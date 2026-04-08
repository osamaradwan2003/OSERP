<?php

namespace App\Controllers;

use App\Libraries\Sale_lib;
use App\Models\Customer;
use App\Models\Price_offer_condition;
use App\Models\Price_offer_condition_link;
use App\Models\Sale;
use HTMLPurifier;
use HTMLPurifier_Config;
use Config\OSPOS;
use CodeIgniter\HTTP\ResponseInterface;

class Price_offers extends Secure_Controller
{
    private Sale $sale;
    private Customer $customer;
    private Price_offer_condition $condition;
    private Price_offer_condition_link $condition_link;
    private array $config;

    public function __construct()
    {
        parent::__construct('price_offers');

        $this->sale = model(Sale::class);
        $this->customer = model(Customer::class);
        $this->condition = model(Price_offer_condition::class);
        $this->condition_link = model(Price_offer_condition_link::class);
        $this->config = config(OSPOS::class)->settings;
    }

    public function getIndex(): string
    {
        $customer_id = (int) $this->request->getGet('customer_id', FILTER_SANITIZE_NUMBER_INT);
        $offers = $this->getOffers($customer_id > 0 ? $customer_id : null);

        return view('price_offers/manage', [
            'offers' => $offers,
            'selected_customer_id' => $customer_id,
            'config' => $this->config,
        ]);
    }

    public function getCreate(): ResponseInterface
    {
        $sale_lib = new Sale_lib();
        $sale_lib->clear_all();
        $sale_lib->set_mode('sale_quote');

        return redirect()->to(site_url('sales'));
    }

    public function getView(int $data_item_id = -1)
    {
        if ($data_item_id <= 0) {
            return redirect()->to(site_url('price_offers'));
        }

        $data = $this->buildOfferData($data_item_id);
        $data['is_pdf'] = false;
        $data['conditions'] = $this->getActiveConditions();
        $data['selected_condition_ids'] = $this->getSelectedConditionIds($data_item_id);

        return view('price_offers/offer', $data);
    }

    public function getConditions(): string
    {
        return view('price_offers/conditions', [
            'conditions' => $this->getAllConditions()
        ]);
    }

    public function postSaveCondition(): ResponseInterface
    {
        $id = (int) $this->request->getPost('id', FILTER_SANITIZE_NUMBER_INT);
        $raw_description = $this->request->getPost('description');
        $description = $this->sanitizeConditionHtml(is_string($raw_description) ? $raw_description : '');
        $data = [
            'name' => $this->request->getPost('name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'title' => $this->request->getPost('title', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'description' => $description,
            'sort' => (int) $this->request->getPost('sort', FILTER_SANITIZE_NUMBER_INT),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($id > 0) {
            $this->condition->update($id, $data);
        } else {
            $this->condition->insert($data);
        }

        return redirect()->to(site_url('price_offers/conditions'));
    }

    public function postDeleteCondition(): ResponseInterface
    {
        $id = (int) $this->request->getPost('id', FILTER_SANITIZE_NUMBER_INT);
        if ($id > 0) {
            $this->condition->delete($id);
            $this->condition_link->where('condition_id', $id)->delete();
        }

        return redirect()->to(site_url('price_offers/conditions'));
    }

    public function postSaveOfferConditions(int $sale_id): ResponseInterface
    {
        $condition_ids = $this->request->getPost('condition_ids') ?? [];
        $condition_ids = array_filter(array_map('intval', (array) $condition_ids));

        $this->condition_link->where('sale_id', $sale_id)->delete();

        foreach ($condition_ids as $condition_id) {
            $this->condition_link->insert([
                'sale_id' => $sale_id,
                'condition_id' => $condition_id,
            ]);
        }

        return redirect()->to(site_url('price_offers/view/' . $sale_id));
    }

    private function getOffers(?int $customer_id = null): array
    {
        $builder = $this->sale->builder();
        $builder->select('sales.sale_id, sales.quote_number, sales.sale_time, sales.customer_id, people.first_name, people.last_name, customers.company_name');
        $builder->join('people', 'people.person_id = sales.customer_id', 'left');
        $builder->join('customers', 'customers.person_id = sales.customer_id', 'left');
        $builder->where('sales.sale_status', SUSPENDED);
        $builder->where('sales.sale_type', SALE_TYPE_QUOTE);

        if (!empty($customer_id)) {
            $builder->where('sales.customer_id', $customer_id);
        }

        $builder->orderBy('sales.sale_time', 'desc');

        $offers = $builder->get()->getResultArray();

        foreach ($offers as &$offer) {
            $offer['customer_name'] = $this->formatCustomerName($offer);
            $offer['sale_date'] = to_date(strtotime($offer['sale_time']));
            $offer['quote_number_display'] = $offer['quote_number'] ?: ('Q' . $offer['sale_id']);
        }

        return $offers;
    }

    private function buildOfferData(int $sale_id): array
    {
        $sale_info = $this->sale->get_info($sale_id)->getRowArray();
        if (empty($sale_info)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $customer = $this->customer->get_info($sale_info['customer_id']);
        $customer_company = $customer ? trim((string) ($customer->company_name ?? '')) : '';
        $customer_name = $customer ? trim($customer->first_name . ' ' . $customer->last_name) : '';

        if ($customer_company === '') {
            $customer_company = $customer_name;
        }

        $items = $this->sale->get_sale_items_ordered($sale_id)->getResultArray();

        $offer_items = [];
        $total = 0.0;
        $index = 1;
        $decimals = totals_decimals();

        foreach ($items as $item) {
            if (isset($item['print_option']) && (int) $item['print_option'] === PRINT_NO) {
                continue;
            }

            $quantity = (float) $item['quantity_purchased'];
            $unit_price = (float) $item['item_unit_price'];
            $discount = (float) $item['discount'];
            $discount_type = (int) $item['discount_type'];

            if ($discount_type === PERCENT) {
                $line_total = $quantity * $unit_price * (1 - ($discount / 100));
            } else {
                $line_total = $quantity * ($unit_price - $discount);
            }

            $line_total = round($line_total, $decimals);
            $total += $line_total;

            $description_parts = [];
            $name = trim((string) $item['name']);
            if ($name !== '') {
                $description_parts[] = $name;
            }
            $desc = trim((string) $item['description']);
            if ($desc !== '') {
                $description_parts[] = $desc;
            }

            $offer_items[] = [
                'index' => $index++,
                'description' => implode("\n", $description_parts),
                'quantity' => $quantity,
                'price' => $line_total,
            ];
        }

        $company_logo = trim((string) ($this->config['company_logo'] ?? ''));
        $logo_src = $company_logo !== '' ? $this->buildAssetDataUri('uploads/' . $company_logo) : '';
        $whatsapp_src = $this->buildAssetDataUri('images/price_offers/image2.jpeg');

        $selected_conditions = $this->getSelectedConditions($sale_id);

        return [
            'sale_id' => $sale_id,
            'quote_number' => $sale_info['quote_number'] ?? '',
            'offer_date' => to_date(strtotime($sale_info['sale_time'])),
            'customer_company' => $customer_company,
            'customer_attention' => $customer_name,
            'offer_description' => $sale_info['comment'] ?? '',
            'selected_conditions' => $selected_conditions,
            'items' => $offer_items,
            'total' => $total,
            'assets_base' => base_url('images/price_offers'),
            'logo_src' => $logo_src,
            'watermark_src' => $logo_src,
            'whatsapp_src' => $whatsapp_src,
        ];
    }

    private function getAllConditions(): array
    {
        return $this->condition
            ->orderBy('sort', 'asc')
            ->orderBy('id', 'asc')
            ->findAll();
    }

    private function getActiveConditions(): array
    {
        return $this->condition
            ->where('is_active', 1)
            ->orderBy('sort', 'asc')
            ->orderBy('id', 'asc')
            ->findAll();
    }

    private function getSelectedConditionIds(int $sale_id): array
    {
        $rows = $this->condition_link
            ->select('condition_id')
            ->where('sale_id', $sale_id)
            ->findAll();

        return array_map(fn ($row) => (int) $row['condition_id'], $rows);
    }

    private function getSelectedConditions(int $sale_id): array
    {
        $ids = $this->getSelectedConditionIds($sale_id);
        if (empty($ids)) {
            return [];
        }

        return $this->condition
            ->whereIn('id', $ids)
            ->orderBy('sort', 'asc')
            ->orderBy('id', 'asc')
            ->findAll();
    }

    private function sanitizeConditionHtml(string $html): string
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,br,b,strong,i,em,u,ul,ol,li,span,div,small,sub,sup');
        $config->set('CSS.AllowedProperties', 'text-align,font-weight,font-style,text-decoration');
        $config->set('AutoFormat.AutoParagraph', true);
        $config->set('AutoFormat.RemoveEmpty', true);

        $purifier = new HTMLPurifier($config);
        return $purifier->purify($html);
    }

    private function buildAssetDataUri(string $relative_path): string
    {
        $path = FCPATH . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relative_path), DIRECTORY_SEPARATOR);
        if (!is_file($path)) {
            return '';
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = $extension === 'png' ? 'image/png' : 'image/jpeg';

        return 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($path));
    }

    private function formatCustomerName(array $offer): string
    {
        $company = trim((string) ($offer['company_name'] ?? ''));
        if ($company !== '') {
            return $company;
        }

        return trim(($offer['first_name'] ?? '') . ' ' . ($offer['last_name'] ?? ''));
    }
}
