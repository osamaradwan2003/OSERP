<?php
/**
 * @var array $offers
 */
?>

<?= view('partial/header') ?>

<div id="title_bar" class="btn-toolbar" style="margin-bottom: 12px;">
    <?= anchor('price_offers/create', '<span class="glyphicon glyphicon-plus">&nbsp;</span>' . lang('Common.new') . ' ' . lang('Module.price_offers'), ['class' => 'btn btn-info btn-sm pull-right']) ?>
    <?= anchor('price_offers/conditions', '<span class="glyphicon glyphicon-cog">&nbsp;</span>Conditions', ['class' => 'btn btn-default btn-sm pull-right', 'style' => 'margin-right: 8px;']) ?>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th><?= lang('Sales.quote_number') ?></th>
            <th><?= lang('Common.date') ?></th>
            <th><?= lang('Customers.customer') ?></th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($offers)) { ?>
            <tr>
                <td colspan="4">No price offers found.</td>
            </tr>
        <?php } else { ?>
            <?php foreach ($offers as $offer) { ?>
                <tr>
                    <td><?= esc($offer['quote_number_display']) ?></td>
                    <td><?= esc($offer['sale_date']) ?></td>
                    <td><?= esc($offer['customer_name']) ?></td>
                    <td>
                        <?= anchor('price_offers/view/' . $offer['sale_id'], '<span class="glyphicon glyphicon-eye-open">&nbsp;</span>View', ['class' => 'btn btn-default btn-xs']) ?>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
</div>

<?= view('partial/footer') ?>
