<?php
/**
 * @var array $transfer
 * @var array $locations
 * @var array $items
 * @var array|null $projects
 */
$projects = $projects ?? [];

// Ensure all transfer values have defaults
$transfer['transfer_id'] = $transfer['transfer_id'] ?? NEW_ENTRY;
$transfer['transfer_code'] = $transfer['transfer_code'] ?? '';
$transfer['project_id'] = $transfer['project_id'] ?? null;
$transfer['project_name'] = $transfer['project_name'] ?? '';
$transfer['project_code'] = $transfer['project_code'] ?? '';
$transfer['source_location_id'] = $transfer['source_location_id'] ?? null;
$transfer['transfer_type'] = $transfer['transfer_type'] ?? 'issue';
$transfer['transfer_date'] = $transfer['transfer_date'] ?? date('Y-m-d');
$transfer['reference'] = $transfer['reference'] ?? '';
$transfer['notes'] = $transfer['notes'] ?? '';
$transfer['status'] = $transfer['status'] ?? 'draft';
$transfer['items'] = $transfer['items'] ?? [];
?>
<?= view('partial/header') ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?= $transfer['transfer_id'] == NEW_ENTRY ? lang('Manufacturing.add_transfer') : lang('Manufacturing.edit_transfer') ?>
                </h3>
            </div>
            <div class="panel-body">
                <?= form_open("manufacturing/transfers/save/{$transfer['transfer_id']}", ['id' => 'transfer_form', 'class' => 'form-horizontal']) ?>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= lang('Manufacturing.transfer_code') ?></label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?= esc($transfer['transfer_code']) ?></p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= lang('Manufacturing.project') ?></label>
                    <div class="col-sm-9">
                        <?php if ($transfer['transfer_id'] == NEW_ENTRY): ?>
                            <?= form_dropdown('project_id', $projects, $transfer['project_id'], ['class' => 'form-control', 'id' => 'project_id']) ?>
                        <?php else: ?>
                            <p class="form-control-static"><?= esc($transfer['project_name']) ?> (<?= esc($transfer['project_code']) ?>)</p>
                            <?= form_hidden('project_id', $transfer['project_id']) ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= lang('Manufacturing.transfer_type') ?></label>
                    <div class="col-sm-9">
                        <?= form_dropdown('transfer_type', [
                            'issue' => lang('Manufacturing.transfer_issue'),
                            'return' => lang('Manufacturing.transfer_return')
                        ], $transfer['transfer_type'], ['class' => 'form-control', 'id' => 'transfer_type']) ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= lang('Manufacturing.source_location') ?></label>
                    <div class="col-sm-9">
                        <?= form_dropdown('source_location_id', $locations, $transfer['source_location_id'], ['class' => 'form-control', 'id' => 'source_location_id']) ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= lang('Manufacturing.transfer_date') ?></label>
                    <div class="col-sm-9">
                        <input type="text" name="transfer_date" class="form-control date-picker" value="<?= esc($transfer['transfer_date'] ?? date('Y-m-d')) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= lang('Manufacturing.reference') ?></label>
                    <div class="col-sm-9">
                        <input type="text" name="reference" class="form-control" value="<?= esc($transfer['reference'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= lang('Manufacturing.notes') ?></label>
                    <div class="col-sm-9">
                        <textarea name="notes" class="form-control" rows="3"><?= esc($transfer['notes'] ?? '') ?></textarea>
                    </div>
                </div>

                <?php if ($transfer['transfer_id'] != NEW_ENTRY): ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?= lang('Manufacturing.items') ?></h4>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped" id="items_table">
                            <thead>
                                <tr>
                                    <th><?= lang('Manufacturing.item_number') ?></th>
                                    <th><?= lang('Manufacturing.item') ?></th>
                                    <th><?= lang('Manufacturing.quantity') ?></th>
                                    <th><?= lang('Manufacturing.unit_cost') ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($transfer['items'])): ?>
                                    <?php foreach ($transfer['items'] as $item): ?>
                                    <tr>
                                        <td><?= esc($item['item_number']) ?></td>
                                        <td><?= esc($item['item_name']) ?></td>
                                        <td><?= to_quantity_decimals($item['quantity']) ?></td>
                                        <td><?= to_currency($item['unit_cost']) ?></td>
                                        <td>
                                            <?php if ($transfer['status'] == 'draft'): ?>
                                            <a href="<?= site_url("manufacturing/transfers/delete-item/{$item['transfer_item_id']}") ?>" class="btn btn-xs btn-danger">
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <?php if ($transfer['status'] == 'draft'): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" id="item_search" class="form-control" placeholder="<?= lang('Manufacturing.select_item') ?>">
                            </div>
                            <div class="col-md-3">
                                <input type="number" id="item_quantity" class="form-control" placeholder="<?= lang('Manufacturing.quantity') ?>" min="0" step="0.01">
                            </div>
                            <div class="col-md-3">
                                <button type="button" id="add_item_btn" class="btn btn-success">
                                    <span class="glyphicon glyphicon-plus"></span> <?= lang('Manufacturing.add_item') ?>
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-ok"></span> <?= lang('Common.save') ?>
                    </button>
                    <a href="<?= site_url('manufacturing/transfers') ?>" class="btn btn-default">
                        <span class="glyphicon glyphicon-arrow-left"></span> <?= lang('Common.cancel') ?>
                    </a>
                    <?php if ($transfer['transfer_id'] != NEW_ENTRY && $transfer['status'] == 'draft'): ?>
                    <a href="<?= site_url("manufacturing/transfers/confirm/{$transfer['transfer_id']}") ?>" class="btn btn-success" onclick="return confirm('<?= lang('Manufacturing.confirm_confirm') ?>')">
                        <span class="glyphicon glyphicon-ok-sign"></span> <?= lang('Manufacturing.confirm_transfer') ?>
                    </a>
                    <?php endif; ?>
                </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.date-picker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    $('#item_search').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: '<?= site_url("manufacturing/transfers/item-search") ?>',
                dataType: 'json',
                data: { term: request.term },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            $(this).val(ui.item.label).data('item-id', ui.item.value);
            return false;
        }
    });

    $('#add_item_btn').click(function() {
        var itemId = $('#item_search').data('item-id');
        var quantity = $('#item_quantity').val();

        if (!itemId || !quantity) {
            alert('<?= lang('Manufacturing.quantity_required') ?>');
            return;
        }

        $.post('<?= site_url("manufacturing/transfers/add-item") ?>', {
            transfer_id: <?= $transfer['transfer_id'] ?>,
            item_id: itemId,
            quantity: quantity
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.message);
            }
        }, 'json');
    });
});
</script>

<?= view('partial/footer') ?>
