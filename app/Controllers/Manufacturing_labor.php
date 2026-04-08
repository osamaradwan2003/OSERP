<?php

namespace App\Controllers;

use App\Models\Manufacturing_project;
use App\Models\Manufacturing_labor_entry;
use App\Models\Employee;
use App\Traits\SearchableTrait;
use App\Traits\ValidatesInputTrait;
use App\Traits\DeletesEntitiesTrait;
use CodeIgniter\HTTP\ResponseInterface;

require_once('Secure_Controller.php');

/**
 * Manufacturing Labor Controller
 */
class Manufacturing_labor extends Secure_Controller
{
    use SearchableTrait;
    use ValidatesInputTrait;
    use DeletesEntitiesTrait;

    private Manufacturing_project $project;
    private Manufacturing_labor_entry $laborEntry;

    public function __construct()
    {
        parent::__construct('manufacturing');
        $this->project = model(Manufacturing_project::class);
        $this->laborEntry = model(Manufacturing_labor_entry::class);
    }

    /**
     * Labor entries listing page
     */
    public function getIndex(): string
    {
        $data['table_headers'] = json_encode([
            ['field' => 'labor_id', 'title' => lang('Manufacturing.labor_id'), 'sortable' => true],
            ['field' => 'project_name', 'title' => lang('Manufacturing.project'), 'sortable' => false],
            ['field' => 'employee_name', 'title' => lang('Manufacturing.employee'), 'sortable' => false],
            ['field' => 'work_date', 'title' => lang('Manufacturing.work_date'), 'sortable' => true],
            ['field' => 'hours', 'title' => lang('Manufacturing.hours'), 'sortable' => true],
            ['field' => 'hourly_rate', 'title' => lang('Manufacturing.hourly_rate'), 'sortable' => true],
            ['field' => 'total_cost', 'title' => lang('Manufacturing.total_cost'), 'sortable' => true],
        ]);

        $data['projects'] = $this->project->where('project_status !=', 'completed')->findAll();
        $data['employees'] = $this->employee->findAll();

        return view('manufacturing/labor/manage', $data);
    }

    /**
     * Search labor entries
     */
    public function getSearch(): ResponseInterface
    {
        $filters = [
            'project_id' => $this->request->getGet('project_id'),
            'employee_id' => $this->request->getGet('employee_id'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
        ];

        $entries = $this->laborEntry->getEntriesWithDetails($filters);

        $result = [];
        foreach ($entries as $entry) {
            $result[] = [
                'labor_id' => $entry['labor_id'],
                'project_name' => $entry['project_name'],
                'employee_name' => $entry['employee_name'],
                'work_date' => $entry['work_date'],
                'hours' => $entry['hours'],
                'hourly_rate' => to_currency_no_money($entry['hourly_rate']),
                'total_cost' => to_currency($entry['hours'] * $entry['hourly_rate']),
            ];
        }

        return $this->response->setJSON(['data' => $result]);
    }

    /**
     * Create labor entry form
     */
    public function getCreate(?int $project_id = null): string
    {
        $data = [
            'labor_id' => null,
            'project_id' => $project_id,
            'employee_id' => null,
            'work_date' => date('Y-m-d'),
            'hours' => 0,
            'hourly_rate' => 0,
            'description' => '',
        ];

        $data['projects'] = $this->project->where('project_status !=', 'completed')->findAll();
        $data['employees'] = $this->employee->findAll();

        return view('manufacturing/labor/form', $data);
    }

    /**
     * View labor entry
     */
    public function getView(int $labor_id = NEW_ENTRY): string
    {
        $entry = $this->laborEntry->find($labor_id);
        if (!$entry) {
            return $this->getIndex();
        }

        $data = $entry;
        $data['projects'] = $this->project->findAll();
        $data['employees'] = $this->employee->findAll();

        return view('manufacturing/labor/form', $data);
    }

    /**
     * Save labor entry
     */
    public function postSave(int $labor_id = NEW_ENTRY): ResponseInterface
    {
        $labor_id = $this->request->getPost('labor_id');

        $data = [
            'project_id' => $this->request->getPost('project_id'),
            'employee_id' => $this->request->getPost('employee_id'),
            'work_date' => $this->request->getPost('work_date'),
            'hours' => parse_decimals($this->request->getPost('hours')),
            'hourly_rate' => parse_decimals($this->request->getPost('hourly_rate')),
            'description' => $this->request->getPost('description'),
        ];

        if ($labor_id) {
            $this->laborEntry->update($labor_id, $data);
            $message = lang('Manufacturing.labor_updated');
        } else {
            $labor_id = $this->laborEntry->insert($data);
            $message = lang('Manufacturing.labor_added');
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $message,
            'id' => $labor_id,
        ]);
    }

    /**
     * Delete labor entries
     */
    public function postDelete(): ResponseInterface
    {
        $labor_ids = $this->request->getPost('labor_ids');

        if (empty($labor_ids)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Manufacturing.no_entries_selected'),
            ]);
        }

        $this->laborEntry->whereIn('labor_id', $labor_ids)->delete();

        return $this->response->setJSON([
            'success' => true,
            'message' => lang('Manufacturing.labor_deleted'),
        ]);
    }
}
