<?php

use App\Models\Attribute;
use App\Models\Employee;
use App\Models\Item_taxes;
use App\Models\Tax_category;
use CodeIgniter\Database\ResultInterface;
use CodeIgniter\Session\Session;
use Config\OSPOS;
use Config\Services;

/**
 * Tabular views helper
 */

/**
 * Basic tabular headers function
 */
function transform_headers_readonly(array $headers): string
{
    $result = [];

    foreach ($headers as $key => $value) {
        $result[] = ['field' => $key, 'title' => $value, 'sortable' => $value != '', 'switchable' => !preg_match('(^$|&nbsp)', $value)];
    }

    return json_encode($result);
}

/**
 * Basic tabular headers function
 */
function transform_headers(array $headers, bool $readonly = false, bool $editable = true): string    // TODO: $array needs to be refactored to a new name.  Perhaps $headers?
{
    $result = [];

    if (!$readonly) {
        $headers = array_merge([['checkbox' => 'select', 'sortable' => false]], $headers);
    }

    if ($editable) {
        $headers[] = ['edit' => ''];
    }

    foreach ($headers as $element) {    // TODO: This might be clearer to refactor this to `foreach($headers as $header)`
        reset($element);
        $result[] = [
            'field'      => key($element),
            'title'      => current($element),
            'switchable' => $element['switchable'] ?? !preg_match('(^$|&nbsp)', current($element)),
            'escape'     => !preg_match("/(edit|email|messages|item_pic)/", key($element)) && !(isset($element['escape']) && !$element['escape']),
            'sortable'   => $element['sortable'] ?? current($element) != '',
            'checkbox'   => $element['checkbox'] ?? false,
            'class'      => isset($element['checkbox']) || preg_match('(^$|&nbsp)', current($element)) ? 'print_hide' : '',
            'sorter'     => $element['sorter'] ?? ''
        ];
    }

    return json_encode($result);
}


function sales_headers(): array
{
    return [
        ['sale_id'         => lang('Common.id')],
        ['sale_time'       => lang('Sales.sale_time')],
        ['customer_name'   => lang('Customers.customer')],
        ['amount_due'      => lang('Sales.amount_due')],
        ['amount_tendered' => lang('Sales.amount_tendered')],
        ['change_due'      => lang('Sales.change_due')],
        ['payment_type'    => lang('Sales.payment_type')]
    ];
}

/**
 * Get the header for the sales tabular view
 */
function get_sales_manage_table_headers(): string
{
    $headers = sales_headers();
    $config = config(OSPOS::class)->settings;

    if ($config['invoice_enable']) {
        $headers[] = ['invoice_number' => lang('Sales.invoice_number')];
        $headers[] = ['invoice' => '', 'sortable' => false, 'escape' => false];
    }

    $headers[] = ['receipt' => '', 'sortable' => false, 'escape' => false];

    return transform_headers($headers);
}

/**
 * Get the html data row for the sales
 */
function get_sale_data_row(object $sale): array
{
    $uri = current_url(true);
    $controller = $uri->getSegment(1);

    $row = [
        'sale_id'         => $sale->sale_id,
        'sale_time'       => to_datetime(strtotime($sale->sale_time)),
        'customer_name'   => $sale->customer_name,
        'amount_due'      => to_currency($sale->amount_due),
        'amount_tendered' => to_currency($sale->amount_tendered),
        'change_due'      => to_currency($sale->change_due),
        'payment_type'    => $sale->payment_type
    ];

    $config = config(OSPOS::class)->settings;

    if ($config['invoice_enable']) {
        $row['invoice_number'] = $sale->invoice_number;
        $row['invoice'] = empty($sale->invoice_number)
            ? '-'
            : "<div style='display: flex; gap: 10px;'>" . anchor(
                "$controller/invoice/$sale->sale_id",
                '<span class="glyphicon glyphicon-list-alt"></span>',
                ['title' => lang('Sales.show_invoice')]
            ) .
            anchor(
                "$controller/invoice/$sale->sale_id/1",
                '<span class="glyphicon glyphicon-th-list"></span>',
                ['title' => lang('Sales.show_invoice') . " customer " ]
            )
            . "</div>";
    }

    $row['receipt'] = '<div style="display: flex; gap: 10px;">' . anchor(
        "$controller/receipt/$sale->sale_id",
        '<span class="glyphicon glyphicon-usd"></span>',
        ['title' => lang('Sales.show_receipt')]
    ). anchor(
        "$controller/receipt/$sale->sale_id/1",
        '<span class="glyphicon glyphicon-user"></span>',
        ['title' => lang('Sales.show_receipt') . " customer " ]
    ) . '</div>';
    $row['edit'] = anchor(
        "$controller/edit/$sale->sale_id",
        '<span class="glyphicon glyphicon-edit"></span>',
        [
            'class'           => 'modal-dlg print_hide',
            'data-btn-delete' => lang('Common.delete'),
            'data-btn-submit' => lang('Common.submit'),
            'title'           => lang(ucfirst($controller) . ".update")
        ]
    );

    return $row;
}

/**
 * Get the html data last row for the sales
 */
function get_sale_data_last_row(ResultInterface $sales): array
{
    $sum_amount_due = 0;
    $sum_amount_tendered = 0;
    $sum_change_due = 0;

    foreach ($sales->getResult() as $key => $sale) {
        $sum_amount_due += $sale->amount_due;
        $sum_amount_tendered += $sale->amount_tendered;
        $sum_change_due += $sale->change_due;
    }

    return [
        'sale_id'         => '-',
        'sale_time'       => lang('Sales.total'),
        'amount_due'      => to_currency($sum_amount_due),
        'amount_tendered' => to_currency($sum_amount_tendered),
        'change_due'      => to_currency($sum_change_due)
    ];
}

/**
 * Get the sales payments summary
 */
function get_sales_manage_payments_summary(array $payments): string
{
    $table = '<div id="report_summary">';
    $total = 0;

    foreach ($payments as $key => $payment) {
        $amount = $payment['payment_amount'];
        $total = bcadd($total, $amount);
        $table .= '<div class="summary_row">' . $payment['payment_type'] . ': ' . to_currency($amount) . '</div>';
    }

    $table .= '<div class="summary_row">' . lang('Sales.total') . ': ' . to_currency($total) . '</div>';
    $table .= '</div>';

    return $table;
}

function person_headers(): array
{
    return [
        ['people.person_id' => lang('Common.id')],
        ['last_name'        => lang('Common.last_name')],
        ['first_name'       => lang('Common.first_name')],
        ['email'            => lang('Common.email')],
        ['phone_number'     => lang('Common.phone_number')]
    ];
}


/**
 * Get the header for the people tabular view
 */
function get_people_manage_table_headers(): string
{
    $headers = person_headers();

    $employee = model(Employee::class);
    $session = session();

    if ($employee->has_grant('messages', $session->get('person_id'))) {
        $headers[] = ['messages' => '', 'sortable' => false];
    }

    return transform_headers($headers);
}

/**
 * Get the html data row for the person
 */
function get_person_data_row(object $person): array
{
    $controller = get_controller();

    return [
        'people.person_id' => $person->person_id,
        'last_name'        => $person->last_name,
        'first_name'       => $person->first_name,
        'email'            => empty($person->email) ? '' : mailto($person->email, $person->email),
        'phone_number'     => $person->phone_number,
        'messages'         => empty($person->phone_number)
            ? ''
            : anchor(
                "Messages/view/$person->person_id",
                '<span class="glyphicon glyphicon-phone"></span>',
                [
                    'class'           => 'modal-dlg',
                    'data-btn-submit' => lang('Common.submit'),
                    'title'           => lang('Messages.sms_send')
                ]
            ),
        'edit'             => anchor(
            "$controller/view/$person->person_id",
            '<span class="glyphicon glyphicon-edit"></span>',
            [
                'class'           => 'modal-dlg',
                'data-btn-submit' => lang('Common.submit'),
                'title'           => lang(ucfirst($controller) . '.update')    // TODO: String interpolation
            ]
        )
    ];
}


function customer_headers(): array
{
    return [
        ['people.person_id' => lang('Common.id')],
        ['last_name'        => lang('Common.last_name')],
        ['first_name'       => lang('Common.first_name')],
        ['email'            => lang('Common.email')],
        ['phone_number'     => lang('Common.phone_number')],
        ['total'            => lang('Common.total_spent'), 'sortable' => false]
    ];
}

/**
 * Get the header for the customer tabular view
 */
function get_customer_manage_table_headers(): string
{
    $headers = customer_headers();

    $employee = model(Employee::class);
    $session = session();

    if ($employee->has_grant('messages', $session->get('person_id'))) {
        $headers[] = ['messages' => '', 'sortable' => false];
    }

    return transform_headers($headers);
}

/**
 * Get the html data row for the customer
 */
function get_customer_data_row(object $person, object $stats): array
{
    $controller = get_controller();

    return [
        'people.person_id' => $person->person_id,
        'last_name'        => $person->last_name,
        'first_name'       => $person->first_name,
        'email'            => empty($person->email) ? '' : mailto($person->email, $person->email),
        'phone_number'     => $person->phone_number,
        'total'            => to_currency($stats->total),
        'messages'         => empty($person->phone_number)
            ? ''
            : anchor(
                "Messages/view/$person->person_id",
                '<span class="glyphicon glyphicon-phone"></span>',
                [
                    'class'           => 'modal-dlg',
                    'data-btn-submit' => lang('Common.submit'),
                    'title'           => lang('Messages.sms_send')
                ]
            ),
        'edit'             => anchor(
            "$controller/view/$person->person_id",
            '<span class="glyphicon glyphicon-edit"></span>',
            [
                'class'           => 'modal-dlg',
                'data-btn-submit' => lang('Common.submit'),
                'title'           => lang(ucfirst($controller) . ".update")
            ]
        )
    ];
}

function supplier_headers(): array
{
    return [
        ['people.person_id' => lang('Common.id')],
        ['company_name'     => lang('Suppliers.company_name')],
        ['agency_name'      => lang('Suppliers.agency_name')],
        ['category'         => lang('Suppliers.category')],
        ['last_name'        => lang('Common.last_name')],
        ['first_name'       => lang('Common.first_name')],
        ['email'            => lang('Common.email')],
        ['phone_number'     => lang('Common.phone_number')]
    ];
}

/**
 * Get the header for the suppliers tabular view
 */
function get_suppliers_manage_table_headers(): string
{
    $headers = supplier_headers();

    $employee = model(Employee::class);
    $session = session();

    if ($employee->has_grant('messages', $session->get('person_id'))) {
        $headers[] = ['messages' => ''];
    }

    return transform_headers($headers);
}

/**
 * Get the html data row for the supplier
 */
function get_supplier_data_row(object $supplier): array
{
    $controller = get_controller();

    return [
        'people.person_id' => $supplier->person_id,
        'company_name'     => html_entity_decode($supplier->company_name),
        'agency_name'      => $supplier->agency_name,
        'category'         => $supplier->category,
        'last_name'        => $supplier->last_name,
        'first_name'       => $supplier->first_name,
        'email'            => empty($supplier->email) ? '' : mailto($supplier->email, $supplier->email),
        'phone_number'     => $supplier->phone_number,
        'messages'         => empty($supplier->phone_number)
            ? ''
            : anchor(
                "Messages/view/$supplier->person_id",
                '<span class="glyphicon glyphicon-phone"></span>',
                [
                    'class'           => "modal-dlg",
                    'data-btn-submit' => lang('Common.submit'),
                    'title'           => lang('Messages.sms_send')
                ]
            ),
        'edit'             => anchor(
            "$controller/view/$supplier->person_id",
            '<span class="glyphicon glyphicon-edit"></span>',
            [
                'class'           => "modal-dlg",
                'data-btn-submit' => lang('Common.submit'),
                'title'           => lang(ucfirst($controller) . ".update")
            ]
        )
    ];
}

function item_headers(bool $show_all_stores_columns = true): array
{
    $headers = [
        ['items.item_id' => lang('Common.id')],
        ['item_number'   => lang('Items.item_number')],
        ['name'          => lang('Items.name')],
        ['category'      => lang('Items.category')],
        ['company_name'  => lang('Suppliers.company_name')],
        ['cost_price'    => lang('Items.cost_price')],
        ['unit_price'    => lang('Items.unit_price')],
        ['quantity'      => lang('Items.quantity')]
    ];

    if ($show_all_stores_columns) {
        $headers[] = ['stock_name' => 'Stock Name', 'sortable' => false, 'escape' => false];
        $headers[] = ['total_quantity' => 'Total Quantity', 'sortable' => false];
    }

    return $headers;
}

/**
 * Get the header for the items tabular view
 */
function get_items_manage_table_headers(bool $show_all_stores_columns = true): string
{
    $attribute = model(Attribute::class);
    $config = config(OSPOS::class)->settings;
    $definition_names = $attribute->get_definitions_by_flags($attribute::SHOW_IN_ITEMS);    // TODO: this should be made into a constant in constants.php

    $headers = item_headers($show_all_stores_columns);

    if ($config['use_destination_based_tax']) {
        $headers[] = ['tax_percents' => lang('Items.tax_category'), 'sortable' => false];
    } else {
        $headers[] = ['tax_percents' => lang('Items.tax_percents'), 'sortable' => false];
    }

    $headers[] = ['item_pic' => lang('Items.image'), 'sortable' => false];

    foreach ($definition_names as $definition_id => $definition_name) {
        $headers[] = [$definition_id => $definition_name, 'sortable' => false];
    }

    $headers[] = ['inventory' => '', 'escape' => false];
    $headers[] = ['stock' => '', 'escape' => false];

    return transform_headers($headers);
}

/**
 * Get the html data row for the item
 */
function get_item_data_row(object $item, bool $show_all_stores_columns = true): array
{
    $attribute = model(Attribute::class);
    $item_taxes = model(Item_taxes::class);
    $tax_category = model(Tax_category::class);
    $item_quantity = model(\App\Models\Item_quantity::class);
    $stock_location = model(\App\Models\Stock_location::class);
    $config = config(OSPOS::class)->settings;

    if ($config['use_destination_based_tax']) {
        if ($item->tax_category_id == null) {    // TODO: === ?
            $tax_percents = '-';
        } else {
            $tax_category_info = $tax_category->get_info($item->tax_category_id);
            $tax_percents = $tax_category_info->tax_category;
        }
    } else {
        $item_tax_info = $item_taxes->get_info($item->item_id);
        $tax_percents = '';
        foreach ($item_tax_info as $tax_info) {
            $tax_percents .= to_tax_decimals($tax_info['percent']) . '%, ';
        }

        // Remove ', ' from last item
        $tax_percents = substr($tax_percents, 0, -2);
        $tax_percents = !$tax_percents ? '-' : $tax_percents;
    }

    $controller = get_controller();

    $image = null;
    if (!empty($item->pic_filename)) {
        $ext = pathinfo($item->pic_filename, PATHINFO_EXTENSION);

        $images = $ext == ''
            ? glob("./uploads/item_pics/$item->pic_filename.*")
            : glob("./uploads/item_pics/$item->pic_filename");

        if (sizeof($images) > 0) {
            $image_path = ltrim($images[0], './');
            $image .= '<a class="rollover" href="' . base_url(implode('/', array_map('rawurlencode', explode('/', $image_path)))) . '"><img alt="Image thumbnail" src="' . site_url('items/PicThumb/' . rawurlencode(pathinfo($images[0], PATHINFO_BASENAME))) . '"></a>';
        }
    }

    if ($config['multi_pack_enabled']) {
        $item->name .= NAME_SEPARATOR . $item->pack_name;
    }

    $definition_names = $attribute->get_definitions_by_flags($attribute::SHOW_IN_ITEMS);
    $stock_lines = [];
    $total_quantity = 0.0;
    if ($show_all_stores_columns) {
        $allowed_locations = $stock_location->get_allowed_locations();

        foreach ($allowed_locations as $location_id => $location_name) {
            $location_quantity = (float) $item_quantity->get_item_quantity((int) $item->item_id, (int) $location_id)->quantity;
            $total_quantity += $location_quantity;
            $stock_lines[] = esc($location_name) . ': ' . to_quantity_decimals($location_quantity);
        }
    } elseif (isset($item->quantity)) {
        $total_quantity = (float) $item->quantity;
    }

    $row_quantity = $show_all_stores_columns
        ? to_quantity_decimals($total_quantity)
        : to_quantity_decimals(isset($item->quantity) ? (float) $item->quantity : $total_quantity);

    $columns = [
        'items.item_id' => $item->item_id,
        'item_number'   => $item->item_number,
        'name'          => $item->name,
        'category'      => $item->category,
        'company_name'  => $item->company_name,    // TODO: This isn't in the items table. Should this be here?
        'cost_price'    => to_currency($item->cost_price),
        'unit_price'    => to_currency($item->unit_price),
        'quantity'      => $row_quantity,
        'tax_percents'  => !$tax_percents ? '-' : $tax_percents,
        'item_pic'      => $image
    ];

    if ($show_all_stores_columns) {
        $columns['stock_name'] = implode('<br>', $stock_lines);
        $columns['total_quantity'] = to_quantity_decimals($total_quantity);
    }

    $icons = [
        'inventory' => anchor(
            "$controller/inventory/$item->item_id",
            '<span class="glyphicon glyphicon-pushpin"></span>',
            [
                'class'           => 'modal-dlg',
                'data-btn-submit' => lang('Common.submit'),
                'title'           => lang(ucfirst($controller) . ".count")
            ]
        ),
        'stock'     => anchor(
            "$controller/countDetails/$item->item_id",
            '<span class="glyphicon glyphicon-list-alt"></span>',
            [
                'class' => 'modal-dlg',
                'title' => lang(ucfirst($controller) . ".details_count")
            ]
        ),
        'edit'      => anchor(
            "$controller/view/$item->item_id",
            '<span class="glyphicon glyphicon-edit"></span>',
            [
                'class'           => 'modal-dlg',
                'data-btn-submit' => lang('Common.submit'),
                'title'           => lang(ucfirst($controller) . ".update")
            ]
        )
    ];

    return $columns + expand_attribute_values($definition_names, (array) $item) + $icons;
}

function giftcard_headers(): array
{
    return [
        ['giftcard_id'     => lang('Common.id')],
        ['last_name'       => lang('Common.last_name')],
        ['first_name'      => lang('Common.first_name')],
        ['giftcard_number' => lang('Giftcards.giftcard_number')],
        ['value'           => lang('Giftcards.card_value')]
    ];
}

/**
 * Get the header for the giftcard tabular view
 */
function get_giftcards_manage_table_headers(): string
{
    return transform_headers(giftcard_headers());
}

/**
 * Get the html data row for the giftcard
 */
function get_giftcard_data_row(object $giftcard): array
{
    $controller = get_controller();

    return [
        'giftcard_id'     => $giftcard->giftcard_id,
        'last_name'       => $giftcard->last_name,
        'first_name'      => $giftcard->first_name,
        'giftcard_number' => $giftcard->giftcard_number,
        'value'           => to_currency($giftcard->value),
        'edit'            => anchor(
            "$controller/view/$giftcard->giftcard_id",
            '<span class="glyphicon glyphicon-edit"></span>',
            [
                'class'           => 'modal-dlg',
                'data-btn-submit' => lang('Common.submit'),
                'title'           => lang(ucfirst($controller) . ".update")
            ]
        )
    ];
}

function item_kit_headers(): array
{
    return [
        ['item_kit_id'      => lang('Item_kits.kit')],
        ['item_kit_number'  => lang('Item_kits.item_kit_number')],
        ['name'             => lang('Item_kits.name')],
        ['description'      => lang('Item_kits.description')],
        ['total_cost_price' => lang('Items.cost_price'), 'sortable' => FALSE],
        ['total_unit_price' => lang('Items.unit_price'), 'sortable' => FALSE]
    ];
}

/**
 * Get the header for the item kits tabular view
 */
function get_item_kits_manage_table_headers(): string
{
    return transform_headers(item_kit_headers());
}

/**
 * Get the html data row for the item kit
 */
function get_item_kit_data_row(object $item_kit): array
{
    $controller = get_controller();

    return [
        'item_kit_id'      => $item_kit->item_kit_id,
        'item_kit_number'  => $item_kit->item_kit_number,
        'name'             => $item_kit->name,
        'description'      => $item_kit->description,
        'total_cost_price' => to_currency($item_kit->total_cost_price),
        'total_unit_price' => to_currency($item_kit->total_unit_price),
        'edit'             => anchor(
            "$controller/view/$item_kit->item_kit_id",
            '<span class="glyphicon glyphicon-edit"></span>',
            [
                'class'           => 'modal-dlg',
                'data-btn-submit' => lang('Common.submit'),
                'title'           => lang(ucfirst($controller) . ".update")
            ]
        )
    ];
}

/**
 * @param array $columns
 * @param array $row
 * @return array
 */
function parse_attribute_values(array $columns, array $row): array
{
    $attribute_values = [];
    foreach ($columns as $column) {
        if (array_key_exists($column, $row)) {
            $attribute_value = explode('|', $row[$column]);
            $attribute_values = array_merge($attribute_values, $attribute_value);
        }
    }
    return $attribute_values;
}

/**
 * @param array $definition_names
 * @param array $row
 * @return array
 */
function expand_attribute_values(array $definition_names, array $row): array
{
    $values = parse_attribute_values(['attribute_values', 'attribute_dtvalues', 'attribute_dvalues'], $row);

    $indexed_values = [];
    foreach ($values as $attribute_value) {
        $exploded_value = explode('_', $attribute_value);
        if (sizeof($exploded_value) > 1) {
            $indexed_values[$exploded_value[0]] = $exploded_value[1];
        }
    }

    $attribute_values = [];
    foreach ($definition_names as $definition_id => $definition_name) {
        if (isset($indexed_values[$definition_id])) {
            $attribute_value = $indexed_values[$definition_id];
            $attribute_values["$definition_id"] = $attribute_value;
        } else {
            $attribute_values["$definition_id"] = "";
        }
    }

    return $attribute_values;
}

function attribute_definition_headers(): array
{
    return [
        ['definition_id'    => lang('Attributes.definition_id')],
        ['definition_name'  => lang('Attributes.definition_name')],
        ['definition_type'  => lang('Attributes.definition_type')],
        ['definition_flags' => lang('Attributes.definition_flags')],
        ['definition_group' => lang('Attributes.definition_group')],
    ];
}

/**
 * @return string
 */
function get_attribute_definition_manage_table_headers(): string
{
    return transform_headers(attribute_definition_headers());
}

/**
 * @param object $attribute_row
 * @return array
 */
function get_attribute_definition_data_row(object $attribute_row): array
{

    $attribute = model(Attribute::class);
    $controller = get_controller();

    if (count($attribute->get_definition_flags()) == 0) {
        $definition_flags = lang('Common.none_selected_text');
    } elseif ($attribute->definition_type == GROUP) {
        $definition_flags = "-";
    } else {
        $definition_flags = implode(', ', $attribute_row->definition_flags);
    }

    return [
        'definition_id'    => $attribute_row->definition_id,
        'definition_name'  => $attribute_row->definition_name,
        'definition_type'  => $attribute_row->definition_type,
        'definition_group' => $attribute_row->definition_group,
        'definition_flags' => $definition_flags,
        'edit'             => anchor(
            "$controller/view/$attribute_row->definition_id",
            '<span class="glyphicon glyphicon-edit"></span>',
            [
                'class'           => 'modal-dlg',
                'data-btn-submit' => lang('Common.submit'),
                'title'           => lang(ucfirst($controller) . ".update")
            ]
        )
    ];
}



/**
 * Returns the right-most part of the controller name
 * @return string
 */
function get_controller(): string
{
    $router = service('router');
    $controller_name = strtolower($router->controllerName());
    $controller_name_parts = explode('\\', $controller_name);
    return end($controller_name_parts);
}

/**
 * Restores filter values from URL query string.
 *
 * @param CodeIgniter\HTTP\IncomingRequest $request The request object
 * @return array Array with 'start_date', 'end_date', and 'selected_filters' keys
 */
function restoreTableFilters($request): array
{
    $startDate = $request->getGet('start_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $endDate = $request->getGet('end_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $urlFilters = $request->getGet('filters', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    return array_filter([
        'start_date' => $startDate ?: null,
        'end_date' => $endDate ?: null,
        'selected_filters' => $urlFilters ?? []
    ], function($value) {
        return $value !== null && $value !== [];
    });
}
