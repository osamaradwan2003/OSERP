<?php
/**
 * @var array|null $entry
 * @var array $attachments
 */
?>

<?php if (empty($entry)): ?>
    <div class="alert alert-danger"><?= lang('Cashflow.not_found') ?></div>
<?php else: ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= lang('Common.det') ?></h3>
        </div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt><?= lang('Common.id') ?></dt>
                <dd><?= esc($entry['entry_id']) ?></dd>

                <dt><?= lang('Reports.date') ?></dt>
                <dd><?= esc(to_datetime(strtotime($entry['entry_date']))) ?></dd>

                <dt><?= lang('Reports.type') ?></dt>
                <dd><?= esc(lang('Cashflow.' . ($entry['entry_type'] ?? ''))) ?></dd>

                <dt><?= lang('Cashflow.category') ?></dt>
                <dd><?= ($entry['entry_type'] ?? '') === 'transfer' ? '-' : esc($entry['category_name'] ?? '-') ?></dd>

                <?php if (($entry['entry_type'] ?? '') === 'transfer'): ?>
                    <dt><?= lang('Cashflow.from_account') ?></dt>
                    <dd><?= esc($entry['from_account_name'] ?? '-') ?></dd>

                    <dt><?= lang('Cashflow.to_account') ?></dt>
                    <dd><?= esc($entry['to_account_name'] ?? '-') ?></dd>
                <?php else: ?>
                    <dt><?= lang('Cashflow.account') ?></dt>
                    <dd><?= esc($entry['account_name'] ?? '-') ?></dd>
                <?php endif; ?>

                <dt><?= lang('Cashflow.party') ?></dt>
                <dd><?= esc(($entry['customer_name'] ?? '') ?: ($entry['supplier_name'] ?? '-')) ?></dd>

                <dt><?= lang('Cashflow.amount') ?></dt>
                <dd><?= esc(to_currency($entry['amount'])) ?></dd>

                <dt><?= lang('Cashflow.status') ?></dt>
                <dd><?= esc(lang('Cashflow.' . ($entry['status'] ?? ''))) ?></dd>

                <dt><?= lang('Common.description') ?></dt>
                <dd><?= esc(($entry['description'] ?? '') !== '' ? $entry['description'] : '-') ?></dd>

                <dt><?= lang('Cashflow.sale_reference') ?></dt>
                <dd>
                    <?php if (!empty($entry['sale_id'])): ?>
                        <?php $saleId = (int) $entry['sale_id']; ?>
                        <div>
                            <a href="<?= site_url('sales/receipt/' . $saleId) ?>" target="_blank">
                                <?= esc('POS ' . $saleId) ?>
                            </a>
                        </div>

                    <?php else: ?>
                        -
                    <?php endif; ?>
                </dd>

                <dt><?= lang('Cashflow.receiving_reference') ?></dt>
                <dd>
                    <?php if (!empty($entry['receiving_id'])): ?>
                        <?php $receivingId = (int) $entry['receiving_id']; ?>
                        <a href="<?= site_url('receivings/receipt/' . $receivingId) ?>" target="_blank">
                            <?= esc('RECV ' . $receivingId) ?>
                        </a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </dd>
                <dt><?= lang('Cashflow.attachments') ?></dt>
                <dd>
                    <?php if (empty($attachments)): ?>
                        -
                    <?php else: ?>
                        <?php foreach ($attachments as $attachment): ?>
                            <div>
                                <a href="<?= base_url('writable/' . $attachment['file_path']) ?>" target="_blank">
                                    <?= esc($attachment['file_name']) ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </dd>
            </dl>
        </div>
    </div>
<?php endif; ?>



