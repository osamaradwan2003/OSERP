<?php

namespace App\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Manufacturing_project;
use App\Models\Manufacturing_project_stage;
use App\Models\Sale;
use App\Traits\SearchableTrait;
use App\Traits\ValidatesInputTrait;
use App\Traits\DeletesEntitiesTrait;
use CodeIgniter\HTTP\ResponseInterface;

require_once('Secure_Controller.php');

/**
 * Manufacturing Projects Controller
 */
class Manufacturing_projects extends Secure_Controller
{
    use SearchableTrait;
    use ValidatesInputTrait;
    use DeletesEntitiesTrait;

    private Manufacturing_project $project;
    private Manufacturing_project_stage $stage;
    private Customer $customer;
    private Sale $sale;

    public function __construct()
    {
        parent::__construct('manufacturing');
        $this->project = model(Manufacturing_project::class);
        $this->stage = model(Manufacturing_project_stage::class);
        $this->customer = model(Customer::class);
        $this->sale = model(Sale::class);
    }

    /**
     * Project listing page
     */
    public function getIndex(): string
    {
        $data['table_headers'] = json_encode([
            ['field' => 'project_id', 'title' => lang('Manufacturing.project_code'), 'sortable' => true],
            ['field' => 'project_name', 'title' => lang('Manufacturing.project_name'), 'sortable' => true],
            ['field' => 'customer_name', 'title' => lang('Manufacturing.customer'), 'sortable' => false],
            ['field' => 'project_status', 'title' => lang('Manufacturing.project_status'), 'sortable' => true],
            ['field' => 'priority', 'title' => lang('Manufacturing.priority'), 'sortable' => true],
            ['field' => 'start_date', 'title' => lang('Manufacturing.start_date'), 'sortable' => true],
            ['field' => 'target_completion_date', 'title' => lang('Manufacturing.target_completion_date'), 'sortable' => true],
            ['field' => 'manager_name', 'title' => lang('Manufacturing.project_manager'), 'sortable' => false],
            ['field' => 'edit', 'title' => '', 'sortable' => false, 'escape' => false]
        ]);
        $data['controller_name'] = 'manufacturing_projects';

        return view('manufacturing/projects/manage', $data);
    }

    /**
     * Search projects via AJAX
     */
    public function getSearch(): ResponseInterface
    {
        $params = $this->getSearchParams();
        $sort = $this->sanitizeSortColumn(['project_id', 'project_name', 'project_status', 'priority', 'start_date', 'target_completion_date'], $params['sort'], 'project_id');
        $order = $this->validateSortOrder($params['order']);

        $projects = $this->project->search($params['search'], $params['limit'], $params['offset'], $sort, $order);
        $total_rows = $this->project->get_found_rows($params['search']);

        $data_rows = [];
        foreach ($projects as $project) {
            $data_rows[] = $this->mapProjectRow($project);
        }

        return $this->buildSearchResponse($total_rows, $data_rows);
    }

    /**
     * Get single project row
     */
    public function getRow(int $project_id): ResponseInterface
    {
        $project = $this->project->get_info($project_id);
        if (empty($project)) {
            return $this->response->setJSON([]);
        }

        return $this->response->setJSON($this->mapProjectRow($project));
    }

    /**
     * Project create/edit form
     */
    public function getView(int $project_id = NEW_ENTRY): string
    {
        $project_info = $this->project->get_info($project_id);

        if (empty($project_info)) {
            $project_info = [
                'project_id' => NEW_ENTRY,
                'project_code' => $this->project->generate_project_code(),
                'project_name' => '',
                'customer_id' => null,
                'sale_id' => null,
                'project_status' => 'planned',
                'priority' => 'normal',
                'start_date' => date('Y-m-d'),
                'target_completion_date' => null,
                'estimated_hours' => null,
                'budgeted_material_cost' => null,
                'budgeted_labor_cost' => null,
                'budgeted_overhead_cost' => null,
                'project_manager_id' => null,
                'notes' => ''
            ];
        }

        // Get customers for dropdown
        $customers = ['' => lang('Common.none_selected_text')];
        foreach ($this->customer->get_all()->getResultArray() as $row) {
            $customers[$row['person_id']] = trim($row['company_name'] . ' - ' . $row['first_name'] . ' ' . $row['last_name']);
        }

        // Get employees for project manager dropdown
        $employees = ['' => lang('Common.none_selected_text')];
        foreach ($this->employee->get_all()->getResultArray() as $row) {
            $employees[$row['person_id']] = $row['first_name'] . ' ' . $row['last_name'];
        }

        // Get stages for this project
        $stages = [];
        if ($project_id > 0) {
            $stages = $this->stage->get_by_project($project_id);
        }

        $data = [
            'project' => $project_info,
            'customers' => $customers,
            'employees' => $employees,
            'stages' => $stages,
            'controller_name' => 'manufacturing_projects'
        ];

        return view('manufacturing/projects/form', $data);
    }

    /**
     * Save project
     */
    public function postSave(int $project_id = NEW_ENTRY): ResponseInterface
    {
        $project_name = trim((string) $this->request->getPost('project_name'));

        if ($project_name === '') {
            return $this->response->setJSON(['success' => false, 'message' => lang('Manufacturing.project_name_required')]);
        }

        $data = [
            'project_name' => $project_name,
            'customer_id' => $this->nullableInt($this->request->getPost('customer_id')),
            'sale_id' => $this->nullableInt($this->request->getPost('sale_id')),
            'project_status' => $this->request->getPost('project_status') ?? 'planned',
            'priority' => $this->request->getPost('priority') ?? 'normal',
            'start_date' => $this->request->getPost('start_date') ?: null,
            'target_completion_date' => $this->request->getPost('target_completion_date') ?: null,
            'estimated_hours' => $this->request->getPost('estimated_hours') ?: null,
            'budgeted_material_cost' => $this->request->getPost('budgeted_material_cost') ?: null,
            'budgeted_labor_cost' => $this->request->getPost('budgeted_labor_cost') ?: null,
            'budgeted_overhead_cost' => $this->request->getPost('budgeted_overhead_cost') ?: null,
            'project_manager_id' => $this->nullableInt($this->request->getPost('project_manager_id')),
            'notes' => $this->request->getPost('notes')
        ];

        if ($project_id === NEW_ENTRY) {
            $data['project_code'] = $this->project->generate_project_code();
            $data['created_by'] = (int) $this->employee->get_logged_in_employee_info()->person_id;
        }

        $success = $project_id === NEW_ENTRY
            ? $this->project->insert($data)
            : $this->project->update($project_id, $data);

        if (!$success) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Manufacturing.error_adding_updating')]);
        }

        $id = $project_id === NEW_ENTRY ? (int) $this->project->getInsertID() : $project_id;

        return $this->response->setJSON([
            'success' => true,
            'message' => $project_id === NEW_ENTRY
                ? lang('Manufacturing.successful_adding') . ' ' . $project_name
                : lang('Manufacturing.successful_updating') . ' ' . $project_name,
            'id' => $id
        ]);
    }

    /**
     * Delete projects
     */
    public function postDelete(): ResponseInterface
    {
        $ids = $this->request->getPost('ids');
        if (!is_array($ids) || empty($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Manufacturing.nothing_selected')]);
        }

        foreach ($ids as $id) {
            $this->project->soft_delete((int) $id);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => lang('Manufacturing.successful_deleting') . ' ' . count($ids) . ' ' . lang('Manufacturing.one_or_multiple')
        ]);
    }

    /**
     * Save stage
     */
    public function postSaveStage(): ResponseInterface
    {
        $project_id = (int) $this->request->getPost('project_id');
        $stage_name = trim((string) $this->request->getPost('stage_name'));

        if ($stage_name === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Stage name is required']);
        }

        $data = [
            'project_id' => $project_id,
            'stage_name' => $stage_name,
            'stage_sequence' => (int) $this->request->getPost('stage_sequence'),
            'assigned_to' => $this->nullableInt($this->request->getPost('assigned_to')),
            'notes' => $this->request->getPost('notes')
        ];

        $stage_id = $this->request->getPost('stage_id');
        if ($stage_id) {
            $this->stage->update($stage_id, $data);
        } else {
            $this->stage->insert($data);
        }

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Update stage status
     */
    public function postUpdateStageStatus(): ResponseInterface
    {
        $stage_id = (int) $this->request->getPost('stage_id');
        $status = $this->request->getPost('status');

        if ($this->stage->update_status($stage_id, $status)) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Error updating stage status']);
    }

    /**
     * Delete stage
     */
    public function getDeleteStage(int $stage_id): ResponseInterface
    {
        if ($this->stage->delete($stage_id)) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false]);
    }

    /**
     * Map project row for table
     */
    private function mapProjectRow(array $project): array
    {
        $customer_name = trim(($project['customer_company'] ?? '') . ' ' . ($project['first_name'] ?? '') . ' ' . ($project['last_name'] ?? ''));
        $manager_name = trim(($project['manager_first_name'] ?? '') . ' ' . ($project['manager_last_name'] ?? ''));

        return [
            'project_id' => $project['project_id'],
            'project_code' => $project['project_code'],
            'project_name' => $project['project_name'],
            'customer_name' => $customer_name,
            'project_status' => lang('Manufacturing.status_' . $project['project_status']),
            'priority' => lang('Manufacturing.priority_' . $project['priority']),
            'start_date' => $project['start_date'] ?? '',
            'target_completion_date' => $project['target_completion_date'] ?? '',
            'manager_name' => $manager_name,
            'edit' => anchor(
                "manufacturing/projects/view/{$project['project_id']}",
                '<span class="glyphicon glyphicon-edit"></span>',
                [
                    'class' => 'modal-dlg',
                    'data-btn-submit' => lang('Common.submit'),
                    'title' => lang('Manufacturing.edit_project')
                ]
            )
        ];
    }

    /**
     * Get nullable int from POST
     */
    private function nullableInt($value): ?int
    {
        return $value !== null && $value !== '' ? (int) $value : null;
    }
}
