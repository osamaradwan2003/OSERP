<?php
/**
 * Reusable receipt line items component
 *
 * @var array $items Array of line items
 *   Each item: [
 *     'name' => string, Item name
 *     'quantity' => int, Quantity
 *     'price' => float, Unit price
 *     'total' => float, Line total
 *     'discount' => float, Discount amount (optional)
 *     'description' => string, Description (optional)
 *     'serial_number' => string, Serial number (optional)
 *   ]
 * @var array $options Additional options:
 *   - show_serial: bool Whether to show serial numbers
 *   - show_description: bool Whether to show item descriptions
 *   - show_discount: bool Whether to show discounts
 *   - show_unit_price: bool Whether to show unit price
 *   - currency: string Currency symbol
 *   - tax_included: bool Whether tax is included in price
 */

$defaults = [
    'show_serial' => false,
    'show_description' => false,
    'show_discount' => true,
    'show_unit_price' => true,
    'currency' => '$',
    'tax_included' => false,
];
$options = array_merge($defaults, $options ?? []);
?>

<table class="receipt-items" width="100%" style="margin-top: 10px; border-collapse: collapse;">
    <thead>
        <tr style="border-bottom: 1px solid #000;">
            <th style="text-align: left; padding: 5px;"><?= lang('Sales.item') ?></th>
            <?php if ($options['show_serial']): ?>
            <th style="text-align: center; padding: 5px;"><?= lang('Sales.serial_number') ?></th>
            <?php endif; ?>
            <?php if ($options['show_unit_price']): ?>
            <th style="text-align: right; padding: 5px;"><?= lang('Sales.price') ?></th>
            <?php endif; ?>
            <th style="text-align: center; padding: 5px;"><?= lang('Sales.quantity') ?></th>
            <?php if ($options['show_discount']): ?>
            <th style="text-align: right; padding: 5px;"><?= lang('Sales.discount') ?></th>
            <?php endif; ?>
            <th style="text-align: right; padding: 5px;"><?= lang('Sales.total') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items ?? [] as $item): ?>
        <tr style="border-bottom: 1px dotted #ccc;">
            <td style="text-align: left; padding: 5px;">
                <?= esc($item['name']) ?>
                <?php if ($options['show_description'] && !empty($item['description'])): ?>
                <br><small><?= esc($item['description']) ?></small>
                <?php endif; ?>
            </td>
            <?php if ($options['show_serial']): ?>
            <td style="text-align: center; padding: 5px;">
                <?= esc($item['serial_number'] ?? '') ?>
            </td>
            <?php endif; ?>
            <?php if ($options['show_unit_price']): ?>
            <td style="text-align: right; padding: 5px;">
                <?= to_currency($item['price']) ?>
            </td>
            <?php endif; ?>
            <td style="text-align: center; padding: 5px;">
                <?= to_quantity_decimals($item['quantity']) ?>
            </td>
            <?php if ($options['show_discount']): ?>
            <td style="text-align: right; padding: 5px;">
                <?= !empty($item['discount']) ? to_currency($item['discount']) : '-' ?>
            </td>
            <?php endif; ?>
            <td style="text-align: right; padding: 5px;">
                <?= to_currency($item['total']) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
