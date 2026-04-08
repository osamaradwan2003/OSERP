<?php ?>

<?= view('partial/header') ?>

<div class="container-fluid" style="margin-top: 20px;">

    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><?= lang('Common.error') ?></h4>
        <p><?= lang('Transfers.transfer_not_found') ?></p>
    </div>

    <a href="<?= base_url('transfers') ?>" class="btn btn-default">
        <span class="glyphicon glyphicon-arrow-left"></span> <?= lang('Common.back') ?>
    </a>

</div>

<?= view('partial/footer') ?>
