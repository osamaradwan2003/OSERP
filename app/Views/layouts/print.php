<?php
/**
 * Print-optimized layout
 *
 * @var string $title Page title
 * @var string $content Page content
 * @var array $config Configuration array
 */

use Config\Services;

$request = Services::request();
?>

<!doctype html>
<html lang="<?= $request->getLocale() ?>">

<head>
    <meta charset="utf-8">
    <base href="<?= base_url() ?>">
    <title><?= esc($config['company']) ?> | <?= lang('Common.print') ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" href="css/receipt.css">
    <link rel="stylesheet" href="css/ospos_print.css">

    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }

        @media screen {
            body {
                background: #f5f5f5;
                padding: 20px;
            }
            .print-content {
                max-width: 300px;
                margin: 0 auto;
                background: white;
                padding: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
        }
    </style>
</head>

<body>
    <div class="print-content">
        <?= $content ?? '' ?>
        <?= $this->renderSection('content') ?>
    </div>

    <script type="text/javascript">
        // Auto-print on load if requested
        if (window.location.search.indexOf('print=1') !== -1) {
            window.onload = function() {
                window.print();
            };
        }
    </script>
</body>

</html>
