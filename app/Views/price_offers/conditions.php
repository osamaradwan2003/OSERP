<?php
/**
 * @var array $conditions
 */
?>

<?= view('partial/header') ?>

<div id="title_bar" class="btn-toolbar" style="margin-bottom: 12px;">
    <?= anchor('price_offers', '<span class="glyphicon glyphicon-chevron-left">&nbsp;</span>' . lang('Common.back'), ['class' => 'btn btn-default btn-sm pull-right']) ?>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><strong>Conditions</strong></div>
    <div class="panel-body">
        <?= form_open('price_offers/save_condition') ?>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <?= form_label('Name', 'name') ?>
                    <?= form_input(['name' => 'name', 'id' => 'name', 'class' => 'form-control input-sm', 'required' => true]) ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?= form_label('Title', 'title') ?>
                    <?= form_input(['name' => 'title', 'id' => 'title', 'class' => 'form-control input-sm', 'required' => true]) ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?= form_label('Description', 'description') ?>
                    <?= form_textarea(['name' => 'description', 'id' => 'description', 'class' => 'form-control input-sm hidden', 'rows' => 5]) ?> <!-- Hidden textarea to store Quill content -->
                    <div id="description_editor" style="height: 220px;"></div>
                    <small class="text-muted">Rich text enabled.</small>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <?= form_label('Sort', 'sort') ?>
                    <?= form_input(['name' => 'sort', 'id' => 'sort', 'class' => 'form-control input-sm', 'value' => '0']) ?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <?= form_label('Active', 'is_active') ?>
                    <div>
                        <?= form_checkbox(['name' => 'is_active', 'id' => 'is_active', 'value' => '1', 'checked' => true]) ?>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn-info btn-sm"><span class="glyphicon glyphicon-plus"></span> Add Condition</button>
        <?= form_close() ?>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Title</th>
                <th>Description</th>
                <th>Sort</th>
                <th>Active</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($conditions)) { ?>
                <tr><td colspan="6">No conditions found.</td></tr>
            <?php } else { ?>
                <?php foreach ($conditions as $condition) { ?>
                    <tr>
                        <td><?= esc($condition['name']) ?></td>
                        <td><?= esc($condition['title']) ?></td>
                        <td><?= $condition['description'] ?></td>
                        <td><?= esc($condition['sort']) ?></td>
                        <td><?= $condition['is_active'] ? 'Yes' : 'No' ?></td>
                        <td>
                            <?= form_open('price_offers/delete_condition', ['style' => 'display:inline']) ?>
                            <?= form_hidden('id', $condition['id']) ?>
                            <button class="btn btn-danger btn-xs">
                                <span class="glyphicon glyphicon-trash"></span> Delete
                            </button>
                            <?= form_close() ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
</div>


<script type="text/javascript">
    const quill = new Quill('#description_editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['clean']
            ]
        }
    });

    const descriptionInput = document.getElementById('description');
    const form = document.querySelector('form[action*="price_offers/save_condition"]');

    if (form) {
        form.addEventListener('submit', function () {
            descriptionInput.value = quill.root.innerHTML;
        });
    }

    const editor = document.querySelector('#description_editor .ql-editor');
    if (editor) {
        editor.style.direction = 'rtl';
        editor.style.textAlign = 'right';
        editor.style.fontFamily = 'Arial, "DejaVu Sans", sans-serif';
        editor.style.fontSize = '14px';
    }

</script>



<?= view('partial/footer') ?>
