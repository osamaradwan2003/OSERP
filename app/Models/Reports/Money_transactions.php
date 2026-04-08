<?php

namespace App\Models\Reports;

use App\Models\Receiving;
use App\Models\Sale;
use Config\OSPOS;

class Money_transactions extends Report
{
    public function getDataColumns(): array
    {
        return [
            'sales' => [
                ['id' => lang('Common.id')],
                ['date' => lang('Reports.date')],
                ['type' => lang('Reports.type')],
                ['total' => lang('Reports.total'), 'sorter' => 'number_sorter'],
                ['invoice' => '', 'sortable' => false, 'escape' => false],
                ['receipt' => '', 'sortable' => false, 'escape' => false]
            ],
            'payments' => [
                ['date' => lang('Reports.date')],
                ['source' => lang('Reports.source')],
                ['method' => lang('Reports.payment_type')],
                ['amount' => lang('Reports.total'), 'sorter' => 'number_sorter'],
                ['description' => lang('Common.description')]
            ]
        ];
    }

    public function getData(array $inputs): array
    {
        if (($inputs['entity_type'] ?? '') === 'supplier') {
            return [
                'sales' => $this->getSupplierReceivings($inputs),
                'payments' => $this->getSupplierPayments($inputs),
                'details' => $this->getSupplierReceivingDetails($inputs)
            ];
        }

        return [
            'sales' => $this->getCustomerSales($inputs),
            'payments' => $this->getCustomerPayments($inputs),
            'details' => $this->getCustomerSaleDetails($inputs)
        ];
    }

    public function getSummaryData(array $inputs): array
    {
        if (($inputs['entity_type'] ?? '') === 'supplier') {
            $totalSales = $this->getSupplierReceivingsTotal($inputs);
            $totalPayments = $this->getSupplierPaymentsTotal($inputs);
        } else {
            $totalSales = $this->getCustomerSalesTotal($inputs);
            $totalPayments = $this->getCustomerPaymentsTotal($inputs);
        }

        return [
            'total_sales' => $totalSales,
            'total_payments' => $totalPayments,
            'net_balance' => $totalSales - $totalPayments
        ];
    }

    private function getCustomerSales(array $inputs): array
    {
        helper('url');
        $sale = model(Sale::class);
        $sale->create_temp_table($inputs);

        $builder = $this->db->table('sales_items_temp');
        $builder->select('sale_id, MAX(sale_time) AS sale_time, MAX(sale_type) AS sale_type, MAX(invoice_number) AS invoice_number, SUM(total) AS total');
        $builder->where('customer_id', (int) $inputs['entity_id']);
        $builder->where('sale_status', COMPLETED);
        $builder->whereIn('sale_type', [SALE_TYPE_POS, SALE_TYPE_INVOICE, SALE_TYPE_RETURN]);
        $builder->groupBy('sale_id');
        $builder->orderBy('MAX(sale_time)', 'DESC');

        $rows = $builder->get()->getResultArray();
        $data = [];

        foreach ($rows as $row) {
            $saleId = (int) $row['sale_id'];
            $invoiceLink = '-';
            if (!empty($row['invoice_number'])) {
                $invoiceLink = anchor(
                    "sales/invoice/$saleId",
                    '<span class="glyphicon glyphicon-list-alt"></span>',
                    ['title' => lang('Sales.show_invoice')]
                );
            }
            $receiptLink = anchor(
                "sales/receipt/$saleId",
                '<span class="glyphicon glyphicon-usd"></span>',
                ['title' => lang('Sales.show_receipt')]
            );
            $data[] = [
                'id' => $saleId,
                'date' => to_datetime(strtotime($row['sale_time'])),
                'type' => $this->mapSaleType((int) $row['sale_type']),
                'total' => to_currency((float) $row['total']),
                'invoice' => $invoiceLink,
                'receipt' => $receiptLink
            ];
        }

        return $data;
    }

    private function getSupplierReceivings(array $inputs): array
    {
        helper('url');
        $receiving = model(Receiving::class);
        $receiving->create_temp_table($inputs);

        $builder = $this->db->table('receivings_items_temp');
        $builder->select('receiving_id, MAX(receiving_time) AS receiving_time, SUM(total) AS total');
        $builder->where('supplier_id', (int) $inputs['entity_id']);
        $builder->groupBy('receiving_id');
        $builder->orderBy('MAX(receiving_time)', 'DESC');

        $rows = $builder->get()->getResultArray();
        $data = [];

        foreach ($rows as $row) {
            $receivingId = (int) $row['receiving_id'];
            $receiptLink = anchor(
                "receivings/receipt/$receivingId",
                '<span class="glyphicon glyphicon-usd"></span>',
                ['title' => lang('Receivings.receipt')]
            );
            $data[] = [
                'id' => $receivingId,
                'date' => to_datetime(strtotime($row['receiving_time'])),
                'type' => lang('Reports.receivings'),
                'total' => to_currency((float) $row['total']),
                'invoice' => '-',
                'receipt' => $receiptLink
            ];
        }

        return $data;
    }

    private function getCustomerPayments(array $inputs): array
    {
        $payments = [];

        $salesBuilder = $this->db->table('sales_payments AS payments');
        $salesBuilder->select('sales.sale_id, sales.sale_time, payments.payment_type, payments.payment_amount, payments.cash_refund');
        $salesBuilder->join('sales AS sales', 'sales.sale_id = payments.sale_id');
        $salesBuilder->where('sales.customer_id', (int) $inputs['entity_id']);
        $this->applyDateRange($salesBuilder, 'sales.sale_time', $inputs['start_date'], $inputs['end_date']);

        foreach ($salesBuilder->get()->getResultArray() as $row) {
            $amount = (float) $row['payment_amount'] - (float) $row['cash_refund'];
            if (abs($amount) < 0.00001) {
                continue;
            }
            $payments[] = [
                'sort_date' => $row['sale_time'],
                'date' => to_datetime(strtotime($row['sale_time'])),
                'source' => lang('Reports.trans_sales'),
                'method' => $row['payment_type'] ?? '',
                'amount' => to_currency($amount),
                'description' => 'Sale #' . $row['sale_id']
            ];
        }

        $cashflowBuilder = $this->db->table('cashflow_entries AS entries');
        $cashflowBuilder->select('entry_date, entry_type, amount, description');
        $cashflowBuilder->where('customer_id', (int) $inputs['entity_id']);
        $cashflowBuilder->where('deleted', 0);
        $cashflowBuilder->where('status', 'posted');
        $cashflowBuilder->where('sale_payment_id IS NULL', null, false);
        $cashflowBuilder->whereIn('entry_type', ['income', 'outcome']);
        $this->applyDateRange($cashflowBuilder, 'entry_date', $inputs['start_date'], $inputs['end_date']);

        foreach ($cashflowBuilder->get()->getResultArray() as $row) {
            $signedAmount = ($row['entry_type'] ?? '') === 'outcome'
                ? -1 * (float) $row['amount']
                : (float) $row['amount'];
            if (abs($signedAmount) < 0.00001) {
                continue;
            }
            $payments[] = [
                'sort_date' => $row['entry_date'],
                'date' => to_datetime(strtotime($row['entry_date'])),
                'source' => lang('Module.cashflow'),
                'method' => lang('Cashflow.' . ($row['entry_type'] ?? 'income')),
                'amount' => to_currency($signedAmount),
                'description' => $row['description'] ?? ''
            ];
        }

        usort($payments, function($a, $b) {
            return strcmp($b['sort_date'], $a['sort_date']);
        });

        return array_map(function($row) {
            unset($row['sort_date']);
            return $row;
        }, $payments);
    }

    private function getSupplierPayments(array $inputs): array
    {
        $payments = [];

        $cashflowBuilder = $this->db->table('cashflow_entries AS entries');
        $cashflowBuilder->select('entry_date, entry_type, amount, description');
        $cashflowBuilder->where('supplier_id', (int) $inputs['entity_id']);
        $cashflowBuilder->where('deleted', 0);
        $cashflowBuilder->where('status', 'posted');
        $cashflowBuilder->whereIn('entry_type', ['income', 'outcome']);
        $this->applyDateRange($cashflowBuilder, 'entry_date', $inputs['start_date'], $inputs['end_date']);

        foreach ($cashflowBuilder->get()->getResultArray() as $row) {
            $signedAmount = ($row['entry_type'] ?? '') === 'income'
                ? -1 * (float) $row['amount']
                : (float) $row['amount'];
            if (abs($signedAmount) < 0.00001) {
                continue;
            }
            $payments[] = [
                'sort_date' => $row['entry_date'],
                'date' => to_datetime(strtotime($row['entry_date'])),
                'source' => lang('Module.cashflow'),
                'method' => lang('Cashflow.' . ($row['entry_type'] ?? 'outcome')),
                'amount' => to_currency($signedAmount),
                'description' => $row['description'] ?? ''
            ];
        }

        usort($payments, function($a, $b) {
            return strcmp($b['sort_date'], $a['sort_date']);
        });

        return array_map(function($row) {
            unset($row['sort_date']);
            return $row;
        }, $payments);
    }

    private function getCustomerSaleDetails(array $inputs): array
    {
        $sale = model(Sale::class);
        $sale->create_temp_table($inputs);

        $builder = $this->db->table('sales_items_temp');
        $builder->select('sale_id, name, quantity_purchased, total');
        $builder->where('customer_id', (int) $inputs['entity_id']);
        $builder->where('sale_status', COMPLETED);
        $builder->whereIn('sale_type', [SALE_TYPE_POS, SALE_TYPE_INVOICE, SALE_TYPE_RETURN]);
        $builder->orderBy('sale_id', 'DESC');

        $details = [];
        foreach ($builder->get()->getResultArray() as $row) {
            $saleId = (int) $row['sale_id'];
            if (!isset($details[$saleId])) {
                $details[$saleId] = [];
            }
            $details[$saleId][] = [
                'name' => $row['name'] ?? '',
                'quantity' => to_quantity_decimals($row['quantity_purchased'] ?? 0),
                'total' => to_currency((float) ($row['total'] ?? 0))
            ];
        }

        return $details;
    }

    private function getSupplierReceivingDetails(array $inputs): array
    {
        $receiving = model(Receiving::class);
        $receiving->create_temp_table($inputs);

        $builder = $this->db->table('receivings_items_temp AS receivings_items_temp');
        $builder->select('receivings_items_temp.receiving_id, items.name, receivings_items_temp.quantity_purchased, receivings_items_temp.total');
        $builder->join('items', 'receivings_items_temp.item_id = items.item_id');
        $builder->where('receivings_items_temp.supplier_id', (int) $inputs['entity_id']);
        $builder->orderBy('receivings_items_temp.receiving_id', 'DESC');

        $details = [];
        foreach ($builder->get()->getResultArray() as $row) {
            $receivingId = (int) $row['receiving_id'];
            if (!isset($details[$receivingId])) {
                $details[$receivingId] = [];
            }
            $details[$receivingId][] = [
                'name' => $row['name'] ?? '',
                'quantity' => to_quantity_decimals($row['quantity_purchased'] ?? 0),
                'total' => to_currency((float) ($row['total'] ?? 0))
            ];
        }

        return $details;
    }

    private function getCustomerSalesTotal(array $inputs): float
    {
        $sale = model(Sale::class);
        $sale->create_temp_table($inputs);

        $builder = $this->db->table('sales_items_temp');
        $builder->select('SUM(total) AS total');
        $builder->where('customer_id', (int) $inputs['entity_id']);
        $builder->where('sale_status', COMPLETED);
        $builder->whereIn('sale_type', [SALE_TYPE_POS, SALE_TYPE_INVOICE, SALE_TYPE_RETURN]);

        return (float) ($builder->get()->getRow()->total ?? 0);
    }

    private function getSupplierReceivingsTotal(array $inputs): float
    {
        $receiving = model(Receiving::class);
        $receiving->create_temp_table($inputs);

        $builder = $this->db->table('receivings_items_temp');
        $builder->select('SUM(total) AS total');
        $builder->where('supplier_id', (int) $inputs['entity_id']);

        return (float) ($builder->get()->getRow()->total ?? 0);
    }

    private function getCustomerPaymentsTotal(array $inputs): float
    {
        $total = 0.0;

        $salesBuilder = $this->db->table('sales_payments AS payments');
        $salesBuilder->select('payments.payment_amount, payments.cash_refund');
        $salesBuilder->join('sales AS sales', 'sales.sale_id = payments.sale_id');
        $salesBuilder->where('sales.customer_id', (int) $inputs['entity_id']);
        $this->applyDateRange($salesBuilder, 'sales.sale_time', $inputs['start_date'], $inputs['end_date']);

        foreach ($salesBuilder->get()->getResultArray() as $row) {
            $total += (float) $row['payment_amount'] - (float) $row['cash_refund'];
        }

        $cashflowBuilder = $this->db->table('cashflow_entries AS entries');
        $cashflowBuilder->select('entry_type, amount');
        $cashflowBuilder->where('customer_id', (int) $inputs['entity_id']);
        $cashflowBuilder->where('deleted', 0);
        $cashflowBuilder->where('status', 'posted');
        $cashflowBuilder->where('sale_payment_id IS NULL', null, false);
        $cashflowBuilder->whereIn('entry_type', ['income', 'outcome']);
        $this->applyDateRange($cashflowBuilder, 'entry_date', $inputs['start_date'], $inputs['end_date']);

        foreach ($cashflowBuilder->get()->getResultArray() as $row) {
            $signedAmount = ($row['entry_type'] ?? '') === 'outcome'
                ? -1 * (float) $row['amount']
                : (float) $row['amount'];
            $total += $signedAmount;
        }

        return $total;
    }

    private function getSupplierPaymentsTotal(array $inputs): float
    {
        $total = 0.0;

        $cashflowBuilder = $this->db->table('cashflow_entries AS entries');
        $cashflowBuilder->select('entry_type, amount');
        $cashflowBuilder->where('supplier_id', (int) $inputs['entity_id']);
        $cashflowBuilder->where('deleted', 0);
        $cashflowBuilder->where('status', 'posted');
        $cashflowBuilder->whereIn('entry_type', ['income', 'outcome']);
        $this->applyDateRange($cashflowBuilder, 'entry_date', $inputs['start_date'], $inputs['end_date']);

        foreach ($cashflowBuilder->get()->getResultArray() as $row) {
            $signedAmount = ($row['entry_type'] ?? '') === 'income'
                ? -1 * (float) $row['amount']
                : (float) $row['amount'];
            $total += $signedAmount;
        }

        return $total;
    }

    private function applyDateRange($builder, string $field, string $startDate, string $endDate): void
    {
        $config = config(OSPOS::class)->settings;

        if (empty($config['date_or_time_format'])) {
            $builder->where("DATE($field) >=", $startDate);
            $builder->where("DATE($field) <=", $endDate);
        } else {
            $builder->where($field . ' >=', rawurldecode($startDate));
            $builder->where($field . ' <=', rawurldecode($endDate));
        }
    }

    private function mapSaleType(int $saleType): string
    {
        return match ($saleType) {
            SALE_TYPE_POS => lang('Reports.code_pos'),
            SALE_TYPE_INVOICE => lang('Reports.code_invoice'),
            SALE_TYPE_RETURN => lang('Reports.code_return'),
            default => ''
        };
    }
}


