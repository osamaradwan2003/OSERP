<?php
/**
 * @var string $offer_date
 * @var string $customer_company
 * @var string $customer_attention
 * @var string $offer_description
 * @var array $items
 * @var float $total
 * @var string $assets_base
 * @var string $logo_src
 * @var string $watermark_src
 * @var string $whatsapp_src
 * @var array $selected_conditions
 */

$notes = [
    'العرض غير شامل الضريبة.',
    'غير شامل المجانس الترددي.',
    'غير شامل الكومبروسر.',
    'غير شامل النقل والتركيب.',
    'غير شامل غلاية البخار.',
    'العرض صالح لمدة 7 أيام من تاريخه.',
    'ضمان ما بعد البيع لمدة عام كامل مجانا ومدى الحياة مقابل أجر.',
    'لا يتم تسليم أي معدة قبل استيفاء المبلغ كاملا.',
    'الاستلام من أرض المصنع.',
];

$payment_terms = [
    '65% مقدم الدفعة.',
    '20% وسط المدة.',
    '15% مع التسليم من أرض المصنع.',
];

$logo_image = !empty($logo_src) ? $logo_src : ($assets_base . '/image3.png');
$watermark_image = !empty($watermark_src) ? $watermark_src : $logo_image;
$whatsapp_image = !empty($whatsapp_src) ? $whatsapp_src : ($assets_base . '/image2.jpeg');
?>

<style>
    @page {
        size: A4;
        margin: 16mm 14mm;
    }

    :root {
        --accent-green: #70ad47;
        --accent-blue: #2f5597;
        --accent-orange: #ed7d31;
        --table-header: #0f4c81;
        --table-alt: #f4f7fb;
        --table-border: #1f1f1f;
    }

    html, body {
        font-family: "Arial", "DejaVu Sans", sans-serif;
        direction: rtl;
        color: #1f1f1f;
        font-size: 14px;
    }

    .offer-page {
        width: 100%;
        background: #fff;
        border: 1px solid #e6e6e6;
        padding: 8mm 6mm;
        position: relative;
    }

    .watermark-layer {
        position: absolute;
        inset: 0;
        background-image: url('<?= esc($watermark_image, 'attr') ?>');
        background-repeat: no-repeat;
        background-position: center;
        background-size: 85%;
        opacity: 0.07;
        pointer-events: none;
        z-index: 0;
    }

    .offer-content {
        position: relative;
        z-index: 1;
    }

    .offer-title {
        text-align: center;
        font-size: 22px;
        font-weight: bold;
        color: var(--accent-green);
        text-decoration: underline;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }

    .offer-header {
        width: 100%;
        margin-bottom: 10px;
        direction: ltr;
    }

    .offer-header td {
        vertical-align: middle;
    }

    .offer-logo {
        width: 160px;
        text-align: left;
    }

    .offer-logo img {
        width: 130px;
        height: auto;
    }

    .offer-meta {
        direction: rtl;
        text-align: right;
        line-height: 1.8;
    }

    .offer-meta .label,
    .offer-meta .headline,
    .offer-description,
    .notes-title,
    .contact-label {
        color: var(--accent-blue);
    }

    .offer-meta .headline {
        margin-top: 6px;
        font-size: 18px;
        font-weight: bold;
    }

    .offer-description {
        margin-top: 4px;
        font-weight: bold;
    }

    .offer-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
        direction: rtl;
    }

    .offer-table th,
    .offer-table td {
        border: 1px solid var(--table-border);
        padding: 8px 6px;
        vertical-align: top;
    }

    .offer-table th {
        background: linear-gradient(90deg, #0f4c81, #1966a5);
        color: #fff;
        text-align: center;
        font-size: 16px;
    }

    .offer-table td {
        font-size: 14px;
    }

    .offer-table tbody tr:nth-child(odd) td {
        background: var(--table-alt);
    }

    .offer-table .col-index {
        width: 8%;
        text-align: center;
        font-weight: bold;
    }

    .offer-table .col-qty {
        width: 12%;
        text-align: center;
        font-weight: bold;
    }

    .offer-table .col-price {
        width: 20%;
        text-align: center;
        font-weight: bold;
    }

    .offer-table .total-row td {
        font-size: 18px;
        font-weight: bold;
    }

    .offer-table .total-label,
    .offer-table .total-value {
        background: #ffe28a;
        color: #2b2b2b;
        text-align: center;
    }

    .conditions-block {
        margin-top: 14px;
        border: 1px solid #e6e6e6;
        padding: 10px 12px;
        border-radius: 6px;
        background: #f9fbff;
    }

    .conditions-block h4 {
        margin: 0 0 8px;
        color: var(--accent-blue);
        font-weight: bold;
    }

    .condition-item {
        margin-bottom: 8px;
    }

    .condition-title {
        font-weight: bold;
        color: #2b2b2b;
    }

    .condition-desc {
        color: #444;
        margin-top: 2px;
    }

    .page-break {
        page-break-after: always;
    }

    .section-title {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        color: var(--accent-green);
        text-decoration: underline;
        margin: 0 0 12px;
    }

    .notes-title {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .notes-list,
    .payment-list {
        margin: 0 0 12px;
        padding: 0 18px 0 0;
    }

    .notes-list li,
    .payment-list li {
        margin-bottom: 6px;
    }

    .info-block {
        margin-bottom: 10px;
    }

    .contact-row {
        display: table;
        width: 100%;
    }

    .contact-row span {
        display: table-cell;
        padding-bottom: 4px;
    }

    .contact-label {
        width: 140px;
        font-weight: bold;
    }

    .whatsapp-icon {
        width: 18px;
        height: 18px;
        vertical-align: middle;
        margin-left: 6px;
    }

    @media print {
        html, body {
            font-size: 12pt;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .offer-page {
            border: none;
            padding: 0;
        }

        .offer-table th {
            background: linear-gradient(90deg, #0f4c81, #1966a5) !important;
            color: #fff !important;
        }

        .offer-table tbody tr:nth-child(odd) td {
            background: #f4f7fb !important;
        }

        .offer-table .total-label,
        .offer-table .total-value {
            background: #ffe28a !important;
            color: #2b2b2b !important;
        }
    }
</style>

<div class="offer-page">
    <div class="watermark-layer" aria-hidden="true"></div>
    <div class="offer-content">
        <div class="offer-title">صفحة العرض والتفاصيل</div>

        <table class="offer-header">
            <tr>
                <td class="offer-logo">
                    <?php if (!empty($logo_image)) { ?>
                        <img src="<?= esc($logo_image, 'attr') ?>" alt="logo">
                    <?php } ?>
                </td>
                <td>
                    <div class="offer-meta">
                        <div><span class="label">التاريخ:</span><?= esc($offer_date) ?></div>
                        <div><span class="label">الشركة:</span><?= esc($customer_company) ?></div>
                        <div><span class="label">عناية:</span><?= esc($customer_attention) ?></div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="offer-meta">
            <div class="headline">عرض سعر</div>
            <?php if (!empty($offer_description)) { ?>
                <div class="offer-description"><?= esc($offer_description) ?></div>
            <?php } ?>
        </div>

        <table class="offer-table">
            <thead>
            <tr>
                <th class="col-index">م</th>
                <th>الوصف</th>
                <th class="col-qty">الكمية</th>
                <th class="col-price">السعر</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($items)) { ?>
                <tr>
                    <td class="col-index">-</td>
                    <td>لا توجد بنود في عرض السعر.</td>
                    <td class="col-qty">-</td>
                    <td class="col-price">-</td>
                </tr>
            <?php } else { ?>
                <?php foreach ($items as $item) { ?>
                    <tr>
                        <td class="col-index"><?= esc($item['index']) ?></td>
                        <td><?= nl2br(esc($item['description'])) ?></td>
                        <td class="col-qty"><?= to_quantity_decimals($item['quantity']) ?></td>
                        <td class="col-price"><?= to_currency_no_money($item['price']) ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            <tr class="total-row">
                <td colspan="3" class="total-label">السعر</td>
                <td class="total-value"><?= to_currency_no_money($total) ?></td>
            </tr>
            </tbody>
        </table>

        <?php if (!empty($selected_conditions)) { ?>
            <div class="conditions-block">
                <h4>الشروط المطبقة</h4>
                <?php foreach ($selected_conditions as $condition) { ?>
                    <div class="condition-item">
                        <div class="condition-title"><?= esc($condition['title']) ?></div>
                        <div class="condition-desc"><?= $condition['description'] ?></div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>

<div class="page-break"></div>

<div class="offer-page">
    <div class="watermark-layer" aria-hidden="true"></div>
    <div class="offer-content">
        <div class="section-title">صفحة الشروط والتواصل</div>

        <div class="info-block">
            <div class="notes-title">ملحوظات</div>
            <ul class="notes-list">
                <?php foreach ($notes as $note) { ?>
                    <li><?= esc($note) ?></li>
                <?php } ?>
            </ul>
        </div>

        <div class="info-block">
            <div class="notes-title">نظام الدفع</div>
            <ul class="payment-list">
                <?php foreach ($payment_terms as $term) { ?>
                    <li><?= esc($term) ?></li>
                <?php } ?>
            </ul>
        </div>

        <div class="info-block">
            <div class="notes-title">زمن التصنيع</div>
            <div>زمن تصنيع يبدأ من وقت دفع العربون ويكون 30 يوم عمل.</div>
        </div>

        <div class="info-block">
            <div class="notes-title">مسؤولي الشركة</div>
            <div class="contact-row"><span class="contact-label">مدير الشركة:</span><span>ا / عبدالله سعودي</span></div>
            <div class="contact-row"><span class="contact-label">رئيس مجلس الإدارة:</span><span>م / صابر سعودي</span></div>
        </div>

        <div class="info-block">
            <div class="notes-title">للتواصل تليفونيا</div>
            <div class="contact-row">
                <span class="contact-label">ا / عبدالله:</span>
                <span>
                    <img class="whatsapp-icon" src="<?= esc($whatsapp_image, 'attr') ?>" alt="WhatsApp">
                    01050345202
                </span>
            </div>
        </div>

        <div class="info-block">
            <div class="notes-title">بيانات البريد والموقع</div>
            <div class="contact-row"><span class="contact-label">بريد إلكتروني:</span><span>egyptianexpert1@gmail.com</span></div>
            <div class="contact-row"><span class="contact-label">موقع الويب سايت:</span><span>www.expertindustryei.com</span></div>
            <div class="contact-row"><span class="contact-label">صفحة الفيسبوك:</span><span>Facebook</span></div>
        </div>
    </div>
</div>

