<?php

namespace App\Controllers;

use App\Models\Cashflow_category;
use App\Models\Cashflow_category_type;
use App\Traits\SearchableTrait;
use App\Traits\ValidatesInputTrait;
use App\Traits\DeletesEntitiesTrait;
use CodeIgniter\HTTP\ResponseInterface;
use Config\OSPOS;

class Cashflow_categories extends Secure_Controller
{
    use SearchableTrait;
    use ValidatesInputTrait;
    use DeletesEntitiesTrait;

    private Cashflow_category $cashflow_category;
    private Cashflow_category_type $category_type;
    private array $config;

    public function __construct()
    {
        parent::__construct('cashflow', 'cashflow_manage_categories');

        $this->cashflow_category = model(Cashflow_category::class);
        $this->category_type = model(Cashflow_category_type::class);
        $this->config = config(OSPOS::class)->settings;
    }

    public function getIndex(): string
    {
        $data['table_headers'] = json_encode([
            ['field' => 'category_id', 'title' => lang('Common.id'), 'sortable' => true, 'switchable' => true],
            ['field' => 'name', 'title' => lang('Cashflow.category_name'), 'sortable' => true, 'switchable' => true],
            ['field' => 'entry_type_label', 'title' => lang('Cashflow.category_type'), 'sortable' => true, 'switchable' => true],
            ['field' => 'is_active', 'title' => lang('Cashflow.is_active'), 'sortable' => true, 'switchable' => true],
            ['field' => 'edit', 'title' => '', 'sortable' => false, 'switchable' => false, 'escape' => false]
        ]);
        $data['config'] = $this->config;
        $data['controller_name'] = 'cashflow_categories';

        return view('cashflow/categories_manage', $data);
    }

    public function getSearch(): ResponseInterface
    {
        $params = $this->getSearchParams();
        $allowedSort = ['category_id', 'name', 'entry_type', 'entry_type_label', 'is_active'];
        $sort = $this->validateSortColumn($allowedSort, $params['sort'], 'category_id');
        $order = $this->validateSortOrder($params['order']);

        if ($sort === 'entry_type_label') {
            $sort = 'types.type_label';
        }

        $builder = db_connect()->table('cashflow_categories AS categories');
        $builder->select('categories.*');
        $builder->select('types.type_label AS entry_type_label', false);
        $builder->join('cashflow_category_types AS types', 'types.type_code = categories.entry_type', 'left');
        if ($params['search'] !== '') {
            $builder->groupStart()
                ->like('categories.name', $params['search'])
                ->orLike('categories.entry_type', $params['search'])
                ->orLike('types.type_label', $params['search'])
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

    public function getRow(int $category_id): ResponseInterface
    {
        $row = $this->cashflow_category->find($category_id);
        if (!$row) {
            return $this->response->setJSON([]);
        }

        return $this->response->setJSON($this->mapRow($row));
    }

    public function getView(int $category_id = NEW_ENTRY): string
    {
        $category = $this->cashflow_category->find($category_id);
        if (!$category) {
            $category = [
                'category_id' => NEW_ENTRY,
                'name' => '',
                'entry_type' => 'income',
                'is_active' => 1
            ];
        }

        return view('cashflow/category_form', [
            'category' => $category,
            'controller_name' => 'cashflow_categories',
            'type_options' => $this->category_type->getActiveOptions()
        ]);
    }

    public function postSave(int $category_id = NEW_ENTRY): ResponseInterface
    {
        $name = trim((string) $this->request->getPost('name'));
        $entry_type = trim((string) $this->request->getPost('entry_type'));
        $is_active = $this->request->getPost('is_active') !== null ? 1 : 0;

        if ($name === '') {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.category_name_required')]);
        }
        $typeMap = $this->category_type->getActiveTypeMap();
        if (!isset($typeMap[$entry_type])) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.category_type_invalid')]);
        }

        $duplicate = $this->cashflow_category
            ->where('name', $name)
            ->where('entry_type', $entry_type)
            ->where('category_id !=', $category_id === NEW_ENTRY ? 0 : $category_id)
            ->first();
        if ($duplicate) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.category_name_duplicate')]);
        }

        $data = [
            'name' => $name,
            'entry_type' => $entry_type,
            'is_active' => $is_active
        ];

        $success = $category_id === NEW_ENTRY
            ? $this->cashflow_category->insert($data)
            : $this->cashflow_category->update($category_id, $data);

        if (!$success) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.category_save_failed')]);
        }

        $id = $category_id === NEW_ENTRY ? (int) $this->cashflow_category->getInsertID() : $category_id;

        return $this->response->setJSON([
            'success' => true,
            'message' => $category_id === NEW_ENTRY ? lang('Cashflow.category_save_success_new') : lang('Cashflow.category_save_success_update'),
            'id' => $id
        ]);
    }

    public function postDelete(): ResponseInterface
    {
        $ids = $this->request->getPost('ids');
        if (!is_array($ids) || empty($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.nothing_selected')]);
        }

        $this->cashflow_category->whereIn('category_id', $ids)->set(['is_active' => 0])->update();

        return $this->response->setJSON(['success' => true, 'message' => lang('Cashflow.category_archive_success')]);
    }

    private function mapRow(array $row): array
    {
        return [
            'category_id' => $row['category_id'],
            'name' => $row['name'],
            'entry_type_label' => $row['entry_type_label'] ?? $row['entry_type'],
            'is_active' => $row['is_active'] ? lang('Common.yes') : lang('Common.no'),
            'edit' => anchor(
                "cashflow_categories/view/{$row['category_id']}",
                '<span class="glyphicon glyphicon-edit"></span>',
                [
                    'class' => 'modal-dlg',
                    'data-btn-submit' => lang('Common.submit'),
                    'title' => lang('Cashflow.edit_category')
                ]
            )
        ];
    }
}
