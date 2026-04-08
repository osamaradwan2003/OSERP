<?php
/**
 * Reusable receipt totals section component
 *
 * @var array $totals Array of totals
 *   [
 *     'subtotal' => float, Subtotal
 *     'tax' => float, Tax amount
 *     'tax_name' => string, Tax name (optional)
 *     'discount' => float, Total discount
 *     'total' => float, Grand total
 *     'payments' => array, Payment breakdown (optional)
 *     'amount_due' => float, Amount due (optional)
 *     'change_due' => float, Change due (optional)
 *   ]
 * @var array $options Additional options:
 *   - show_subtotal: bool Whether to show subtotal
 *   - show_tax: bool Whether to show tax
 *   - show_discount: bool Whether to show discount
 *   - show_payments: bool Whether to show payment breakdown
 *   - show_amount_due: bool Whether to show amount due
 *   - show_change_due: bool Whether to show change due
 */

$defaults = [
    'show_subtotal' => true,
    'show_tax' => true,
    'show_discount' => true,
    'show_payments' => true,
    'show_amount_due' => true,
    'show_change_due' => true,
];
$options = array_merge($defaults, $options ?? []);
?>

<table class="receipt-totals" width="100%" style="margin-top: 10px; border-collapse: collapse;">
    <?php if ($options['show_subtotal'] && isset($totals['subtotal'])): ?>
    <tr>
        <td style="text-align: right; padding: 5px;"><?= lang('Sales.sub_total') ?></td>
        <td style="text-align: right; padding: 5px; width: 100px;"><?= to_currency($totals['subtotal']) ?></td>
    </tr>
    <?php endif; ?>

    <?php if ($options['show_discount'] && !empty($totals['discount'])): ?>
    <tr>
        <td style="text-align: right; padding: 5px;"><?= lang('Sales.discount') ?></td>
        <td style="text-align: right; padding: 5px; width: 100px;"><?= to_currency($totals['discount']) ?></td>
    </tr>
    <?php endif; ?>

    <?php if ($options['show_tax'] && !empty($totals['tax'])): ?>
    <tr>
        <td style="text-align: right; padding: 5px;">
            <?= !empty($totals['tax_name']) ? esc($totals['tax_name']) : lang('Sales.tax') ?>
        </td>
        <td style="text-align: right; padding: 5px; width: 100px;"><?= to_currency($totals['tax']) ?></td>
    </tr>
    <?php endif; ?>

    <tr style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
        <td style="text-align: right; padding: 5px; font-weight: bold; font-size: 1.1em;">
            <?= lang('Sales.total') ?>
        </td>
        <td style="text-align: right; padding: 5px; width: 100px; font-weight: bold; font-size: 1.1em;">
            <?= to_currency($totals['total'] ?? 0) ?>
        </td>
    </tr>

    <?php if ($options['show_payments'] && !empty($totals['payments'])): ?>
    <?php foreach ($totals['payments'] as $payment): ?>
    <tr>
        <td style="text-align: right; padding: 5px;">
            <?= esc($payment['type']) ?>
            <?php if (!empty($payment['description'])): ?>
            <small>(<?= esc($payment['description']) ?>)</small>
            <?php endif; ?>
        </td>
        <td style="text-align: right; padding: 5px; width: 100px;"><?= to_currency($payment['amount']) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($options['show_amount_due'] && isset($totals['amount_due']) && $totals['amount_due'] > 0): ?>
    <tr style="font-weight: bold;">
        <td style="text-align: right; padding: 5px;"><?= lang('Sales.amount_due') ?></td>
        <td style="text-align: right; padding: 5px; width: 100px;"><?= to_currency($totals['amount_due']) ?></td>
    </tr>
    <?php endif; ?>

    <?php if ($options['show_change_due'] && isset($totals['change_due']) && $totals['change_due'] > 0): ?>
    <tr>
        <td style="text-align: right; padding: 5px;"><?= lang('Sales.change_due') ?></td>
        <td style="text-align: right; padding: 5px; width: 100px;"><?= to_currency($totals['change_due']) ?></td>
    </tr>
    <?php endif; ?>
</table>
