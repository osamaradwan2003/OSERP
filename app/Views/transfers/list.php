<?php
/**
 * @var array $transfers
 */
?>

<?= view('partial/header') ?>

<div class="container-fluid" style="margin-top: 20px;">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?= lang('Transfers.transfers_history') ?>
                <a href="<?= base_url('transfers/view') ?>" class="btn btn-primary btn-sm pull-right">
                    <span class="glyphicon glyphicon-plus"></span> <?= lang('Transfers.new_transfer') ?>
                </a>
            </h3>
        </div>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th><?= lang('Transfers.transfer_id') ?></th>
                    <th><?= lang('Transfers.date') ?></th>
                    <th><?= lang('Transfers.source_location') ?></th>
                    <th><?= lang('Transfers.destination_location') ?></th>
                    <th><?= lang('Transfers.employee') ?></th>
                    <th><?= lang('Transfers.reference') ?></th>
                    <th><?= lang('Transfers.status') ?></th>
                    <th><?= lang('Common.action') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transfers)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            <?= lang('Transfers.no_transfers_found') ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transfers as $transfer): ?>
                        <tr>
                            <td><strong>TRANSFER <?= $transfer['transfer_id'] ?></strong></td>
                            <td><?= to_datetime(strtotime($transfer['transfer_datetime'])) ?></td>
                            <td><?= esc($transfer['location_name']) ?></td>
                            <td><?= isset($transfer['dest_location_name']) ? esc($transfer['dest_location_name']) : '-' ?></td>
                            <td><?= esc($transfer['first_name']) ?> <?= esc($transfer['last_name']) ?></td>
                            <td><?= esc($transfer['reference']) ?></td>
                            <td>
                                <span class="label label-success"><?= ucfirst($transfer['transfer_status']) ?></span>
                            </td>
                            <td>
                                <a href="<?= base_url('transfers/view/' . $transfer['transfer_id']) ?>" class="btn btn-xs btn-info">
                                    <span class="glyphicon glyphicon-eye-open"></span> <?= lang('Common.view') ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?= view('partial/footer') ?>
