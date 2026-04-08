<?php
/**
 * Minimal layout (for modals, AJAX content, etc.)
 *
 * @var string $content Page content
 */

use Config\Services;

$request = Services::request();
?>

<?= $content ?? '' ?>
<?= $this->renderSection('content') ?>
