<?php

namespace App\Controllers;

use App\Models\Tax_jurisdiction;
use App\Traits\SearchableTrait;
use App\Traits\ValidatesInputTrait;
use App\Traits\DeletesEntitiesTrait;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

/**
 * @property tax_jurisdiction tax_jurisdiction
 */
class Tax_jurisdictions extends Secure_Controller
{
    use SearchableTrait;
    use ValidatesInputTrait;
    use DeletesEntitiesTrait;

    private Tax_jurisdiction $tax_jurisdiction;

    public function __construct()
    {
        parent::__construct('tax_jurisdictions');

        $this->tax_jurisdiction = model(Tax_jurisdiction::class);

        helper('tax_helper');
    }


    /**
     * @return string
     */
    public function getIndex(): string
    {
        $data['table_headers'] = get_tax_jurisdictions_table_headers();

        return view('taxes/tax_jurisdictions', $data);
    }

    /**
     * Returns tax_category table data rows. This will be called with AJAX.
     *
     * @return void
     */
    public function getSearch(): ResponseInterface
    {
        $params = $this->getSearchParams();
        $sort = $params['sort'] ?: 'jurisdiction_id';
        $order = $this->validateSortOrder($params['order']);

        $tax_jurisdictions = $this->tax_jurisdiction->search($params['search'], $params['limit'], $params['offset'], $sort, $order);
        $total_rows = $this->tax_jurisdiction->get_found_rows($params['search']);

        $data_rows = [];
        foreach ($tax_jurisdictions->getResult() as $tax_jurisdiction) {
            $data_rows[] = get_tax_jurisdictions_data_row($tax_jurisdiction);
        }

        return $this->buildSearchResponse($total_rows, $data_rows);
    }

    /**
     * @param int $row_id
     * @return ResponseInterface
     */
    public function getRow(int $row_id): ResponseInterface
    {
        $data_row = get_tax_jurisdictions_data_row($this->tax_jurisdiction->get_info($row_id));

        return $this->response->setJSON($data_row);
    }

    /**
     * @param int $tax_jurisdiction_id
     * @return string
     */
    public function getView(int $tax_jurisdiction_id = NEW_ENTRY): string
    {
        $data['tax_jurisdiction_info'] = $this->tax_jurisdiction->get_info($tax_jurisdiction_id);

        return view("taxes/tax_jurisdiction_form", $data);
    }


    /**
     * @param int $jurisdiction_id
     * @return ResponseInterface
     */
    public function postSave(int $jurisdiction_id = NEW_ENTRY): ResponseInterface
    {
        $tax_jurisdiction_data = [
            'jurisdiction_name'   => $this->request->getPost('jurisdiction_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'reporting_authority' => $this->request->getPost('reporting_authority', FILTER_SANITIZE_FULL_SPECIAL_CHARS)
        ];

        if ($this->tax_jurisdiction->save_value($tax_jurisdiction_data)) {
            if ($jurisdiction_id == NEW_ENTRY) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => lang('Tax_jurisdictions.successful_adding'),
                    'id'      => $tax_jurisdiction_data['jurisdiction_id']
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => lang('Tax_jurisdictions.successful_updating'),
                    'id'      => $jurisdiction_id
                ]);
            }
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Tax_jurisdictions.error_adding_updating') . ' ' . $tax_jurisdiction_data['jurisdiction_name'],
                'id'      => NEW_ENTRY
            ]);
        }
    }

    /**
     * @return ResponseInterface
     */
    public function postDelete(): ResponseInterface
    {
        $tax_jurisdictions_to_delete = $this->request->getPost('ids', FILTER_SANITIZE_NUMBER_INT);

        if ($this->tax_jurisdiction->delete_list($tax_jurisdictions_to_delete)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => lang('Tax_jurisdictions.successful_deleted') . ' ' . count($tax_jurisdictions_to_delete) . ' ' . lang('Tax_jurisdictions.one_or_multiple')
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => lang('Tax_jurisdictions.cannot_be_deleted')]);
        }
    }
}

