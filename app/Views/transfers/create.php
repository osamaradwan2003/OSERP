<?php
/**
 * @var string $controller_name
 * @var array $cart
 * @var int $source_location
 * @var int $destination_location
 * @var array $source_locations
 * @var array $destination_locations
 * @var string $reference
 * @var string $comment
 */
?>

<?= view('partial/header') ?>

<div class="container-fluid" style="margin-top: 20px;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?= lang('Transfers.new_transfer') ?>
                <a href="<?= base_url('transfers') ?>" class="btn btn-default btn-sm pull-right">
                    <span class="glyphicon glyphicon-arrow-left"></span> <?= lang('Common.back') ?>
                </a>
            </h3>
        </div>

        <div class="panel-body">
            <?= view('transfers/form', [
                'controller_name' => $controller_name ?? 'transfers',
                'cart' => $cart,
                'source_location' => $source_location,
                'destination_location' => $destination_location,
                'source_locations' => $source_locations,
                'destination_locations' => $destination_locations,
                'reference' => $reference,
                'comment' => $comment,
                'error' => $error ?? null
            ]) ?>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
