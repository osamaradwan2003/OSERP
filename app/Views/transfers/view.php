<?php
/**
 * @var array $transfer
 * @var array $items
 */

?>

<?= view('partial/header') ?>



<div class="container-fluid" style="margin-top: 20px;">

    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?= lang('Transfers.transfer_details') ?> - TRANSFER <?= $transfer['transfer_id'] ?>
                <a href="<?= base_url('transfers') ?>" class="btn btn-default btn-sm pull-right">
                    <span class="glyphicon glyphicon-arrow-left"></span> <?= lang('Common.back') ?>
                </a>
            </h3>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <p>
                        <strong><?= lang('Transfers.transfer_id') ?>:</strong>
                        TRANSFER <?= $transfer['transfer_id'] ?>
                    </p>
                    <p>
                        <strong><?= lang('Transfers.date') ?>:</strong>
                        <?= to_datetime(strtotime($transfer['transfer_datetime'])) ?>
                    </p>
                    <p>
                        <strong><?= lang('Transfers.employee') ?>:</strong>
                        <?= esc($transfer['first_name']) ?> <?= esc($transfer['last_name']) ?>
                    </p>
                    <p>
                        <strong><?= lang('Transfers.status') ?>:</strong>
                        <span class="label label-success"><?= ucfirst($transfer['transfer_status']) ?></span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p>
                        <strong><?= lang('Transfers.source_location') ?>:</strong>
                        <?= esc($transfer['location_name']) ?>
                    </p>
                    <p>
                        <strong><?= lang('Transfers.destination_location') ?>:</strong>
                        <?= isset($transfer['dest_location_name']) ? esc($transfer['dest_location_name']) : '-' ?>
                    </p>
                    <?php if (!empty($transfer['reference'])): ?>
                        <p>
                            <strong><?= lang('Transfers.reference') ?>:</strong>
                            <?= esc($transfer['reference']) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($transfer['comment'])): ?>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-12">
                        <p><strong><?= lang('Transfers.comment') ?>:</strong></p>
                        <p><?= nl2br(esc($transfer['comment'])) ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Transfer Items -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= lang('Transfers.items_transferred') ?></h3>
        </div>

        <table class="table table-striped table-condensed">
            <thead>
                <tr class="active">
                    <th><?= lang('Transfers.item_number') ?></th>
                    <th><?= lang('Transfers.item_name') ?></th>
                    <th class="text-right"><?= lang('Transfers.quantity') ?></th>
                    <th><?= lang('Transfers.description') ?></th>
                    <th><?= lang('Transfers.serial_number') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            <?= lang('Transfers.no_items_in_transfer') ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                    $total_quantity = 0;
                    foreach ($items as $item):
                        $total_quantity += $item['quantity'];
                    ?>
                        <tr>
                            <td><?= esc($item['item_number']) ?></td>
                            <td><?= esc($item['name']) ?></td>
                            <td class="text-right"><?= to_quantity_decimals($item['quantity']) ?></td>
                            <td><?= esc($item['description']) ?></td>
                            <td><?= esc($item['serialnumber']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="success">
                        <td colspan="2"><strong><?= lang('Transfers.total') ?></strong></td>
                        <td class="text-right"><strong><?= to_quantity_decimals($total_quantity) ?></strong></td>
                        <td colspan="2"></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-md-12">
            <a href="<?= base_url('transfers') ?>" class="btn btn-default">
                <span class="glyphicon glyphicon-arrow-left"></span> <?= lang('Common.back') ?>
            </a>
        </div>
    </div>

</div>

<?= view('partial/footer') ?>
