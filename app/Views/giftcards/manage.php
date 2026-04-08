<?php
/**
 * @var string $controller_name
 * @var string $table_headers
 * @var array $config
 */
?>

<?= view('partial/header') ?>

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

<div id="title_bar" class="btn-toolbar">
    <button class="btn btn-info btn-sm pull-right modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= esc("$controller_name/view") ?>" title="<?= lang(ucfirst($controller_name) . '.new') ?>">
        <span class="glyphicon glyphicon-heart">&nbsp;</span><?= lang(ucfirst($controller_name) . '.new') ?>
    </button>
</div>

<div id="toolbar">
    <div class="pull-left btn-toolbar">
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

<div id="table_holder">
    <table id="table"></table>
</div>

<?= view('partial/footer') ?>
