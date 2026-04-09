<?php
/**
 * @var array $offers
 */
?>

<?= view('partial/header') ?>

<style>
.price-offers-page { padding: 20px 0; }
.price-offers-breadcrumb { padding: 15px 0; }
.price-offers-breadcrumb .breadcrumb { margin: 0; padding: 0; background: transparent; }
.price-offers-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.price-offers-page-header h1 { margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50; }
.price-offers-toolbar {
    background: #fff;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.price-offers-toolbar .btn { border-radius: 8px; font-weight: 500; transition: all 0.2s; }
.price-offers-toolbar .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
.price-offers-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.price-offers-card .table { margin-bottom: 0; }
.price-offers-card .table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    padding: 15px 12px;
}
.price-offers-card .table tbody tr:hover { background-color: #f8f9fa; }
.price-offers-card .table tbody td { vertical-align: middle; padding: 12px; border-color: #e9ecef; }
</style>

<div class="price-offers-page">
    <div class="price-offers-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="active"><?= lang('Price_offers.price_offers') ?></li>
        </ol>
    </div>

    <div class="price-offers-page-header">
        <h1><span class="glyphicon glyphicon-tag" style="color: #667eea; margin-right: 10px;"></span><?= lang('Price_offers.price_offers') ?></h1>
    </div>

    <div class="price-offers-toolbar">
        <?= anchor('price_offers/create', '<span class="glyphicon glyphicon-plus">&nbsp;</span>' . lang('Common.new') . ' ' . lang('Module.price_offers'), ['class' => 'btn btn-info btn-sm']) ?>
        <?= anchor('price_offers/conditions', '<span class="glyphicon glyphicon-cog">&nbsp;</span>Conditions', ['class' => 'btn btn-default btn-sm']) ?>
    </div>

    <div class="price-offers-card">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th><?= lang('Sales.quote_number') ?></th>
                    <th><?= lang('Common.date') ?></th>
                    <th><?= lang('Customers.customer') ?></th>
                    <th><?= lang('Common.actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($offers)) { ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted" style="padding: 40px;">
                            <span class="glyphicon glyphicon-folder-open" style="font-size: 48px; opacity: 0.3;"></span>
                            <p style="margin-top: 15px;">No price offers found.</p>
                        </td>
                    </tr>
                <?php } else { ?>
                    <?php foreach ($offers as $offer) { ?>
                        <tr>
                            <td><strong><?= esc($offer['quote_number_display']) ?></strong></td>
                            <td><?= esc($offer['sale_date']) ?></td>
                            <td><?= esc($offer['customer_name']) ?></td>
                            <td>
                                <?= anchor('price_offers/view/' . $offer['sale_id'], '<span class="glyphicon glyphicon-eye-open">&nbsp;</span>View', ['class' => 'btn btn-xs btn-info']) ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
