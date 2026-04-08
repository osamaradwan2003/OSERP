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

<div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>
<ul id="error_message_box" class="error_message_box"></ul>
<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= esc($error) ?></div>
<?php endif; ?>
<?php if (!empty($error_message)): ?>
    <div class="alert alert-danger"><?= esc($error_message) ?></div>
<?php endif; ?>

<?= form_open("$controller_name/complete", ['id' => 'transfers_edit_form', 'class' => 'form-horizontal']) ?>
    <fieldset id="transfer_basic_info">

        <!-- Location Selection Section -->
        <div class="form-group form-group-sm">
            <?= form_label(lang('Transfers.source_location'), 'source_location', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-4">
                <?= form_dropdown('source_location', $source_locations, $source_location, ['id' => 'source_location', 'class' => 'form-control']) ?>
            </div>

            <?= form_label(lang('Transfers.destination_location'), 'destination_location', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-2">
                <?= form_dropdown('destination_location', $destination_locations, $destination_location, ['id' => 'destination_location', 'class' => 'form-control']) ?>
            </div>
        </div>

        <!-- Item Search Section -->
        <div class="form-group form-group-sm">
            <?= form_label(lang('Transfers.search_item'), 'item', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-4">
                <?= form_input(['name' => 'item', 'id' => 'item', 'class' => 'form-control input-sm', 'placeholder' => lang('Transfers.item_name_or_number')]) ?>
            </div>

            <?= form_label(lang('Transfers.quantity'), 'quantity', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-2">
                <?= form_input(['type' => 'number', 'name' => 'quantity', 'id' => 'quantity', 'class' => 'form-control input-sm', 'value' => 1, 'min' => 0.001, 'step' => 0.001]) ?>
                <?= form_button(['type' => 'button', 'name' => 'add_item_btn', 'id' => 'add_item_btn', 'content' => lang('Common.add'), 'class' => 'btn btn-primary btn-sm', 'style' => 'margin-left: 5px;']) ?>
            </div>
        </div>

    </fieldset>

    <!-- Transfer Items Table -->
    <fieldset id="transfer_items_info">
        <div style="margin: 15px 0; padding: 15px 0; border-top: 1px solid #ddd;">
            <h4><?= lang('Transfers.items') ?></h4>
            <div class="table-responsive">
                <table class="table table-striped table-condensed">
                    <thead>
                        <tr>
                            <th style="width: 5%;"><?= lang('Common.delete') ?></th>
                            <th style="width: 12%;"><?= lang('Transfers.item_number') ?></th>
                            <th style="width: 28%;"><?= lang('Transfers.item_name') ?></th>
                            <th style="width: 10%;"><?= lang('Transfers.available') ?></th>
                            <th style="width: 12%;"><?= lang('Transfers.quantity') ?></th>
                            <th style="width: 25%;"><?= lang('Transfers.description') ?></th>
                            <th style="width: 8%;"><?= lang('Common.update') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($cart) == 0): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    <?= lang('Transfers.no_items_in_transfer') ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach (array_reverse($cart, true) as $line => $item): ?>
                                <tr>
                                    <td>
                                        <?= anchor("$controller_name/deleteItem/$line", '<span class="glyphicon glyphicon-trash"></span>', ['data-item-id' => $line, 'class' => 'delete_item_button']) ?>
                                    </td>
                                    <td><?= esc($item['item_number']) ?></td>
                                    <td><?= esc($item['name']) ?></td>
                                    <td class="text-center">
                                        <?= to_quantity_decimals($item['in_stock']) ?>
                                    </td>
                                    <td>
                                        <?= form_input(['name' => 'quantity', 'data-line' => $line, 'class' => 'form-control input-sm item-quantity', 'value' => to_quantity_decimals($item['quantity']), 'style' => 'width: 100%;']) ?>
                                    </td>
                                    <td>
                                        <?= form_input(['name' => 'description', 'data-line' => $line, 'class' => 'form-control input-sm item-description', 'value' => esc($item['description']), 'style' => 'width: 100%;']) ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-xs btn-info update_item_button" data-line="<?= $line ?>"><?= lang('Common.update') ?></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </fieldset>

    <!-- Transfer Details Section -->
    <fieldset id="transfer_details_info">
        <div style="margin: 15px 0; padding: 15px 0; border-top: 1px solid #ddd;">
            <h4><?= lang('Transfers.transfer_details') ?></h4>

            <div class="form-group form-group-sm">
                <?= form_label(lang('Transfers.reference'), 'transfer_reference', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-8">
                    <?= form_input(['name' => 'transfer_reference', 'id' => 'transfer_reference', 'class' => 'form-control input-sm', 'value' => esc($reference)]) ?>
                </div>
            </div>

            <div class="form-group form-group-sm">
                <?= form_label(lang('Transfers.comment'), 'comment', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-8">
                    <?= form_textarea(['name' => 'comment', 'id' => 'comment', 'class' => 'form-control input-sm', 'rows' => 3, 'value' => esc($comment)]) ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-8">
                    <?php if (count($cart) > 0): ?>
                        <?= form_submit(['name' => 'submit', 'value' => lang('Transfers.complete_transfer'), 'class' => 'btn btn-success btn-lg']) ?>
                    <?php else: ?>
                        <?= form_submit(['name' => 'submit', 'value' => lang('Transfers.complete_transfer'), 'class' => 'btn btn-success btn-lg', 'disabled' => 'disabled']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </fieldset>

<?= form_close() ?>

<script type="text/javascript">
    $(document).ready(function() {
        const csrfName = "<?= esc(config('Security')->tokenName, 'js') ?>";
        const csrfHash = "<?= csrf_hash() ?>";
        const withCsrf = function(data) {
            const payload = data || {};
            payload[csrfName] = csrfHash;
            return payload;
        };

        const transferPageUrl = "<?= site_url("$controller_name/transfer"); ?>";
        const redirect = function() {
            window.location.href = transferPageUrl;
        };
        const reloadForm = function() {
            window.location.reload();
        };

        // Delete item
        $(".delete_item_button").click(function(e) {
            e.preventDefault();
            window.location.href = $(this).attr('href');
        });

        // Update item quantity/description
        $(".update_item_button").click(function() {
            const line = $(this).data('line');
            const quantity = $('input[data-line="' + line + '"].item-quantity').val();
            const description = $('input[data-line="' + line + '"].item-description').val();
            
            $.ajax({
                url: "<?= site_url("$controller_name/editItem/"); ?>" + line,
                type: 'POST',
                data: withCsrf({
                    'quantity': quantity,
                    'description': description
                }),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $.notify({
                            message: response.message
                        }, {
                            type: 'success'
                        });
                        setTimeout(reloadForm, 400);
                    } else {
                        $.notify({
                            message: response.message
                        }, {
                            type: 'danger'
                        });
                    }
                }
            });
        });

        // Location change via AJAX
        $('#source_location, #destination_location').change(function() {
            const source = $('#source_location').val();
            const destination = $('#destination_location').val();
            
            $.post("<?= site_url("$controller_name/changeLocations"); ?>", withCsrf({
                'source_location': source,
                'destination_location': destination
            }), function(response) {
                if (response && response.success) {
                    reloadForm();
                } else if (response && response.error) {
                    $.notify({
                        message: response.error
                    }, {
                        type: 'danger'
                    });
                    setTimeout(reloadForm, 1200);
                }
            }, 'json');
        });

        // Add item
        $('#add_item_btn').click(function(e) {
            e.preventDefault();
            $('#item').focus();
            $.post("<?= site_url("$controller_name/addItem"); ?>", withCsrf({
                'item': $('#item').val(),
                'quantity': $('#quantity').val()
            }), function(response) {
                if (response && response.success) {
                    $.notify({
                        message: response.message
                    }, {
                        type: 'success'
                    });
                    setTimeout(reloadForm, 300);
                } else if (response) {
                    $.notify({
                        message: response.message
                    }, {
                        type: 'danger'
                    });
                }
            }, 'json');
        });

        // Item autocomplete
        $('#item').autocomplete({
            source: "<?= esc(site_url("$controller_name/itemSearch")) ?>",
            minChars: 0,
            delay: 15,
            cacheLength: 1,
            select: function(event, ui) {
                $('#item').val(ui.item.value);
                $('#quantity').focus().select();
                return false;
            }
        });

        $('#item').keypress(function(e) {
            if (e.which == 13) {
                $('#add_item_btn').click();
                return false;
            }
        });

        // Auto-save reference and comment
        $('#transfer_reference').on('blur', function() {
            $.post("<?= site_url("$controller_name/setReference"); ?>", withCsrf({
                transfer_reference: $(this).val()
            }));
        });

        $('#comment').on('blur', function() {
            $.post("<?= site_url("$controller_name/setComment"); ?>", withCsrf({
                comment: $(this).val()
            }));
        });

        $('#item').focus();
    });
</script>
