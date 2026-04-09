<?php
/**
 * @var array $transfers
 */
?>

<?= view('partial/header') ?>

<style>
.transfers-page { padding: 20px 0; }
.transfers-breadcrumb { padding: 15px 0; }
.transfers-breadcrumb .breadcrumb { margin: 0; padding: 0; background: transparent; }
.transfers-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.transfers-page-header h1 { margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50; }
.transfers-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.transfers-card .panel-heading {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 12px 12px 0 0;
    font-weight: 600;
    padding: 15px 20px;
}
.transfers-card .panel-body { padding: 0; }
.transfers-table { margin-bottom: 0; }
.transfers-table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    padding: 15px 12px;
}
.transfers-table tbody tr:hover { background-color: #f8f9fa; }
.transfers-table tbody td { vertical-align: middle; padding: 12px; border-color: #e9ecef; }
.transfers-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}
.transfers-badge-pending { background: #fff3cd; color: #856404; }
.transfers-badge-in_progress { background: #d1ecf1; color: #0c5460; }
.transfers-badge-completed { background: #d4edda; color: #155724; }
.transfers-badge-cancelled { background: #f8d7da; color: #721c24; }
</style>

<div class="transfers-page">
    <div class="transfers-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="active"><?= lang('Transfers.transfers_history') ?></li>
        </ol>
    </div>

    <div class="transfers-page-header">
        <h1><span class="glyphicon glyphicon-transfer" style="color: #667eea; margin-right: 10px;"></span><?= lang('Transfers.transfers_history') ?></h1>
        <a href="<?= base_url('transfers/view') ?>" class="btn btn-info btn-sm">
            <span class="glyphicon glyphicon-plus"></span> <?= lang('Transfers.new_transfer') ?>
        </a>
    </div>

    <div class="transfers-card panel panel-default">
        <div class="panel-body">
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
                            <td colspan="8" class="text-center text-muted" style="padding: 40px;">
                                <span class="glyphicon glyphicon-folder-open" style="font-size: 48px; opacity: 0.3;"></span>
                                <p style="margin-top: 15px;"><?= lang('Transfers.no_transfers_found') ?></p>
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
                                    <span class="transfers-badge transfers-badge-<?= strtolower($transfer['transfer_status']) ?>"><?= ucfirst($transfer['transfer_status']) ?></span>
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
</div>

<?= view('partial/footer') ?>
