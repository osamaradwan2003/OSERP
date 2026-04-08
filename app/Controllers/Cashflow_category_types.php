<?php

namespace App\Controllers;

use App\Models\Cashflow_category_type;
use App\Traits\SearchableTrait;
use App\Traits\ValidatesInputTrait;
use App\Traits\DeletesEntitiesTrait;
use CodeIgniter\HTTP\ResponseInterface;
use Config\OSPOS;

class Cashflow_category_types extends Secure_Controller
{
    use SearchableTrait;
    use ValidatesInputTrait;
    use DeletesEntitiesTrait;

    private Cashflow_category_type $category_type;
    private array $config;

    public function __construct()
    {
        parent::__construct('cashflow', 'cashflow_manage_categories');

        $this->category_type = model(Cashflow_category_type::class);
        $this->config = config(OSPOS::class)->settings;
    }

    public function getIndex(): string
    {
        $data['table_headers'] = json_encode([
            ['field' => 'type_code', 'title' => lang('Cashflow.category_type_code'), 'sortable' => true, 'switchable' => true],
            ['field' => 'type_label', 'title' => lang('Cashflow.category_type_label'), 'sortable' => true, 'switchable' => true],
            ['field' => 'calc_method', 'title' => lang('Cashflow.category_calc_method'), 'sortable' => true, 'switchable' => true],
            ['field' => 'is_active', 'title' => lang('Cashflow.is_active'), 'sortable' => true, 'switchable' => true],
            ['field' => 'edit', 'title' => '', 'sortable' => false, 'switchable' => false, 'escape' => false]
        ]);
        $data['config'] = $this->config;
        $data['controller_name'] = 'cashflow_category_types';

        return view('cashflow/category_types_manage', $data);
    }

    public function getSearch(): ResponseInterface
    {
        $params = $this->getSearchParams();
        $allowedSort = ['type_code', 'type_label', 'calc_method', 'is_active'];
        $sort = $this->validateSortColumn($allowedSort, $params['sort'], 'type_code');
        $order = $this->validateSortOrder($params['order']);

        $builder = db_connect()->table('cashflow_category_types');
        if ($params['search'] !== '') {
            $builder->groupStart()
                ->like('type_code', $params['search'])
                ->orLike('type_label', $params['search'])
                ->orLike('calc_method', $params['search'])
                ->groupEnd();
        }
        $total = $builder->countAllResults(false);
        $rows = $builder->orderBy($sort, $order)->limit($params['limit'], $params['offset'])->get()->getResultArray();

        $resultRows = [];
        foreach ($rows as $row) {
            $resultRows[] = $this->mapRow($row);
        }

        return $this->buildSearchResponse($total, $resultRows);
    }

    public function getRow(string $type_code): ResponseInterface
    {
        $row = $this->category_type->find($type_code);
        if (!$row) {
            return $this->response->setJSON([]);
        }

        return $this->response->setJSON($this->mapRow($row));
    }

    public function getView($type_code = NEW_ENTRY): string
    {
        $type = $this->category_type->find($type_code);
        if (!$type) {
            $type = [
                'type_code' => '',
                'type_label' => '',
                'calc_method' => 'add',
                'is_active' => 1
            ];
        }

        return view('cashflow/category_type_form', [
            'type' => $type,
            'controller_name' => 'cashflow_category_types'
        ]);
    }

    public function postSave($type_code = NEW_ENTRY): ResponseInterface
    {
        $typeCode = trim((string) $this->request->getPost('type_code'));
        $typeLabel = trim((string) $this->request->getPost('type_label'));
        $calcMethod = trim((string) $this->request->getPost('calc_method'));
        $is_active = $this->request->getPost('is_active') !== null ? 1 : 0;

        if ($typeCode === '') {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.category_type_code_required')]);
        }
        if (!preg_match('/^[a-z0-9_]+$/', $typeCode)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.category_type_code_invalid')]);
        }
        if ($typeLabel === '') {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.category_type_label_required')]);
        }

        $allowedMethods = ['add', 'subtract', 'none', 'transfer'];
        if (!in_array($calcMethod, $allowedMethods, true)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.category_type_calc_invalid')]);
        }
        if ($typeCode === 'transfer' && $calcMethod !== 'transfer') {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.category_type_transfer_calc_required')]);
        }

        $duplicate = $this->category_type
            ->where('type_code', $typeCode)
            ->where('type_code !=', $type_code === NEW_ENTRY ? '' : $type_code)
            ->first();
        if ($duplicate) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.category_type_code_duplicate')]);
        }

        $data = [
            'type_code' => $typeCode,
            'type_label' => $typeLabel,
            'calc_method' => $calcMethod,
            'is_active' => $is_active
        ];

        $success = $type_code === NEW_ENTRY
            ? $this->category_type->insert($data)
            : $this->category_type->update($type_code, $data);

        if (!$success) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.category_type_save_failed')]);
        }

        $id = $typeCode;

        return $this->response->setJSON([
            'success' => true,
            'message' => $type_code === NEW_ENTRY ? lang('Cashflow.category_type_save_success_new') : lang('Cashflow.category_type_save_success_update'),
            'id' => $id
        ]);
    }

    public function postDelete(): ResponseInterface
    {
        $ids = $this->request->getPost('ids');
        if (!is_array($ids) || empty($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.nothing_selected')]);
        }

        $this->category_type->whereIn('type_code', $ids)->set(['is_active' => 0])->update();

        return $this->response->setJSON(['success' => true, 'message' => lang('Cashflow.category_type_archive_success')]);
    }

    private function mapRow(array $row): array
    {
        return [
            'type_code' => $row['type_code'],
            'type_label' => $row['type_label'],
            'calc_method' => lang('Cashflow.calc_' . ($row['calc_method'] ?? 'none')),
            'is_active' => $row['is_active'] ? lang('Common.yes') : lang('Common.no'),
            'edit' => anchor(
                "cashflow_category_types/view/{$row['type_code']}",
                '<span class="glyphicon glyphicon-edit"></span>',
                [
                    'class' => 'modal-dlg',
                    'data-btn-submit' => lang('Common.submit'),
                    'title' => lang('Cashflow.edit_category_type')
                ]
            )
        ];
    }
}
