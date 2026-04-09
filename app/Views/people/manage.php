<?php
/**
 * @var string $controller_name
 * @var string $table_headers
 * @var array $config
 */
?>

<?= view('partial/header') ?>

<style>
.people-page { padding: 20px 0; }
.people-breadcrumb { padding: 15px 0; }
.people-breadcrumb .breadcrumb { margin: 0; padding: 0; background: transparent; }
.people-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.people-page-header h1 { margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50; }
.people-toolbar-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.people-toolbar-card .btn { border-radius: 8px; font-weight: 500; transition: all 0.2s; }
.people-toolbar-card .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
.people-table-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
#table_holder { padding: 15px; }
#table_holder .bootstrap-table .table { border-radius: 8px; overflow: hidden; }
#table_holder .table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border: none;
}
</style>

<script type="text/javascript">
    $(document).ready(function() {
        <?= view('partial/bootstrap_tables_locale') ?>

        var show_deleted = new URLSearchParams(window.location.search).get('show_deleted') === '1';

        table_support.init({
            resource: '<?= esc($controller_name) ?>',
            headers: <?= $table_headers ?>,
            pageSize: <?= $config['lines_per_page'] ?>,
            uniqueId: 'people.person_id',
            queryParams: function() {
                return $.extend(arguments[0], {
                    show_deleted: show_deleted ? 1 : 0
                });
            },
            enableActions: function() {
                var email_disabled = show_deleted || $("td input:checkbox:checked").parents("tr").find("td a[href^='mailto:']").length == 0;
                $("#email").prop('disabled', email_disabled);
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

        $("#email").click(function(event) {
            if (show_deleted) {
                return false;
            }
            var recipients = $.map($("tr.selected a[href^='mailto:']"), function(element) {
                return $(element).attr('href').replace(/^mailto:/, '');
            });
            location.href = "mailto:" + recipients.join(",");
        });
    });
</script>

<div class="people-page">
    <div class="people-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="active"><?= lang('Module.' . $controller_name) ?></li>
        </ol>
    </div>

    <div class="people-page-header">
        <h1><span class="glyphicon glyphicon-user" style="color: #667eea; margin-right: 10px;"></span><?= lang('Module.' . $controller_name) ?></h1>
    </div>

    <div class="people-toolbar-card">
        <div class="btn-toolbar" style="margin-bottom: 15px;">
            <?php if ($controller_name === 'customers') { ?>
                <button class="btn btn-info btn-sm modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= "$controller_name/csvImport" ?>" title="<?= lang(ucfirst($controller_name) . '.import_items_csv') ?>">
                    <span class="glyphicon glyphicon-import">&nbsp;</span><?= lang('Common.import_csv') ?>
                </button>
            <?php } ?>
            <button class="btn btn-info btn-sm modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= "$controller_name/view" ?>" title="<?= lang(ucfirst($controller_name) . '.new') ?>">
                <span class="glyphicon glyphicon-plus">&nbsp;</span><?= lang(ucfirst($controller_name) . '.new') ?>
            </button>
        </div>

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
                <button id="email" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-envelope">&nbsp;</span><?= lang('Common.email') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="people-table-card">
        <div id="table_holder">
            <table id="table"></table>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
