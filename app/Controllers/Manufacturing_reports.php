<?php

namespace App\Controllers;

use App\Models\Manufacturing_project;
use App\Models\Manufacturing_labor_entry;
use App\Models\Manufacturing_stock_transfer;
use App\Traits\SearchableTrait;
use App\Traits\ReportDataTrait;
use CodeIgniter\HTTP\ResponseInterface;

require_once('Secure_Controller.php');

/**
 * Manufacturing Reports Controller
 */
class Manufacturing_reports extends Secure_Controller
{
    use SearchableTrait;
    use ReportDataTrait;

    private Manufacturing_project $project;
    private Manufacturing_labor_entry $laborEntry;
    private Manufacturing_stock_transfer $transfer;

    public function __construct()
    {
        parent::__construct('manufacturing');
        $this->project = model(Manufacturing_project::class);
        $this->laborEntry = model(Manufacturing_labor_entry::class);
        $this->transfer = model(Manufacturing_stock_transfer::class);
    }

    /**
     * Reports index page
     */
    public function getIndex(): string
    {
        $data['projects'] = $this->project->findAll();

        return view('manufacturing/reports/index', $data);
    }

    /**
     * Project costs report
     */
    public function getProjectCosts(): string|ResponseInterface
    {
        $project_id = $this->request->getGet('project_id');
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-t');

        $data = [
            'project_id' => $project_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'projects' => $this->project->findAll(),
        ];

        if ($project_id) {
            $data['costs'] = $this->project->getProjectCosts($project_id, $start_date, $end_date);
        }

        return view('manufacturing/reports/project_costs', $data);
    }

    /**
     * Material usage report
     */
    public function getMaterialUsage(): string|ResponseInterface
    {
        $project_id = $this->request->getGet('project_id');
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-t');

        $data = [
            'project_id' => $project_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'projects' => $this->project->findAll(),
        ];

        if ($project_id) {
            $data['materials'] = $this->transfer->getMaterialUsage($project_id, $start_date, $end_date);
        }

        return view('manufacturing/reports/material_usage', $data);
    }

    /**
     * Project progress report
     */
    public function getProjectProgress(): string|ResponseInterface
    {
        $project_id = $this->request->getGet('project_id');

        $data = [
            'project_id' => $project_id,
            'projects' => $this->project->findAll(),
        ];

        if ($project_id) {
            $data['progress'] = $this->project->getProjectProgress($project_id);
        }

        return view('manufacturing/reports/project_progress', $data);
    }

    /**
     * MRP (Material Requirements Planning) report
     */
    public function getMrp(): string|ResponseInterface
    {
        $project_id = $this->request->getGet('project_id');

        $data = [
            'project_id' => $project_id,
            'projects' => $this->project->where('project_status', 'in_progress')->findAll(),
        ];

        if ($project_id) {
            $data['mrp'] = $this->project->getMrpData($project_id);
        }

        return view('manufacturing/reports/mrp', $data);
    }

    /**
     * Cost variance report
     */
    public function getCostVariance(): string|ResponseInterface
    {
        $project_id = $this->request->getGet('project_id');

        $data = [
            'project_id' => $project_id,
            'projects' => $this->project->where('project_status', 'completed')->findAll(),
        ];

        if ($project_id) {
            $data['variance'] = $this->project->getCostVariance($project_id);
        }

        return view('manufacturing/reports/cost_variance', $data);
    }
}
