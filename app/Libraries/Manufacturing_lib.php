<?php

namespace App\Libraries;

use App\Models\Manufacturing_project;
use App\Models\Manufacturing_project_cost;
use App\Models\Manufacturing_labor_entry;
use App\Models\Manufacturing_overhead_rate;
use App\Models\Manufacturing_stock_transfer;

/**
 * Manufacturing Library
 *
 * Provides business logic for manufacturing project management
 */
class Manufacturing_lib
{
    private Manufacturing_project $projectModel;
    private Manufacturing_project_cost $costModel;
    private Manufacturing_labor_entry $laborModel;
    private Manufacturing_overhead_rate $overheadModel;
    private Manufacturing_stock_transfer $transferModel;

    public function __construct()
    {
        $this->projectModel = model(Manufacturing_project::class);
        $this->costModel = model(Manufacturing_project_cost::class);
        $this->laborModel = model(Manufacturing_labor_entry::class);
        $this->overheadModel = model(Manufacturing_overhead_rate::class);
        $this->transferModel = model(Manufacturing_stock_transfer::class);
    }

    /**
     * Get complete project cost breakdown
     */
    public function get_project_cost_breakdown(int $project_id): array
    {
        $project = $this->projectModel->find($project_id);
        if (!$project) {
            return [];
        }

        // Get material costs from transfers
        $material_cost = $this->get_material_cost($project_id);

        // Get labor costs
        $labor_cost = $this->laborModel->get_total_cost($project_id);
        $total_hours = $this->laborModel->get_total_hours($project_id);

        // Calculate overhead
        $overhead_cost = $this->overheadModel->calculate_overhead(
            $material_cost,
            $labor_cost,
            $total_hours
        );

        // Get budget
        $budget_material = (float) ($project['budgeted_material_cost'] ?? 0);
        $budget_labor = (float) ($project['budgeted_labor_cost'] ?? 0);
        $budget_overhead = (float) ($project['budgeted_overhead_cost'] ?? 0);
        $budget_total = $budget_material + $budget_labor + $budget_overhead;

        // Calculate variances
        $total_cost = $material_cost + $labor_cost + $overhead_cost;

        return [
            'material' => [
                'actual' => $material_cost,
                'budget' => $budget_material,
                'variance' => $budget_material - $material_cost,
                'variance_percent' => $budget_material > 0
                    ? round((($budget_material - $material_cost) / $budget_material) * 100, 2)
                    : 0
            ],
            'labor' => [
                'actual' => $labor_cost,
                'budget' => $budget_labor,
                'variance' => $budget_labor - $labor_cost,
                'variance_percent' => $budget_labor > 0
                    ? round((($budget_labor - $labor_cost) / $budget_labor) * 100, 2)
                    : 0,
                'hours' => $total_hours
            ],
            'overhead' => [
                'actual' => $overhead_cost,
                'budget' => $budget_overhead,
                'variance' => $budget_overhead - $overhead_cost,
                'variance_percent' => $budget_overhead > 0
                    ? round((($budget_overhead - $overhead_cost) / $budget_overhead) * 100, 2)
                    : 0
            ],
            'total' => [
                'actual' => $total_cost,
                'budget' => $budget_total,
                'variance' => $budget_total - $total_cost,
                'variance_percent' => $budget_total > 0
                    ? round((($budget_total - $total_cost) / $budget_total) * 100, 2)
                    : 0
            ]
        ];
    }

    /**
     * Get material cost from confirmed transfers
     */
    public function get_material_cost(int $project_id): float
    {
        $builder = db_connect()->table('manufacturing_stock_transfer_items AS ti');
        $builder->selectSum('total_cost');
        $builder->join('manufacturing_stock_transfers AS t', 't.transfer_id = ti.transfer_id');
        $builder->where('t.project_id', $project_id);
        $builder->where('t.status', 'confirmed');
        $builder->where('t.transfer_type', 'issue');
        $builder->where('t.deleted', 0);

        $result = $builder->get()->getRowArray();
        return (float) ($result['total_cost'] ?? 0);
    }

    /**
     * Get material usage by item
     */
    public function get_material_usage(int $project_id): array
    {
        $builder = db_connect()->table('manufacturing_stock_transfer_items AS ti');
        $builder->select('ti.item_id_fk, i.item_number, i.name, i.description,
                          SUM(ti.quantity) AS total_quantity,
                          SUM(ti.total_cost) AS total_cost');
        $builder->join('manufacturing_stock_transfers AS t', 't.transfer_id = ti.transfer_id');
        $builder->join('items AS i', 'i.item_id = ti.item_id_fk', 'left');
        $builder->where('t.project_id', $project_id);
        $builder->where('t.status', 'confirmed');
        $builder->where('t.transfer_type', 'issue');
        $builder->where('t.deleted', 0);
        $builder->groupBy('ti.item_id_fk');

        return $builder->get()->getResultArray();
    }

    /**
     * Get project progress percentage
     */
    public function get_project_progress(int $project_id): float
    {
        $stageModel = model(Manufacturing_project_stage::class);
        return $stageModel->get_progress($project_id);
    }

    /**
     * Get MRP data - material requirements for planned/in-progress projects
     */
    public function get_mrp_data(): array
    {
        $db = db_connect();

        // Get all items with their current stock
        $items = $db->table('items AS i')
            ->select('i.item_id, i.item_number, i.name, i.description,
                      COALESCE(iq.quantity, 0) AS on_hand')
            ->join('item_quantities AS iq', 'iq.item_id = i.item_id', 'left')
            ->where('i.deleted', 0)
            ->get()
            ->getResultArray();

        // Get reserved quantities (in-progress projects)
        $reserved = $db->table('manufacturing_stock_transfer_items AS ti')
            ->select('ti.item_id_fk, SUM(ti.quantity) AS reserved')
            ->join('manufacturing_stock_transfers AS t', 't.transfer_id = ti.transfer_id')
            ->join('manufacturing_projects AS p', 'p.project_id = t.project_id')
            ->where('t.status', 'confirmed')
            ->where('t.transfer_type', 'issue')
            ->where('p.project_status', 'in_progress')
            ->groupBy('ti.item_id_fk')
            ->get()
            ->getResultArray();

        $reservedMap = [];
        foreach ($reserved as $r) {
            $reservedMap[$r['item_id_fk']] = (float) $r['reserved'];
        }

        // Build MRP result
        $mrp = [];
        foreach ($items as $item) {
            $onHand = (float) $item['on_hand'];
            $reservedQty = $reservedMap[$item['item_id']] ?? 0;
            $available = $onHand - $reservedQty;

            $mrp[] = [
                'item_id' => $item['item_id'],
                'item_number' => $item['item_number'],
                'name' => $item['name'],
                'description' => $item['description'],
                'on_hand' => $onHand,
                'reserved' => $reservedQty,
                'available' => $available,
                'shortage' => $available < 0 ? abs($available) : 0
            ];
        }

        return $mrp;
    }

    /**
     * Create project from sales order
     */
    public function create_from_sale(int $sale_id, int $created_by): int
    {
        $saleModel = model(\App\Models\Sale::class);
        $sale = $saleModel->find($sale_id);

        if (!$sale) {
            return -1;
        }

        $project_code = $this->projectModel->generate_project_code();

        $data = [
            'project_code' => $project_code,
            'project_name' => 'Machine Order #' . $sale['sale_id'],
            'customer_id' => $sale['customer_id'],
            'sale_id' => $sale_id,
            'project_status' => 'planned',
            'created_by' => $created_by
        ];

        $this->projectModel->insert($data);
        return (int) $this->projectModel->getInsertID();
    }

    /**
     * Get dashboard statistics
     */
    public function get_dashboard_stats(): array
    {
        $db = db_connect();

        // Project counts by status
        $statusCounts = $db->table('manufacturing_projects')
            ->select('project_status, COUNT(*) AS count')
            ->where('deleted', 0)
            ->groupBy('project_status')
            ->get()
            ->getResultArray();

        $statusMap = [
            'planned' => 0,
            'in_progress' => 0,
            'on_hold' => 0,
            'completed' => 0,
            'delivered' => 0
        ];

        foreach ($statusCounts as $row) {
            $statusMap[$row['project_status']] = (int) $row['count'];
        }

        // Total costs this month
        $firstDayOfMonth = date('Y-m-01');
        $monthlyCosts = $db->table('manufacturing_project_costs')
            ->selectSum('amount')
            ->where('cost_date >=', $firstDayOfMonth)
            ->get()
            ->getRowArray();

        // Pending transfers
        $pendingTransfers = $db->table('manufacturing_stock_transfers')
            ->where('status', 'draft')
            ->where('deleted', 0)
            ->countAllResults();

        return [
            'projects_by_status' => $statusMap,
            'total_projects' => array_sum($statusMap),
            'monthly_costs' => (float) ($monthlyCosts['amount'] ?? 0),
            'pending_transfers' => $pendingTransfers
        ];
    }
}
