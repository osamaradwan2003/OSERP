<?php

namespace App\Controllers;

use App\Models\Giftcard;
use App\Traits\SearchableTrait;
use App\Traits\ValidatesInputTrait;
use App\Traits\DeletesEntitiesTrait;
use CodeIgniter\HTTP\ResponseInterface;
use Config\OSPOS;
use Config\Services;

class Giftcards extends Secure_Controller
{
    use SearchableTrait;
    use ValidatesInputTrait;
    use DeletesEntitiesTrait;

    private Giftcard $giftcard;

    public function __construct()
    {
        parent::__construct('giftcards');

        $this->giftcard = model(Giftcard::class);
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        $data['table_headers'] = get_giftcards_manage_table_headers();

        return view('giftcards/manage', $data);
    }

    /**
     * Returns Giftcards table data rows. This will be called with AJAX.
     */
    public function getSearch(): ResponseInterface
    {
        $params = $this->getSearchParams();
        $sort = $this->sanitizeSortColumn(giftcard_headers(), $params['sort'], 'giftcard_id');
        $order = $this->validateSortOrder($params['order']);
        $show_deleted = (bool) ($this->request->getGet('show_deleted', FILTER_SANITIZE_NUMBER_INT) ?? false);

        $giftcards = $this->giftcard->search($params['search'], $params['limit'], $params['offset'], $sort, $order, false, $show_deleted);
        $total_rows = $this->giftcard->get_found_rows($params['search'], $show_deleted);

        $data_rows = [];
        foreach ($giftcards->getResult() as $giftcard) {
            $data_rows[] = get_giftcard_data_row($giftcard);
        }

        return $this->buildSearchResponse($total_rows, $data_rows);
    }

    /**
     * Gets search suggestions for giftcards. Used in app\Views\sales\register.php
     *
     * @return ResponseInterface
     * @noinspection PhpUnused
     */
    public function getSuggest(): ResponseInterface
    {
        $search = $this->request->getGet('term');
        $suggestions = $this->giftcard->get_search_suggestions($search, true);

        return $this->response->setJSON($suggestions);
    }

    /**
     * @return ResponseInterface
     */
    public function suggest_search(): ResponseInterface
    {
        $search = $this->request->getPost('term');
        $suggestions = $this->giftcard->get_search_suggestions($search);

        return $this->response->setJSON($suggestions);
    }

    /**
     * @param int $row_id
     * @return ResponseInterface
     */
    public function getRow(int $row_id): ResponseInterface
    {
        $data_row = get_giftcard_data_row($this->giftcard->get_info($row_id));

        return $this->response->setJSON($data_row);
    }

    /**
     * @param int $giftcard_id
     * @return string
     */
    public function getView(int $giftcard_id = NEW_ENTRY): string
    {
        $config = config(OSPOS::class)->settings;
        $giftcard_info = $this->giftcard->get_info($giftcard_id);

        $data['selected_person_name'] = ($giftcard_id > 0 && isset($giftcard_info->person_id)) ? $giftcard_info->first_name . ' ' . $giftcard_info->last_name : '';
        $data['selected_person_id'] = $giftcard_info->person_id;
        if ($config['giftcard_number'] == 'random') {
            $data['giftcard_number'] = $giftcard_id > 0 ? $giftcard_info->giftcard_number : '';
        } else {
            $max_number_obj = $this->giftcard->get_max_number();
            $max_giftnumber = isset($max_number_obj) ? $this->giftcard->get_max_number()->giftcard_number : 0;    // TODO: variable does not follow naming standard.
            $data['giftcard_number'] = $giftcard_id > 0 ? $giftcard_info->giftcard_number : $max_giftnumber + 1;
        }
        $data['giftcard_id'] = $giftcard_id;
        $data['giftcard_value'] = $giftcard_info->value;

        return view("giftcards/form", $data);
    }

    /**
     * @param int $giftcard_id
     * @return ResponseInterface
     */
    public function postSave(int $giftcard_id = NEW_ENTRY): ResponseInterface
    {
        $giftcard_number = $this->request->getPost('giftcard_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($giftcard_id == NEW_ENTRY && trim($giftcard_number) == '') {
            $giftcard_number = $this->giftcard->generate_unique_giftcard_name($giftcard_number);
        }

        $giftcard_data = [
            'record_time'     => date('Y-m-d H:i:s'),
            'giftcard_number' => $giftcard_number,
            'value'           => parse_decimals($this->request->getPost('giftcard_amount')),
            'person_id'       => empty($this->request->getPost('person_id')) ? null : $this->request->getPost('person_id', FILTER_SANITIZE_NUMBER_INT)
        ];

        if ($this->giftcard->save_value($giftcard_data, $giftcard_id)) {
            // New giftcard
            if ($giftcard_id == NEW_ENTRY) {    // TODO: Constant needed
                return $this->response->setJSON([
                    'success' => true,
                    'message' => lang('Giftcards.successful_adding') . ' ' . $giftcard_data['giftcard_number'],
                    'id'      => $giftcard_data['giftcard_id']
                ]);
            } else { // Existing giftcard
                return $this->response->setJSON([
                    'success' => true,
                    'message' => lang('Giftcards.successful_updating') . ' ' . $giftcard_data['giftcard_number'],
                    'id'      => $giftcard_id
                ]);
            }
        } else { // Failure
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Giftcards.error_adding_updating') . ' ' . $giftcard_data['giftcard_number'],
                'id'      => NEW_ENTRY
            ]);
        }
    }

    /**
     * Checks the giftcard number validity. Used in app\Views\giftcards\form.php
     *
     * @return void
     * @noinspection PhpUnused
     */
    public function postCheckNumberGiftcard(): ResponseInterface
    {
        $existing_id = $this->request->getPost('giftcard_id', FILTER_SANITIZE_NUMBER_INT);
        $giftcard_number = $this->request->getPost('giftcard_number', FILTER_SANITIZE_NUMBER_INT);
        $giftcard_id = $this->giftcard->get_giftcard_id($giftcard_number);
        $success = ($giftcard_id == (int) $existing_id || !$giftcard_id );

        return $this->response->setJSON($success ? 'true' : 'false');
    }

    /**
     * @return ResponseInterface
     */
    public function postDelete(): ResponseInterface
    {
        $giftcards_to_delete = $this->request->getPost('ids', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($this->giftcard->delete_list($giftcards_to_delete)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => lang('Giftcards.successful_deleted') . ' ' . count($giftcards_to_delete) . ' ' . lang('Giftcards.one_or_multiple')
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => lang('Giftcards.cannot_be_deleted')]);
        }
    }
    public function postRestore(): ResponseInterface
    {
        $giftcards_to_restore = $this->request->getPost('ids', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!is_array($giftcards_to_restore) || empty($giftcards_to_restore)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Cashflow.nothing_selected')]);
        }

        $builder = $this->db->table('giftcards');
        $builder->whereIn('giftcard_id', $giftcards_to_restore);

        if ($builder->update(['deleted' => 0])) {
            return $this->response->setJSON(['success' => true, 'message' => lang('Common.restore')]);
        }

        return $this->response->setJSON(['success' => false, 'message' => lang('Giftcards.cannot_be_deleted')]);
    }
}
