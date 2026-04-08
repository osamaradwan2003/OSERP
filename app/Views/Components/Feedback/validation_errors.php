<?php
/**
 * Reusable validation errors display component
 *
 * @var array $errors Validation errors array
 * @var array $options Additional options:
 *   - class: string Additional CSS classes
 *   - id: string Container ID
 *   - title: string Error title (translation key)
 *   - icon: string Glyphicon class (default: 'exclamation-sign')
 *   - dismissible: bool Whether errors can be dismissed
 */

$defaults = [
    'class' => '',
    'id' => 'error_message_box',
    'title' => null,
    'icon' => 'exclamation-sign',
    'dismissible' => false,
];
$options = array_merge($defaults, $options ?? []);

// Support both $errors array and $validation object
$errors = $errors ?? [];
if (isset($validation) && !empty($validation)) {
    $errors = $validation->getErrors();
}
?>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger <?= $options['class'] ?><?= $options['dismissible'] ? ' alert-dismissible' : '' ?>"
     role="alert"<?= $options['id'] ? ' id="' . $options['id'] . '"' : '' ?>>
    <?php if ($options['dismissible']): ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="<?= lang('Common.close') ?>">
        <span aria-hidden="true">&times;</span>
    </button>
    <?php endif; ?>

    <?php if ($options['title']): ?>
    <strong>
        <?php if ($options['icon']): ?>
        <span class="glyphicon glyphicon-<?= $options['icon'] ?>" aria-hidden="true"></span>&nbsp;
        <?php endif; ?>
        <?= lang($options['title']) ?>
    </strong>
    <?php endif; ?>

    <?php if (is_array($errors)): ?>
    <?php if (count($errors) === 1): ?>
    <p><?= reset($errors) ?></p>
    <?php else: ?>
    <ul class="list-unstyled" style="margin-bottom: 0;">
        <?php foreach ($errors as $error): ?>
        <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <?php else: ?>
    <?= $errors ?>
    <?php endif; ?>
</div>
<?php endif; ?>
