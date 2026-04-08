<?php
/**
 * Reusable receipt footer component
 *
 * @var array $config Configuration array
 * @var array $options Additional options:
 *   - show_thank_you: bool Whether to show thank you message
 *   - show_barcode: bool Whether to show barcode
 *   - barcode_value: string Barcode value
 *   - show_policy: bool Whether to show return policy
 *   - policy_text: string Return policy text (translation key)
 *   - show_signature: bool Whether to show signature line
 *   - signature_label: string Signature label (translation key)
 *   - custom_message: string Custom message to display
 */

$defaults = [
    'show_thank_you' => true,
    'show_barcode' => false,
    'barcode_value' => null,
    'show_policy' => false,
    'policy_text' => 'Sales.return_policy',
    'show_signature' => false,
    'signature_label' => 'Sales.signature',
    'custom_message' => null,
];
$options = array_merge($defaults, $options ?? []);
?>

<div class="receipt-footer" style="margin-top: 15px;">
    <?php if ($options['show_thank_you']): ?>
    <div class="receipt-thank-you" style="text-align: center; margin-bottom: 10px;">
        <strong><?= lang('Sales.thank_you') ?></strong>
        <br>
        <?= lang('Sales.thank_you_for_shopping') ?>
    </div>
    <?php endif; ?>

    <?php if ($options['custom_message']): ?>
    <div class="receipt-custom-message" style="text-align: center; margin-bottom: 10px;">
        <?= lang($options['custom_message']) ?>
    </div>
    <?php endif; ?>

    <?php if ($options['show_barcode'] && $options['barcode_value']): ?>
    <div class="receipt-barcode" style="text-align: center; margin-bottom: 10px;">
        <img src="<?= site_url('barcodes/barcode?text=' . urlencode($options['barcode_value']) . '&size=50') ?>"
             alt="Barcode">
        <br>
        <small><?= esc($options['barcode_value']) ?></small>
    </div>
    <?php endif; ?>

    <?php if ($options['show_policy'] && !empty($config['return_policy'])): ?>
    <div class="receipt-policy" style="text-align: center; margin-top: 15px; font-size: 0.9em; border-top: 1px dotted #ccc; padding-top: 10px;">
        <strong><?= lang($options['policy_text']) ?></strong>
        <br>
        <?= esc($config['return_policy']) ?>
    </div>
    <?php endif; ?>

    <?php if ($options['show_signature']): ?>
    <div class="receipt-signature" style="margin-top: 20px;">
        <div style="border-top: 1px solid #000; width: 200px; margin-left: auto; margin-right: 0;"></div>
        <div style="text-align: right; margin-top: 5px; width: 200px; margin-left: auto; margin-right: 0;">
            <?= lang($options['signature_label']) ?>
        </div>
    </div>
    <?php endif; ?>
</div>
