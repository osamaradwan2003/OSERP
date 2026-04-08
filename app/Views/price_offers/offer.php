<?php
/**
 * @var int $sale_id
 * @var array $conditions
 * @var array $selected_condition_ids
 */
?>

<?= view('partial/header') ?>

<?= view('partial/print_receipt', ['print_after_sale' => false, 'selected_printer' => 'invoice_printer']) ?>

<div class="print_hide" id="control_buttons" style="text-align: right; margin-bottom: 12px;">
    <a href="javascript:printdoc();">
        <div class="btn btn-info btn-sm" id="show_print_button"><?= '<span class="glyphicon glyphicon-print">&nbsp;</span>' . lang('Common.print') ?></div>
    </a>
    <?= anchor('price_offers', '<span class="glyphicon glyphicon-chevron-left">&nbsp;</span>' . lang('Common.back'), ['class' => 'btn btn-default btn-sm']) ?>
</div>

<?php if (!empty($conditions)) { ?>
    <div class="panel panel-default print_hide">
        <div class="panel-heading"><strong>Apply Conditions</strong></div>
        <div class="panel-body">
            <?= form_open('price_offers/save_offer_conditions/' . $sale_id) ?>
            <?php foreach ($conditions as $condition) { ?>
                <div class="checkbox" style="margin-bottom: 8px;">
                    <label>
                        <input type="checkbox" name="condition_ids[]" value="<?= esc($condition['id']) ?>"
                            <?= in_array((int) $condition['id'], $selected_condition_ids ?? [], true) ? 'checked' : '' ?>>
                        <strong><?= esc($condition['title']) ?></strong>
                        <span style="color: #777;">- <?= $condition['description'] ?></span>
                    </label>
                </div>
            <?php } ?>
            <button class="btn btn-info btn-sm"><span class="glyphicon glyphicon-ok"></span> Save Conditions</button>
            <?= form_close() ?>
        </div>
    </div>
<?php } ?>

<?= view('price_offers/offer_body') ?>

<?= view('partial/footer') ?>
