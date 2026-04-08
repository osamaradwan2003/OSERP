<?php
/**
 * @var string $transfer_id
 * @var int $source_location_id
 * @var string $source_location_name
 * @var int $destination_location_id
 * @var string $destination_location_name
 * @var array $cart
 * @var int $total_items
 * @var string $reference
 * @var string $comment
 * @var string $employee
 * @var string $transfer_date
 * @var string $error_message
 */
?>

<?= view('partial/header') ?>

<div class="container-fluid" style="margin-top: 20px;">

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong><?= lang('Common.error') ?></strong> <?= esc($error_message) ?>
        </div>
    <?php else: ?>

        <!-- Transfer Receipt -->
        <div class="panel panel-success">
            <div class="panel-heading text-center">
                <h3 class="panel-title"><?= lang('Transfers.transfer_receipt') ?></h3>
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong><?= lang('Transfers.transfer_id') ?>:</strong> <?= esc($transfer_id) ?></p>
                        <p><strong><?= lang('Transfers.transfer_date') ?>:</strong> <?= esc($transfer_date) ?></p>
                        <p><strong><?= lang('Transfers.employee') ?>:</strong> <?= esc($employee) ?></p>
                        <?php if (!empty($reference)): ?>
                            <p><strong><?= lang('Transfers.reference') ?>:</strong> <?= esc($reference) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <p><strong><?= lang('Transfers.source_location') ?>:</strong> <?= esc($source_location_name) ?></p>
                        <p><strong><?= lang('Transfers.destination_location') ?>:</strong> <?= esc($destination_location_name) ?></p>
                        <p><strong><?= lang('Transfers.total_items') ?>:</strong> <?= to_quantity_decimals($total_items) ?></p>
                    </div>
                </div>

                <?php if (!empty($comment)): ?>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p><strong><?= lang('Transfers.comment') ?>:</strong></p>
                            <p><?= nl2br(esc($comment)) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Transfer Items Table -->
                <table class="table table-striped table-condensed" style="margin-top: 20px;">
                    <thead>
                        <tr class="info">
                            <th><?= lang('Transfers.item_number') ?></th>
                            <th><?= lang('Transfers.item_name') ?></th>
                            <th class="text-right"><?= lang('Transfers.quantity') ?></th>
                            <th><?= lang('Transfers.description') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $item): ?>
                            <tr>
                                <td><?= esc($item['item_number']) ?></td>
                                <td><?= esc($item['name']) ?></td>
                                <td class="text-right"><?= to_quantity_decimals($item['quantity']) ?></td>
                                <td><?= esc($item['description']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="success">
                            <td colspan="2"><strong><?= lang('Transfers.total') ?></strong></td>
                            <td class="text-right"><strong><?= to_quantity_decimals($total_items) ?></strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <!-- Action Buttons -->
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12 text-center">
                        <a href="<?= base_url('transfers') ?>" class="btn btn-primary btn-lg">
                            <span class="glyphicon glyphicon-arrow-left"></span> <?= lang('Transfers.new_transfer') ?>
                        </a>
                        <button onclick="window.print()" class="btn btn-default btn-lg">
                            <span class="glyphicon glyphicon-print"></span> <?= lang('Common.print') ?>
                        </button>
                    </div>
                </div>

            </div>
        </div>

    <?php endif; ?>

</div>

<style>
    @media print {
        .btn, .row:last-child {
            display: none;
        }

        .panel {
            border: none;
            box-shadow: none;
        }

        .panel-heading {
            background-color: #f5f5f5;
        }
    }
</style>

<?= view('partial/footer') ?>
