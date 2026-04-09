<?php
/**
 * @var string $controller_name
 * @var string $table_headers
 * @var array $config
 */
?>

<?= view('partial/header') ?>

<style>
.giftcards-page { padding: 20px 0; }
.giftcards-breadcrumb { padding: 15px 0; }
.giftcards-breadcrumb .breadcrumb { margin: 0; padding: 0; background: transparent; }
.giftcards-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.giftcards-page-header h1 { margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50; }
.giftcards-toolbar-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.giftcards-toolbar-card .btn { border-radius: 8px; font-weight: 500; transition: all 0.2s; }
.giftcards-toolbar-card .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
.giftcards-table-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.giftcards-table-card .table { margin-bottom: 0; }
.giftcards-table-card .table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    padding: 15px 12px;
}
.giftcards-table-card .table tbody tr:hover { background-color: #f8f9fa; }
.giftcards-table-card .table tbody td { vertical-align: middle; padding: 12px; border-color: #e9ecef; }
#table_holder { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
#table_holder .bootstrap-table .table { border-radius: 0; }
.bootstrap-table .table thead th { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; color: #fff; }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        <?= view('partial/bootstrap_tables_locale') ?>

        var show_deleted = new URLSearchParams(window.location.search).get('show_deleted') === '1';

        table_support.init({
            resource: '<?= esc($controller_name) ?>',
            headers: <?= $table_headers ?>,
            pageSize: <?= $config['lines_per_page'] ?>,
            uniqueId: 'giftcard_id',
            queryParams: function() {
                return $.extend(arguments[0], {
                    show_deleted: show_deleted ? 1 : 0
                });
            }
        });

        $('#toggle_deleted').toggleClass('btn-warning', show_deleted);
        $('#toggle_deleted .toggle-label').text(show_deleted ? "<?= lang('Common.hide_deleted') ?>" : "<?= lang('Common.show_deleted') ?>");

        $('#toggle_deleted').click(function() {
            var params = new URLSearchParams(window.location.search);
            if (show_deleted) {
                params.delete('show_deleted');
            } else {
                params.set('show_deleted', '1');
            }
            window.location.search = params.toString();
        });
    });
</script>

<div class="giftcards-page">
    <div class="giftcards-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="active"><?= lang('Giftcards.giftcards') ?></li>
        </ol>
    </div>

    <div class="giftcards-page-header">
        <h1><span class="glyphicon glyphicon-heart" style="color: #667eea; margin-right: 10px;"></span><?= lang('Giftcards.giftcards') ?></h1>
        <button class="btn btn-info btn-sm modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= esc("$controller_name/view") ?>" title="<?= lang(ucfirst($controller_name) . '.new') ?>">
            <span class="glyphicon glyphicon-plus">&nbsp;</span><?= lang(ucfirst($controller_name) . '.new') ?>
        </button>
    </div>

    <div class="giftcards-toolbar-card">
        <div class="btn-toolbar">
            <div class="pull-left btn-group">
                <button id="toggle_deleted" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-eye-open">&nbsp;</span><span class="toggle-label"><?= lang('Common.show_deleted') ?></span>
                </button>
                <button id="delete" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-trash">&nbsp;</span><?= lang('Common.delete') ?>
                </button>
                <button id="restore" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-repeat">&nbsp;</span><?= lang('Common.restore') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="giftcards-table-card">
        <div id="table_holder">
            <table id="table"></table>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
