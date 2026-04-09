<?php
/**
 * @var string $controller_name
 */
?>

<?= view('partial/header') ?>

<style>
.taxes-page { padding: 20px 0; }
.taxes-breadcrumb { padding: 15px 0; }
.taxes-breadcrumb .breadcrumb { margin: 0; padding: 0; background: transparent; }
.taxes-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.taxes-page-header h1 { margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50; }
.taxes-tabs-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    padding: 20px;
}
.taxes-tabs-card .nav-tabs {
    border-bottom: 2px solid #e9ecef;
    margin-bottom: 20px;
}
.taxes-tabs-card .nav-tabs > li > a {
    border-radius: 8px 8px 0 0;
    font-weight: 600;
    color: #6c757d;
    transition: all 0.2s;
}
.taxes-tabs-card .nav-tabs > li > a:hover { color: #667eea; background: #f8f9fa; }
.taxes-tabs-card .nav-tabs > li.active > a,
.taxes-tabs-card .nav-tabs > li.active > a:hover,
.taxes-tabs-card .nav-tabs > li.active > a:focus {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-color: transparent;
}
.taxes-tabs-card .nav-tabs > li > a .glyphicon { margin-right: 5px; }
.taxes-tabs-card .tab-content .tab-pane { padding: 10px 0; }
.taxes-tabs-card .panel { border-radius: 10px; border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
.taxes-tabs-card .panel-heading {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 10px 10px 0 0;
    font-weight: 600;
}
.taxes-tabs-card .panel-heading .btn { border-radius: 6px; }
.taxes-tabs-card .panel-body { padding: 15px; }
.taxes-table { margin-bottom: 0; }
.taxes-table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    padding: 12px 10px;
}
.taxes-table tbody tr:hover { background-color: #f8f9fa; }
.taxes-table tbody td { vertical-align: middle; padding: 10px; border-color: #e9ecef; }
.taxes-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}
.taxes-badge-success { background: #d4edda; color: #155724; }
.taxes-badge-warning { background: #fff3cd; color: #856404; }
.taxes-badge-danger { background: #f8d7da; color: #721c24; }
</style>

<script type="text/javascript">
    dialog_support.init("a.modal-dlg");
</script>

<div class="taxes-page">
    <div class="taxes-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="active"><?= lang('Taxes.config') ?></li>
        </ol>
    </div>

    <div class="taxes-page-header">
        <h1><span class="glyphicon glyphicon glyphicon-file" style="color: #667eea; margin-right: 10px;"></span><?= lang('Taxes.config') ?></h1>
    </div>

    <div class="taxes-tabs-card">
        <ul class="nav nav-tabs" data-tabs="tabs">
            <li class="active" role="presentation">
                <a data-toggle="tab" href="#tax_codes_tab" title="<?= lang(ucfirst($controller_name) . '.tax_codes_configuration') ?>">
                    <span class="glyphicon glyphicon-barcode"></span>
                    <?= lang(ucfirst($controller_name) . '.tax_codes') ?>
                </a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#tax_jurisdictions_tab" title="<?= lang(ucfirst($controller_name) . '.tax_jurisdictions_configuration') ?>">
                    <span class="glyphicon glyphicon-map-marker"></span>
                    <?= lang(ucfirst($controller_name) . '.tax_jurisdictions') ?>
                </a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#tax_categories_tab" title="<?= lang(ucfirst($controller_name) . '.tax_categories_configuration') ?>">
                    <span class="glyphicon glyphicon-folder-open"></span>
                    <?= lang(ucfirst($controller_name) . '.tax_categories') ?>
                </a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#tax_rates_tab" title="<?= lang(ucfirst($controller_name) . '.tax_rate_configuration') ?>">
                    <span class="glyphicon glyphicon-usd"></span>
                    <?= lang(ucfirst($controller_name) . '.tax_rates') ?>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade in active" id="tax_codes_tab">
                <?= view('taxes/tax_codes') ?>
            </div>
            <div class="tab-pane" id="tax_jurisdictions_tab">
                <?= view('taxes/tax_jurisdictions') ?>
            </div>
            <div class="tab-pane" id="tax_categories_tab">
                <?= view('taxes/tax_categories') ?>
            </div>
            <div class="tab-pane" id="tax_rates_tab">
                <?= view('taxes/tax_rates') ?>
            </div>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
