<?php
/**
 * Reusable receipt header component
 *
 * @var array $config Configuration array
 * @var array $options Additional options:
 *   - show_logo: bool Whether to show company logo
 *   - show_company: bool Whether to show company name
 *   - show_address: bool Whether to show company address
 *   - show_phone: bool Whether to show company phone
 *   - show_email: bool Whether to show company email
 *   - show_website: bool Whether to show company website
 *   - receipt_title: string Receipt title (translation key)
 *   - receipt_number: string Receipt number
 *   - receipt_date: string Receipt date
 *   - show_receipt_info: bool Whether to show receipt number and date
 */

$defaults = [
    'show_logo' => true,
    'show_company' => true,
    'show_address' => true,
    'show_phone' => true,
    'show_email' => false,
    'show_website' => false,
    'receipt_title' => 'Sales.receipt',
    'receipt_number' => null,
    'receipt_date' => null,
    'show_receipt_info' => true,
];
$options = array_merge($defaults, $options ?? []);
?>

<div class="receipt-header">
    <?php if ($options['show_logo'] && !empty($config['company_logo'])): ?>
    <div class="receipt-logo">
        <img src="<?= base_url('uploads/' . esc($config['company_logo'], 'url')) ?>"
             alt="<?= esc($config['company']) ?>"
             style="max-width: 100%; max-height: 100px;">
    </div>
    <?php endif; ?>

    <?php if ($options['show_company']): ?>
    <div class="receipt-company">
        <strong><?= esc($config['company']) ?></strong>
    </div>
    <?php endif; ?>

    <?php if ($options['show_address'] && !empty($config['address'])): ?>
    <div class="receipt-address">
        <?= esc($config['address']) ?>
    </div>
    <?php endif; ?>

    <?php if ($options['show_phone'] && !empty($config['phone'])): ?>
    <div class="receipt-phone">
        <?= esc($config['phone']) ?>
    </div>
    <?php endif; ?>

    <?php if ($options['show_email'] && !empty($config['email'])): ?>
    <div class="receipt-email">
        <?= esc($config['email']) ?>
    </div>
    <?php endif; ?>

    <?php if ($options['show_website'] && !empty($config['website'])): ?>
    <div class="receipt-website">
        <?= esc($config['website']) ?>
    </div>
    <?php endif; ?>

    <div class="receipt-title" style="margin-top: 10px; font-weight: bold; font-size: 1.2em;">
        <?= lang($options['receipt_title']) ?>
    </div>

    <?php if ($options['show_receipt_info']): ?>
    <div class="receipt-info" style="margin-top: 5px;">
        <?php if ($options['receipt_number']): ?>
        <div class="receipt-number">
            <strong><?= lang('Sales.receipt_number') ?>:</strong> <?= esc($options['receipt_number']) ?>
        </div>
        <?php endif; ?>
        <?php if ($options['receipt_date']): ?>
        <div class="receipt-date">
            <strong><?= lang('Sales.date') ?>:</strong> <?= esc($options['receipt_date']) ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
