<?php
/**
 * Default page layout
 *
 * @var string $title Page title
 * @var string $content Page content
 * @var array $config Configuration array
 * @var object $user_info User information
 * @var array $allowed_modules Allowed modules
 */

use Config\Services;

$request = Services::request();
?>

<!doctype html>
<html lang="<?= $request->getLocale() ?>">

<head>
    <meta charset="utf-8">
    <base href="<?= base_url() ?>">
    <title><?= esc($config['company']) ?> | <?= lang('Common.powered_by') ?> OSPOS <?= esc(config('App')->application_version) ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" href="<?= 'resources/bootswatch/' . (empty($config['theme']) ? 'flatly' : esc($config['theme'])) . '/bootstrap.min.css' ?>">

    <?= view('partial/header_js') ?>
    <?= view('partial/lang_lines') ?>

    <style>
        html {
            overflow: auto;
        }
    </style>

    <?= $this->renderSection('head') ?>
</head>

<body>
    <div class="wrapper">
        <?= view('partial/header', ['user_info' => $user_info, 'allowed_modules' => $allowed_modules, 'config' => $config]) ?>

        <div class="container">
            <div class="row">
                <?= $this->renderSection('content') ?>
                <?= $content ?? '' ?>
            </div>
        </div>

        <?= view('partial/footer') ?>
    </div>

    <?= $this->renderSection('scripts') ?>
</body>

</html>
