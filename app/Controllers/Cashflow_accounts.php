<?php

namespace App\Controllers;

use App\Models\Cashflow_account;
use App\Traits\SearchableTrait;
use App\Traits\ValidatesInputTrait;
use App\Traits\DeletesEntitiesTrait;
use CodeIgniter\HTTP\ResponseInterface;
use Config\OSPOS;

class Cashflow_accounts extends Secure_Controller
{
    use SearchableTrait;
    use ValidatesInputTrait;
    use DeletesEntitiesTrait;

    private Cashflow_account $cashflow_account;
    private array $config;

    public function __construct()
    {
        parent::__construct('cashflow', 'cashflow_manage_accounts');

        $this->cashflow_account = model(Cashflow_account::class);
        $this->config = config(OSPOS::class)->settings;
    }

    public function getIndex(): string
    {
        $data['table_headers'] = json_encode([
            ['field' => 'account_id', 'title' => lang('Common.id'), 'sortable' => true, 'switchable' => true],
            ['field' => 'name', 'title' => lang('Cashflow.account_name'), 'sortable' => true, 'switchable' => true],
            ['field' => 'type', 'title' => lang('Cashflow.account_type'), 'sortable' => true, 'switchable' => true],
            ['field' => 'opening_balance', 'title' => lang('Cashflow.opening_balance'), 'sortable' => true, 'switchable' => true],
            ['field' => 'is_active', 'title' => lang('Cashflow.is_active'), 'sortable' => true, 'switchable' => true],
            ['field' => 'edit', 'title' => '', 'sortable' => false, 'switchable' => false, 'escape' => false]
        ]);
        $data['controller_name'] = 'cashflow_accounts';

        return view('cashflow/accounts_manage', $data);
    }

    public function getSearch(): ResponseInterface
    {
        $params = $this->getSearchParams();
        $allowedSort = ['account_id', 'name', 'type', 'opening_balance', 'is_active'];
        $sort = $this->validateSortColumn($allowedSort, $params['sort'], 'account_id');
        $order = $this->validateSortOrder($params['order']);

        $builder = db_connect()->table('cashflow_accounts');
        if ($params['search'] !== '') {
            $builder->groupStart()
                ->like('name', $params['search'])
                ->orLike('type', $params['search'])
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

    public function getRow(int $account_id): ResponseInterface
    {
        $row = $this->cashflow_account->find($account_id);
        if (!$row) {
            return $this->response->setJSON([]);
        }

        return $this->response->setJSON($this->mapRow($row));
    }

    public function getView(int $account_id = NEW_ENTRY): string
    {
        $account = $this->cashflow_account->find($account_id);
        if (!$account) {
            $account = [
                'account_id' => NEW_ENTRY,
                'name' => '',
                'type' => 'bank',
                'opening_balance' => '0.00',
                'is_active' => 1
            ];
        }

        return view('cashflow/account_form', ['account' => $account, 'controller_name' => 'cashflow_accounts']);
    }

    public function postSave(int $account_id = NEW_ENTRY): ResponseInterface
    {
        $name = trim((string) $this->request->getPost('name'));
        $type = trim((string) $this->request->getPost('type'));
        $openingBalance = parse_decimals((string) $this->request->getPost('opening_balance'));
        $isActive = $this->request->getPost('is_active') !== null ? 1 : 0;

        if ($name === '') {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.account_name_required')]);
        }
        if (!in_array($type, ['bank', 'cash', 'other'], true)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.account_type_invalid')]);
        }
        if ($openingBalance === false) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.opening_balance_invalid')]);
        }

        $duplicate = $this->cashflow_account
            ->where('name', $name)
            ->where('account_id !=', $account_id === NEW_ENTRY ? 0 : $account_id)
            ->first();
        if ($duplicate) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.account_name_duplicate')]);
        }

        $data = [
            'name' => $name,
            'type' => $type,
            'opening_balance' => $openingBalance,
            'is_active' => $isActive
        ];

        $success = $account_id === NEW_ENTRY
            ? $this->cashflow_account->insert($data)
            : $this->cashflow_account->update($account_id, $data);

        if (!$success) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.account_save_failed')]);
        }

        $id = $account_id === NEW_ENTRY ? (int) $this->cashflow_account->getInsertID() : $account_id;

        return $this->response->setJSON([
            'success' => true,
            'message' => $account_id === NEW_ENTRY ? lang('Cashflow.account_save_success_new') : lang('Cashflow.account_save_success_update'),
            'id' => $id
        ]);
    }

    public function postDelete(): ResponseInterface
    {
        $ids = $this->request->getPost('ids');
        if (!is_array($ids) || empty($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.nothing_selected')]);
        }

        $this->cashflow_account->whereIn('account_id', $ids)->set(['is_active' => 0])->update();

        return $this->response->setJSON(['success' => true, 'message' => lang('Cashflow.account_archive_success')]);
    }

    private function mapRow(array $row): array
    {
        return [
            'account_id' => $row['account_id'],
            'name' => $row['name'],
            'type' => lang('Cashflow.account_type_' . $row['type']),
            'opening_balance' => to_currency($row['opening_balance']),
            'is_active' => $row['is_active'] ? lang('Common.yes') : lang('Common.no'),
            'edit' => anchor(
                "cashflow_accounts/view/{$row['account_id']}",
                '<span class="glyphicon glyphicon-edit"></span>',
                [
                    'class' => 'modal-dlg',
                    'data-btn-submit' => lang('Common.submit'),
                    'title' => lang('Cashflow.edit_account')
                ]
            )
        ];
    }
}
