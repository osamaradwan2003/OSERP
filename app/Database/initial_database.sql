-- =====================================================
-- OSPOS Complete Initial Database Schema
-- This file consolidates all migrations into one file
-- For fresh installation only - do not run on existing databases
-- =====================================================
-- Disable foreign key checks
SET FOREIGN_KEY_CHECKS = 0;
-- Drop tables if they exist (in reverse order of dependencies)
DROP TABLE IF EXISTS `ospos_manufacturing_stock_transfer_items`;
DROP TABLE IF EXISTS `ospos_manufacturing_stock_transfers`;
DROP TABLE IF EXISTS `ospos_manufacturing_labor_entries`;
DROP TABLE IF EXISTS `ospos_manufacturing_project_costs`;
DROP TABLE IF EXISTS `ospos_manufacturing_project_stages`;
DROP TABLE IF EXISTS `ospos_manufacturing_projects`;
DROP TABLE IF EXISTS `ospos_manufacturing_overhead_rates`;
DROP TABLE IF EXISTS `ospos_stock_transfers_items`;
DROP TABLE IF EXISTS `ospos_stock_transfers`;
DROP TABLE IF EXISTS `ospos_price_offer_condition_links`;
DROP TABLE IF EXISTS `ospos_price_offer_conditions`;
DROP TABLE IF EXISTS `ospos_attribute_links`;
DROP TABLE IF EXISTS `ospos_attribute_values`;
DROP TABLE IF EXISTS `ospos_attribute_definitions`;
DROP TABLE IF EXISTS `ospos_sales_reward_points`;
DROP TABLE IF EXISTS `ospos_customers_points`;
DROP TABLE IF EXISTS `ospos_customers_packages`;
DROP TABLE IF EXISTS `ospos_sales_payments`;
DROP TABLE IF EXISTS `ospos_sales_taxes`;
DROP TABLE IF EXISTS `ospos_sales_items_taxes`;
DROP TABLE IF EXISTS `ospos_sales_items`;
DROP TABLE IF EXISTS `ospos_sales`;
DROP TABLE IF EXISTS `ospos_receivings_items`;
DROP TABLE IF EXISTS `ospos_receivings`;
DROP TABLE IF EXISTS `ospos_item_quantities`;
DROP TABLE IF EXISTS `ospos_inventory`;
DROP TABLE IF EXISTS `ospos_item_kit_items`;
DROP TABLE IF EXISTS `ospos_item_kits`;
DROP TABLE IF EXISTS `ospos_items_taxes`;
DROP TABLE IF EXISTS `ospos_items`;
DROP TABLE IF EXISTS `ospos_giftcards`;
DROP TABLE IF EXISTS `ospos_grants`;
DROP TABLE IF EXISTS `ospos_permissions`;
DROP TABLE IF EXISTS `ospos_stock_locations`;
DROP TABLE IF EXISTS `ospos_dinner_tables`;
DROP TABLE IF EXISTS `ospos_tax_rates`;
DROP TABLE IF EXISTS `ospos_tax_jurisdictions`;
DROP TABLE IF EXISTS `ospos_tax_code_rates`;
DROP TABLE IF EXISTS `ospos_tax_codes`;
DROP TABLE IF EXISTS `ospos_tax_categories`;
DROP TABLE IF EXISTS `ospos_customers`;
DROP TABLE IF EXISTS `ospos_employees`;
DROP TABLE IF EXISTS `ospos_suppliers`;
DROP TABLE IF EXISTS `ospos_people`;
DROP TABLE IF EXISTS `ospos_modules`;
DROP TABLE IF EXISTS `ospos_app_config`;
DROP TABLE IF EXISTS `ospos_sessions`;
DROP TABLE IF EXISTS `ospos_cashflow_entries`;
DROP TABLE IF EXISTS `ospos_cashflow_category_types`;
DROP TABLE IF EXISTS `ospos_cashflow_attachments`;
DROP TABLE IF EXISTS `ospos_cashflow_accounts`;
DROP TABLE IF EXISTS `ospos_cashflow_categories`;
DROP TABLE IF EXISTS `ospos_employee_shifts`;
DROP TABLE IF EXISTS `ospos_salary_periods`;
DROP TABLE IF EXISTS `ospos_salary_components`;
DROP TABLE IF EXISTS `ospos_employee_leave_balances`;
DROP TABLE IF EXISTS `ospos_leave_requests`;
DROP TABLE IF EXISTS `ospos_leave_types`;
DROP TABLE IF EXISTS `ospos_attendance`;
DROP TABLE IF EXISTS `ospos_employee_salary_rules`;
DROP TABLE IF EXISTS `ospos_salary_rules`;
DROP TABLE IF EXISTS `ospos_salary_rule_groups`;
DROP TABLE IF EXISTS `ospos_emergency_contacts`;
DROP TABLE IF EXISTS `ospos_employee_profiles`;
DROP TABLE IF EXISTS `ospos_employee_attachments`;
DROP TABLE IF EXISTS `ospos_shifts`;
DROP TABLE IF EXISTS `ospos_positions`;
DROP TABLE IF EXISTS `ospos_departments`;
-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;
-- =====================================================
-- Table: ospos_app_config
-- =====================================================
CREATE TABLE `ospos_app_config` (
    `key` varchar(50) NOT NULL,
    `value` varchar(500) NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
INSERT INTO `ospos_app_config` (`key`, `value`)
VALUES ('address', '123 Nowhere street'),
    ('company', 'Open Source Point of Sale'),
    ('default_tax_rate', '8'),
    ('email', 'changeme@example.com'),
    ('fax', ''),
    ('phone', '555-555-5555'),
    ('return_policy', 'Test'),
    ('timezone', 'America/New_York'),
    ('website', ''),
    ('company_logo', ''),
    ('tax_included', '0'),
    ('barcode_content', 'id'),
    ('barcode_type', 'Code39'),
    ('barcode_width', '250'),
    ('barcode_height', '50'),
    ('barcode_quality', '100'),
    ('barcode_font', 'Arial'),
    ('barcode_font_size', '10'),
    ('barcode_first_row', 'category'),
    ('barcode_second_row', 'item_code'),
    ('barcode_third_row', 'unit_price'),
    ('barcode_num_in_row', '2'),
    ('barcode_page_width', '100'),
    ('barcode_page_cellspacing', '20'),
    ('barcode_generate_if_empty', '0'),
    ('receipt_show_taxes', '0'),
    ('receipt_show_total_discount', '1'),
    ('receipt_show_description', '1'),
    ('receipt_show_serialnumber', '1'),
    ('invoice_enable', '1'),
    ('recv_invoice_format', '$CO'),
    ('sales_invoice_format', '$CO'),
    (
        'invoice_email_message',
        'Dear $CU, In attachment the receipt for sale $INV'
    ),
    (
        'invoice_default_comments',
        'This is a default comment'
    ),
    (
        'quote_default_comments',
        'This is a default quote comment'
    ),
    ('print_silently', '1'),
    ('print_header', '0'),
    ('print_footer', '0'),
    ('print_top_margin', '0'),
    ('print_left_margin', '0'),
    ('print_bottom_margin', '0'),
    ('print_right_margin', '0'),
    ('default_sales_discount', '0'),
    ('default_sales_discount_type', '0'),
    ('default_receivings_discount', '0'),
    ('default_receivings_discount_type', '0'),
    ('lines_per_page', '25'),
    ('dateformat', 'm/d/Y'),
    ('timeformat', 'H:i:s'),
    ('currency_symbol', '$'),
    ('currency_code', ''),
    ('number_locale', 'en_US'),
    ('thousands_separator', '1'),
    ('currency_decimals', '2'),
    ('tax_decimals', '2'),
    ('quantity_decimals', '0'),
    ('cash_decimals', '2'),
    ('cash_rounding_code', '0'),
    ('country_codes', 'us'),
    ('msg_msg', ''),
    ('msg_uid', ''),
    ('msg_src', ''),
    ('msg_pwd', ''),
    ('notify_horizontal_position', 'center'),
    ('notify_vertical_position', 'bottom'),
    ('payment_options_order', 'cashdebitcredit'),
    ('protocol', 'sendmail'),
    ('mailpath', '/usr/bin/sendmail'),
    ('smtp_port', '587'),
    ('smtp_timeout', '5000'),
    ('smtp_crypto', 'tls'),
    ('receipt_template', 'receipt_default'),
    ('theme', 'flatly'),
    ('statistics', '1'),
    ('language', 'english'),
    ('language_code', 'en'),
    ('date_or_time_format', ''),
    ('sales_quote_format', 'Q%y{QSEQ:6}'),
    ('default_register_mode', 'sale'),
    ('last_used_invoice_number', '0'),
    ('last_used_quote_number', '0'),
    ('line_sequence', '0'),
    ('dinner_table_enable', '0'),
    ('customer_sales_tax_support', '0'),
    ('customer_reward_enable', '0'),
    ('default_origin_tax_code', ''),
    ('use_destination_based_tax', '0'),
    ('default_tax_code', ''),
    ('default_tax_category', 'Standard'),
    ('default_tax_jurisdiction', ''),
    ('default_tax_1_name', ''),
    ('default_tax_1_rate', ''),
    ('default_tax_2_name', ''),
    ('default_tax_2_rate', ''),
    ('tax_id', ''),
    ('receipt_font_size', '12'),
    ('receipt_show_company_name', '1'),
    ('receipt_show_tax_ind', '0'),
    ('financial_year', '1'),
    ('giftcard_number', 'series'),
    ('gcaptcha_enable', '0'),
    ('gcaptcha_secret_key', ''),
    ('gcaptcha_site_key', ''),
    ('barcode_formats', '[]'),
    ('work_order_enable', '0'),
    ('work_order_format', 'W%y{WSEQ:6}'),
    ('last_used_work_order_number', '0'),
    ('suggestions_first_column', 'name'),
    ('suggestions_second_column', ''),
    ('suggestions_third_column', ''),
    ('allow_duplicate_barcodes', '0'),
    ('derive_sale_quantity', '0'),
    ('email_receipt_check_behaviour', 'last'),
    ('print_receipt_check_behaviour', 'last'),
    ('print_delay_autoreturn', '0'),
    ('multi_pack_enabled', '0'),
    ('enforce_privacy', '0'),
    ('include_hsn', '0'),
    ('invoice_type', 'invoice'),
    ('image_allowed_types', 'gif|jpg|png'),
    ('image_max_height', '480'),
    ('image_max_size', '128'),
    ('image_max_width', '640'),
    ('login_form', 'floating_labels'),
    ('account_number', ''),
    ('category_dropdown', ''),
    ('smtp_host', ''),
    ('smtp_user', ''),
    ('smtp_pass', ''),
    ('receiving_calculate_average_price', '0'),
    ('payment_message', ''),
    ('show_office_group', '0');
-- =====================================================
-- Table: ospos_people
-- =====================================================
CREATE TABLE `ospos_people` (
    `person_id` int(10) NOT NULL AUTO_INCREMENT,
    `first_name` varchar(255) NOT NULL,
    `last_name` varchar(255) NOT NULL,
    `gender` int(1) DEFAULT NULL,
    `phone_number` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `address_1` varchar(255) NOT NULL,
    `address_2` varchar(255) NOT NULL,
    `city` varchar(255) NOT NULL,
    `state` varchar(255) NOT NULL,
    `zip` varchar(255) NOT NULL,
    `country` varchar(255) NOT NULL,
    `comments` text NOT NULL,
    PRIMARY KEY (`person_id`),
    INDEX `email` (`email`),
    INDEX `first_last_email_phone` (
        `first_name`,
        `last_name`,
        `email`,
        `phone_number`
    )
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
INSERT INTO `ospos_people` (
        `first_name`,
        `last_name`,
        `phone_number`,
        `email`,
        `address_1`,
        `address_2`,
        `city`,
        `state`,
        `zip`,
        `country`,
        `comments`,
        `person_id`
    )
VALUES (
        'John',
        'Doe',
        '555-555-5555',
        'changeme@example.com',
        'Address 1',
        '',
        '',
        '',
        '',
        '',
        '',
        1
    );
-- =====================================================
-- Table: ospos_modules
-- =====================================================
CREATE TABLE `ospos_modules` (
    `name_lang_key` varchar(255) NOT NULL,
    `desc_lang_key` varchar(255) NOT NULL,
    `sort` int(10) NOT NULL,
    `module_id` varchar(255) NOT NULL,
    PRIMARY KEY (`module_id`),
    UNIQUE KEY `desc_lang_key` (`desc_lang_key`),
    UNIQUE KEY `name_lang_key` (`name_lang_key`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
INSERT INTO `ospos_modules` (
        `name_lang_key`,
        `desc_lang_key`,
        `sort`,
        `module_id`
    )
VALUES (
        'module_config',
        'module_config_desc',
        900,
        'config'
    ),
    (
        'module_customers',
        'module_customers_desc',
        10,
        'customers'
    ),
    (
        'module_employees',
        'module_employees_desc',
        80,
        'employees'
    ),
    (
        'module_giftcards',
        'module_giftcards_desc',
        90,
        'giftcards'
    ),
    ('module_items', 'module_items_desc', 20, 'items'),
    (
        'module_item_kits',
        'module_item_kits_desc',
        30,
        'item_kits'
    ),
    (
        'module_messages',
        'module_messages_desc',
        98,
        'messages'
    ),
    (
        'module_receivings',
        'module_receivings_desc',
        60,
        'receivings'
    ),
    (
        'module_reports',
        'module_reports_desc',
        50,
        'reports'
    ),
    ('module_sales', 'module_sales_desc', 70, 'sales'),
    (
        'module_suppliers',
        'module_suppliers_desc',
        40,
        'suppliers'
    ),
    (
        'module_taxes',
        'module_taxes_desc',
        105,
        'taxes'
    ),
(
'module_cashflow',
'module_cashflow_desc',
110,
'cashflow'
),
    (
        'module_attributes',
        'module_attributes_desc',
        107,
        'attributes'
    ),
    (
        'module_office',
        'module_office_desc',
        999,
        'office'
    ),
    ('module_home', 'module_home_desc', 1, 'home'),
    (
        'price_offers',
        'price_offers_desc',
        75,
        'price_offers'
    ),
    (
        'module_transfers',
        'module_transfers_desc',
        25,
        'transfers'
    ),
    (
        'manufacturing',
        'manufacturing_desc',
        45,
        'manufacturing'
    ),
    ('Module.hr', 'Module.hr_desc', 85, 'hr');
-- =====================================================
-- Table: ospos_permissions
-- =====================================================
CREATE TABLE `ospos_permissions` (
    `permission_id` varchar(255) NOT NULL,
    `module_id` varchar(255) NOT NULL,
    `location_id` int(10) DEFAULT NULL,
    PRIMARY KEY (`permission_id`),
    KEY `module_id` (`module_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
INSERT INTO `ospos_permissions` (`permission_id`, `module_id`)
VALUES ('reports_customers', 'reports'),
    ('reports_receivings', 'reports'),
    ('reports_items', 'reports'),
    ('reports_employees', 'reports'),
    ('reports_suppliers', 'reports'),
    ('reports_sales', 'reports'),
    ('reports_discounts', 'reports'),
    ('reports_taxes', 'reports'),
    ('reports_inventory', 'reports'),
    ('reports_categories', 'reports'),
    ('reports_payments', 'reports'),
    ('customers', 'customers'),
    ('employees', 'employees'),
    ('giftcards', 'giftcards'),
    ('items', 'items'),
    ('item_kits', 'item_kits'),
    ('messages', 'messages'),
    ('receivings', 'receivings'),
    ('reports', 'reports'),
    ('sales', 'sales'),
    ('sales_delete', 'sales'),
    ('config', 'config'),
    ('suppliers', 'suppliers'),
('taxes', 'taxes'),
('cashflow', 'cashflow'),
('cashflow_manage_categories', 'cashflow'),
('cashflow_manage_accounts', 'cashflow'),
('cashflow_manage_entries', 'cashflow'),
('attributes', 'attributes'),
    ('office', 'office'),
    ('home', 'home'),
    ('price_offers', 'price_offers'),
    ('transfers', 'transfers'),
    ('manufacturing', 'manufacturing'),
    ('manufacturing_projects', 'manufacturing'),
    ('manufacturing_projects_add', 'manufacturing'),
    ('manufacturing_projects_edit', 'manufacturing'),
    ('manufacturing_projects_delete', 'manufacturing'),
    ('manufacturing_transfers', 'manufacturing'),
    ('manufacturing_transfers_add', 'manufacturing'),
    (
        'manufacturing_transfers_confirm',
        'manufacturing'
    ),
    ('manufacturing_labor', 'manufacturing'),
    ('manufacturing_labor_add', 'manufacturing'),
    ('manufacturing_reports', 'manufacturing'),
    ('manufacturing_reports_costs', 'manufacturing'),
    ('manufacturing_reports_mrp', 'manufacturing'),
    ('hr', 'hr'),
    ('hr_dashboard', 'hr'),
    ('hr_departments', 'hr'),
    ('hr_positions', 'hr'),
    ('hr_shifts', 'hr'),
    ('hr_profiles', 'hr'),
    ('hr_salary_rules', 'hr'),
    ('hr_calculate', 'hr'),
    ('hr_attendance', 'hr'),
    ('hr_leave', 'hr');
INSERT INTO `ospos_permissions` (`permission_id`, `module_id`, `location_id`)
VALUES ('items_stock', 'items', 1),
    ('sales_stock', 'sales', 1),
    ('receivings_stock', 'receivings', 1);
-- =====================================================
-- Table: ospos_grants
-- =====================================================
CREATE TABLE `ospos_grants` (
    `permission_id` varchar(255) NOT NULL,
    `person_id` int(10) NOT NULL,
    `menu_group` varchar(32) DEFAULT 'home',
    PRIMARY KEY (`permission_id`, `person_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
INSERT INTO `ospos_grants` (`permission_id`, `person_id`, `menu_group`)
VALUES ('reports_customers', 1, 'home'),
    ('reports_receivings', 1, 'home'),
    ('reports_items', 1, 'home'),
    ('reports_inventory', 1, 'home'),
    ('reports_employees', 1, 'home'),
    ('reports_suppliers', 1, 'home'),
    ('reports_sales', 1, 'home'),
    ('reports_discounts', 1, 'home'),
    ('reports_taxes', 1, 'home'),
    ('reports_categories', 1, 'home'),
    ('reports_payments', 1, 'home'),
    ('customers', 1, 'home'),
    ('employees', 1, 'office'),
    ('giftcards', 1, 'home'),
    ('items', 1, 'home'),
    ('item_kits', 1, 'home'),
    ('messages', 1, 'home'),
    ('receivings', 1, 'home'),
    ('reports', 1, 'home'),
    ('sales', 1, 'home'),
    ('sales_delete', 1, '--'),
    ('config', 1, 'office'),
    ('items_stock', 1, 'home'),
    ('sales_stock', 1, 'home'),
    ('receivings_stock', 1, 'home'),
    ('suppliers', 1, 'home'),
('taxes', 1, 'office'),
('cashflow', 1, 'home'),
('cashflow_manage_categories', 1, 'home'),
('cashflow_manage_accounts', 1, 'home'),
('cashflow_manage_entries', 1, 'home'),
('attributes', 1, 'office'),
    ('office', 1, 'home'),
    ('home', 1, 'office'),
    ('price_offers', 1, 'home'),
    ('transfers', 1, 'home'),
    ('manufacturing', 1, 'home'),
    ('manufacturing_projects', 1, 'home'),
    ('manufacturing_projects_add', 1, 'home'),
    ('manufacturing_projects_edit', 1, 'home'),
    ('manufacturing_projects_delete', 1, 'home'),
    ('manufacturing_transfers', 1, 'home'),
    ('manufacturing_transfers_add', 1, 'home'),
    ('manufacturing_transfers_confirm', 1, 'home'),
    ('manufacturing_labor', 1, 'home'),
    ('manufacturing_labor_add', 1, 'home'),
    ('manufacturing_reports', 1, 'home'),
    ('manufacturing_reports_costs', 1, 'home'),
    ('manufacturing_reports_mrp', 1, 'home'),
    ('hr', 1, 'office'),
    ('hr_dashboard', 1, 'office'),
    ('hr_departments', 1, 'office'),
    ('hr_positions', 1, 'office'),
    ('hr_shifts', 1, 'office'),
    ('hr_profiles', 1, 'office'),
    ('hr_salary_rules', 1, 'office'),
    ('hr_calculate', 1, 'office'),
    ('hr_attendance', 1, 'office'),
    ('hr_leave', 1, 'office');
-- =====================================================
-- Table: ospos_employees
-- =====================================================
CREATE TABLE `ospos_employees` (
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `person_id` int(10) NOT NULL,
    `deleted` tinyint(1) NOT NULL DEFAULT '0',
    `hash_version` tinyint(1) NOT NULL DEFAULT '2',
    `language` varchar(48) DEFAULT NULL,
    `language_code` varchar(8) DEFAULT NULL,
    PRIMARY KEY `person_id` (`person_id`),
    UNIQUE KEY `username` (`username`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
INSERT INTO `ospos_employees` (
        `username`,
        `password`,
        `person_id`,
        `deleted`,
        `hash_version`
    )
VALUES (
        'admin',
        '$2y$10$vJBSMlD02EC7ENSrKfVQXuvq9tNRHMtcOA8MSK2NYS748HHWm.gcG',
        1,
        0,
        2
    );
-- =====================================================
-- Table: ospos_suppliers
-- =====================================================
CREATE TABLE `ospos_suppliers` (
    `person_id` int(10) NOT NULL,
    `company_name` varchar(255) NOT NULL,
    `agency_name` varchar(255) NOT NULL,
    `account_number` varchar(255) DEFAULT NULL,
    `tax_id` varchar(255) DEFAULT NULL,
    `deleted` tinyint(1) NOT NULL DEFAULT '0',
    `category` tinyint(1) NOT NULL,
    PRIMARY KEY `person_id` (`person_id`),
    UNIQUE KEY `account_number` (`account_number`),
    INDEX `category` (`category`),
    INDEX `company_name_deleted` (`company_name`, `deleted`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_customers
-- =====================================================
CREATE TABLE `ospos_customers` (
    `person_id` int(10) NOT NULL,
    `company_name` varchar(255) DEFAULT NULL,
    `account_number` varchar(255) DEFAULT NULL,
    `tax_id` varchar(255) DEFAULT NULL,
    `taxable` tinyint(1) NOT NULL DEFAULT '1',
    `discount` decimal(15, 2) NOT NULL DEFAULT '0',
    `discount_type` tinyint(1) NOT NULL DEFAULT '0',
    `deleted` tinyint(1) NOT NULL DEFAULT '0',
    `package_id` int(11) DEFAULT NULL,
    `points` int(11) DEFAULT NULL,
    `sales_tax_code_id` int(10) DEFAULT NULL,
    `consent` tinyint(1) NOT NULL DEFAULT '0',
    `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `employee_id` int(10) NOT NULL DEFAULT '0',
    PRIMARY KEY `person_id` (`person_id`),
    UNIQUE KEY `account_number` (`account_number`),
    KEY `package_id` (`package_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_customers_packages
-- =====================================================
CREATE TABLE `ospos_customers_packages` (
    `package_id` int(11) NOT NULL AUTO_INCREMENT,
    `package_name` varchar(255) DEFAULT NULL,
    `points_percent` float NOT NULL DEFAULT '0',
    `deleted` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`package_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci AUTO_INCREMENT = 1;
INSERT INTO `ospos_customers_packages` (
        `package_id`,
        `package_name`,
        `points_percent`,
        `deleted`
    )
VALUES (1, 'Default', 0, 0),
    (2, 'Bronze', 10, 0),
    (3, 'Silver', 20, 0),
    (4, 'Gold', 30, 0),
    (5, 'Premium', 50, 0);
-- =====================================================
-- Table: ospos_customers_points
-- =====================================================
CREATE TABLE `ospos_customers_points` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `person_id` int(11) NOT NULL,
    `package_id` int(11) NOT NULL,
    `sale_id` int(11) NOT NULL,
    `points_earned` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `person_id` (`person_id`),
    KEY `package_id` (`package_id`),
    KEY `sale_id` (`sale_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci AUTO_INCREMENT = 1;
-- =====================================================
-- Table: ospos_giftcards
-- =====================================================
CREATE TABLE `ospos_giftcards` (
    `record_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `giftcard_id` int(11) NOT NULL AUTO_INCREMENT,
    `giftcard_number` varchar(255) NULL,
    `value` decimal(15, 2) NOT NULL,
    `deleted` tinyint(1) NOT NULL DEFAULT '0',
    `person_id` int(10) DEFAULT NULL,
    PRIMARY KEY (`giftcard_id`),
    UNIQUE KEY `giftcard_number` (`giftcard_number`),
    KEY `person_id` (`person_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_items
-- =====================================================
CREATE TABLE `ospos_items` (
    `name` varchar(255) NOT NULL,
    `category` varchar(255) NOT NULL,
    `supplier_id` int(11) DEFAULT NULL,
    `item_number` varchar(255) DEFAULT NULL,
    `description` varchar(255) NOT NULL,
    `cost_price` decimal(15, 2) NOT NULL,
    `unit_price` decimal(15, 2) NOT NULL,
    `reorder_level` decimal(15, 3) NOT NULL DEFAULT '0',
    `receiving_quantity` decimal(15, 3) NOT NULL DEFAULT '1',
    `item_id` int(10) NOT NULL AUTO_INCREMENT,
    `pic_filename` varchar(255) DEFAULT NULL,
    `allow_alt_description` tinyint(1) NOT NULL,
    `is_serialized` tinyint(1) NOT NULL,
    `deleted` tinyint(1) NOT NULL DEFAULT '0',
    `stock_type` tinyint(1) NOT NULL DEFAULT 0,
    `item_type` tinyint(1) NOT NULL DEFAULT 0,
    `tax_category_id` int(10) DEFAULT NULL,
    `qty_per_pack` decimal(15, 3) NOT NULL DEFAULT 1,
    `pack_name` varchar(8) DEFAULT 'Each',
    `low_sell_item_id` int(10) DEFAULT 0,
    `hsn_code` varchar(20) DEFAULT NULL,
    PRIMARY KEY (`item_id`),
    KEY `item_number` (`item_number`),
    KEY `supplier_id` (`supplier_id`),
    INDEX `deleted_item_type` (`deleted`, `item_type`),
    INDEX `item_id_deleted` (`item_id`, `deleted`),
    UNIQUE INDEX `items_uq1` (`supplier_id`, `item_id`, `deleted`, `item_type`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_items_taxes
-- =====================================================
CREATE TABLE `ospos_items_taxes` (
    `item_id` int(10) NOT NULL,
    `name` varchar(255) NOT NULL,
    `percent` decimal(15, 3) NOT NULL,
    PRIMARY KEY (`item_id`, `name`, `percent`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_item_kits
-- =====================================================
CREATE TABLE `ospos_item_kits` (
    `item_kit_id` int(11) NOT NULL AUTO_INCREMENT,
    `item_kit_number` varchar(255) DEFAULT NULL,
    `name` varchar(255) NOT NULL,
    `description` varchar(255) NOT NULL,
    `item_id` int(10) NOT NULL DEFAULT 0,
    `kit_discount` decimal(15, 2) NOT NULL DEFAULT 0,
    `kit_discount_type` tinyint(1) NOT NULL DEFAULT 0,
    `price_option` tinyint(1) NOT NULL DEFAULT 0,
    `print_option` tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`item_kit_id`),
    INDEX `name_description` (`name`, `description`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_item_kit_items
-- =====================================================
CREATE TABLE `ospos_item_kit_items` (
    `item_kit_id` int(11) NOT NULL,
    `item_id` int(11) NOT NULL,
    `quantity` decimal(15, 3) NOT NULL,
    `kit_sequence` int(3) NOT NULL DEFAULT 0,
    PRIMARY KEY (`item_kit_id`, `item_id`, `quantity`),
    KEY `item_id` (`item_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_stock_locations
-- =====================================================
CREATE TABLE `ospos_stock_locations` (
    `location_id` int(11) NOT NULL AUTO_INCREMENT,
    `location_name` varchar(255) DEFAULT NULL,
    `deleted` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`location_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
INSERT INTO `ospos_stock_locations` (`deleted`, `location_name`)
VALUES (0, 'stock');
-- =====================================================
-- Table: ospos_item_quantities
-- =====================================================
CREATE TABLE `ospos_item_quantities` (
    `item_id` int(11) NOT NULL,
    `location_id` int(11) NOT NULL,
    `quantity` decimal(15, 3) NOT NULL DEFAULT '0',
    PRIMARY KEY (`item_id`, `location_id`),
    KEY `item_id` (`item_id`),
    KEY `location_id` (`location_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_inventory
-- =====================================================
CREATE TABLE `ospos_inventory` (
    `trans_id` int(11) NOT NULL AUTO_INCREMENT,
    `trans_items` int(11) NOT NULL DEFAULT '0',
    `trans_user` int(11) NOT NULL DEFAULT '0',
    `trans_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `trans_comment` text NOT NULL,
    `trans_location` int(11) NOT NULL,
    `trans_inventory` decimal(15, 3) NOT NULL DEFAULT '0',
    PRIMARY KEY (`trans_id`),
    KEY `trans_items` (`trans_items`),
    KEY `trans_user` (`trans_user`),
    KEY `trans_location` (`trans_location`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_receivings
-- =====================================================
CREATE TABLE `ospos_receivings` (
    `receiving_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `supplier_id` int(10) DEFAULT NULL,
    `employee_id` int(10) NOT NULL DEFAULT '0',
    `comment` text NOT NULL,
    `receiving_id` int(10) NOT NULL AUTO_INCREMENT,
    `payment_type` varchar(20) DEFAULT NULL,
    `reference` varchar(32) DEFAULT NULL,
    PRIMARY KEY (`receiving_id`),
    KEY `supplier_id` (`supplier_id`),
    KEY `employee_id` (`employee_id`),
    KEY `reference` (`reference`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_receivings_items
-- =====================================================
CREATE TABLE `ospos_receivings_items` (
    `receiving_id` int(10) NOT NULL DEFAULT '0',
    `item_id` int(10) NOT NULL DEFAULT '0',
    `description` varchar(30) DEFAULT NULL,
    `serialnumber` varchar(30) DEFAULT NULL,
    `line` int(3) NOT NULL,
    `quantity_purchased` decimal(15, 3) NOT NULL DEFAULT '0',
    `item_cost_price` decimal(15, 2) NOT NULL,
    `item_unit_price` decimal(15, 2) NOT NULL,
    `discount` decimal(15, 2) NOT NULL DEFAULT '0',
    `discount_type` tinyint(1) NOT NULL DEFAULT 0,
    `item_location` int(11) NOT NULL,
    `receiving_quantity` decimal(15, 3) NOT NULL DEFAULT '1',
    PRIMARY KEY (`receiving_id`, `item_id`, `line`),
    KEY `item_id` (`item_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_dinner_tables
-- =====================================================
CREATE TABLE `ospos_dinner_tables` (
    `dinner_table_id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(30) NOT NULL,
    `status` tinyint(1) NOT NULL DEFAULT '0',
    `deleted` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`dinner_table_id`),
    INDEX `status` (`status`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
INSERT INTO `ospos_dinner_tables` (`dinner_table_id`, `name`, `status`, `deleted`)
VALUES (1, 'Delivery', 0, 0),
    (2, 'Take Away', 0, 0);
-- =====================================================
-- Table: ospos_tax_categories
-- =====================================================
CREATE TABLE `ospos_tax_categories` (
    `tax_category_id` int(10) NOT NULL AUTO_INCREMENT,
    `tax_category` varchar(32) NOT NULL,
    `tax_group_sequence` tinyint(1) NOT NULL,
    `deleted` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`tax_category_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_tax_codes
-- =====================================================
CREATE TABLE `ospos_tax_codes` (
    `tax_code_id` int(11) NOT NULL AUTO_INCREMENT,
    `tax_code` varchar(32) NOT NULL,
    `tax_code_name` varchar(255) NOT NULL DEFAULT '',
    `tax_code_type` tinyint(2) NOT NULL DEFAULT 0,
    `city` varchar(255) NOT NULL DEFAULT '',
    `state` varchar(255) NOT NULL DEFAULT '',
    `deleted` tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`tax_code_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_tax_code_rates
-- =====================================================
CREATE TABLE `ospos_tax_code_rates` (
    `rate_tax_code` varchar(32) NOT NULL,
    `rate_tax_category_id` int(10) NOT NULL,
    `tax_rate` decimal(15, 4) NOT NULL DEFAULT 0.0000,
    `rounding_code` tinyint(2) NOT NULL DEFAULT 0,
    PRIMARY KEY (`rate_tax_code`, `rate_tax_category_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_tax_jurisdictions
-- =====================================================
CREATE TABLE `ospos_tax_jurisdictions` (
    `jurisdiction_id` int(11) NOT NULL AUTO_INCREMENT,
    `jurisdiction_name` varchar(255) NOT NULL,
    `tax_group` varchar(32) NOT NULL,
    `tax_group_sequence` tinyint(1) DEFAULT 0,
    `cascade_sequence` tinyint(1) DEFAULT 0,
    `deleted` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`jurisdiction_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_tax_rates
-- =====================================================
CREATE TABLE `ospos_tax_rates` (
    `tax_rate_id` int(11) NOT NULL AUTO_INCREMENT,
    `rate_tax_category_id` int(10) NOT NULL,
    `jurisdiction_id` int(11) NOT NULL,
    `tax_rate` decimal(15, 4) NOT NULL DEFAULT 0.0000,
    `tax_rounding_code` tinyint(1) DEFAULT 0,
    PRIMARY KEY (`tax_rate_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_sales
-- =====================================================
CREATE TABLE `ospos_sales` (
    `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `customer_id` int(10) DEFAULT NULL,
    `employee_id` int(10) NOT NULL DEFAULT '0',
    `comment` text NOT NULL,
    `invoice_number` varchar(32) DEFAULT NULL,
    `quote_number` varchar(32) DEFAULT NULL,
    `sale_id` int(10) NOT NULL AUTO_INCREMENT,
    `dinner_table_id` int(11) NULL,
    `sale_status` tinyint(1) NOT NULL DEFAULT 0,
    `sale_type` tinyint(1) NOT NULL DEFAULT 0,
    `work_order_number` varchar(32) DEFAULT NULL,
    `has_kit` tinyint(1) NOT NULL DEFAULT 0,
    `cart_data` text,
    PRIMARY KEY (`sale_id`),
    KEY `customer_id` (`customer_id`),
    KEY `employee_id` (`employee_id`),
    KEY `sale_time` (`sale_time`),
    KEY `dinner_table_id` (`dinner_table_id`),
    UNIQUE KEY `invoice_number` (`invoice_number`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_sales_items
-- =====================================================
CREATE TABLE `ospos_sales_items` (
    `sale_id` int(10) NOT NULL DEFAULT '0',
    `item_id` int(10) NOT NULL DEFAULT '0',
    `description` varchar(255) DEFAULT NULL,
    `serialnumber` varchar(30) DEFAULT NULL,
    `line` int(3) NOT NULL DEFAULT '0',
    `quantity_purchased` decimal(15, 3) NOT NULL DEFAULT '0',
    `item_cost_price` decimal(15, 2) NOT NULL,
    `item_unit_price` decimal(15, 2) NOT NULL,
    `discount` decimal(15, 2) NOT NULL DEFAULT '0',
    `discount_type` tinyint(1) NOT NULL DEFAULT 0,
    `item_location` int(11) NOT NULL,
    `print_option` tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`sale_id`, `item_id`, `line`),
    KEY `sale_id` (`sale_id`),
    KEY `item_id` (`item_id`),
    KEY `item_location` (`item_location`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_sales_items_taxes
-- =====================================================
CREATE TABLE `ospos_sales_items_taxes` (
    `sale_id` int(10) NOT NULL,
    `item_id` int(10) NOT NULL,
    `line` int(3) NOT NULL DEFAULT '0',
    `name` varchar(255) NOT NULL,
    `percent` decimal(15, 4) NOT NULL DEFAULT 0.0000,
    `tax_type` tinyint(1) NOT NULL DEFAULT 0,
    `rounding_code` tinyint(1) NOT NULL DEFAULT 0,
    `cascade_tax` tinyint(1) NOT NULL DEFAULT 0,
    `cascade_sequence` tinyint(1) NOT NULL DEFAULT 0,
    `item_tax_amount` decimal(15, 4) NOT NULL DEFAULT 0,
    PRIMARY KEY (`sale_id`, `item_id`, `line`, `name`, `percent`),
    KEY `sale_id` (`sale_id`),
    KEY `item_id` (`item_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_sales_payments
-- =====================================================
CREATE TABLE `ospos_sales_payments` (
    `payment_id` int(11) NOT NULL AUTO_INCREMENT,
    `sale_id` int(10) NOT NULL,
    `payment_type` varchar(40) NOT NULL,
    `payment_amount` decimal(15, 2) NOT NULL,
    `cash_refund` decimal(15, 2) NOT NULL DEFAULT 0,
    `cash_adjustment` decimal(15, 2) NOT NULL DEFAULT 0,
    `cashflow_account_id` int(10) DEFAULT NULL,
    `employee_id` int(11) DEFAULT NULL,
    `payment_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `reference_code` varchar(40) NOT NULL DEFAULT '',
    PRIMARY KEY (`payment_id`),
    KEY `payment_sale` (`sale_id`, `payment_type`),
    KEY `employee_id` (`employee_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_sales_taxes
-- =====================================================
CREATE TABLE `ospos_sales_taxes` (
    `sale_id` int(10) NOT NULL,
    `tax_type` smallint(2) NOT NULL,
    `tax_group` varchar(32) NOT NULL,
    `sale_tax_basis` decimal(15, 4) NOT NULL,
    `sale_tax_amount` decimal(15, 4) NOT NULL,
    `print_sequence` tinyint(1) NOT NULL DEFAULT 0,
    `name` varchar(255) NOT NULL,
    `tax_rate` decimal(15, 4) NOT NULL,
    `sales_tax_code` varchar(32) NOT NULL DEFAULT '',
    `rounding_code` tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`sale_id`, `tax_type`, `tax_group`),
    KEY `print_sequence` (
        `sale_id`,
        `print_sequence`,
        `tax_type`,
        `tax_group`
    )
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_sales_reward_points
-- =====================================================
CREATE TABLE `ospos_sales_reward_points` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `sale_id` int(11) NOT NULL,
    `earned` float NOT NULL,
    `used` float NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sale_id` (`sale_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci AUTO_INCREMENT = 1;
-- =====================================================
-- Table: ospos_attribute_definitions
-- =====================================================
CREATE TABLE `ospos_attribute_definitions` (
    `definition_id` int(10) NOT NULL AUTO_INCREMENT,
    `definition_name` varchar(255) NOT NULL,
    `definition_type` varchar(45) NOT NULL,
    `definition_flags` tinyint(1) NOT NULL,
    `definition_fk` int(10) NULL,
    `deleted` tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`definition_id`),
    KEY `definition_fk` (`definition_fk`),
    INDEX `definition_name` (`definition_name`),
    INDEX `definition_type` (`definition_type`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_attribute_values
-- =====================================================
CREATE TABLE `ospos_attribute_values` (
    `attribute_id` int NOT NULL AUTO_INCREMENT,
    `attribute_value` varchar(255) UNIQUE NULL,
    `attribute_datetime` datetime NULL,
    `attribute_date` date NULL,
    `attribute_decimal` decimal(15, 4) NULL,
    PRIMARY KEY (`attribute_id`),
    UNIQUE `attribute_date_unique` (`attribute_date`),
    UNIQUE `attribute_decimal_unique` (`attribute_decimal`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_attribute_links
-- =====================================================
CREATE TABLE `ospos_attribute_links` (
    `attribute_id` int NULL,
    `definition_id` int NOT NULL,
    `item_id` int NULL,
    `sale_id` int NULL,
    `receiving_id` int NULL,
    KEY `attribute_id` (`attribute_id`),
    KEY `definition_id` (`definition_id`),
    KEY `item_id` (`item_id`),
    KEY `sale_id` (`sale_id`),
    KEY `receiving_id` (`receiving_id`),
    UNIQUE `attribute_links_uq1` (
        `attribute_id`,
        `definition_id`,
        `item_id`,
        `sale_id`,
        `receiving_id`
    )
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_sessions
-- =====================================================
CREATE TABLE `ospos_sessions` (
    `id` varchar(128) NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `timestamp` timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    `data` blob NOT NULL,
    PRIMARY KEY (`id`, `ip_address`),
    KEY `ospos_sessions_timestamp` (`timestamp`),
    INDEX `id_index` (`id`),
    INDEX `ip_address_index` (`ip_address`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_price_offer_conditions
-- =====================================================
CREATE TABLE `ospos_price_offer_conditions` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text,
    `sort` int(11) NOT NULL DEFAULT 0,
    `is_active` tinyint(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_price_offer_condition_links
-- =====================================================
CREATE TABLE `ospos_price_offer_condition_links` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `sale_id` int(11) unsigned NOT NULL,
    `condition_id` int(11) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sale_condition` (`sale_id`, `condition_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_stock_transfers
-- =====================================================
CREATE TABLE `ospos_stock_transfers` (
    `transfer_id` int(10) NOT NULL AUTO_INCREMENT,
    `transfer_datetime` timestamp DEFAULT CURRENT_TIMESTAMP,
    `source_location_id` int(11) NOT NULL,
    `destination_location_id` int(11) NOT NULL,
    `employee_id` int(10) NOT NULL DEFAULT 0,
    `reference` varchar(32) NULL,
    `comment` text NULL,
    `transfer_status` varchar(20) DEFAULT 'completed',
    `deleted` int(1) DEFAULT 0,
    PRIMARY KEY (`transfer_id`),
    KEY `source_location_id` (`source_location_id`),
    KEY `destination_location_id` (`destination_location_id`),
    KEY `employee_id` (`employee_id`),
    KEY `reference` (`reference`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_stock_transfers_items
-- =====================================================
CREATE TABLE `ospos_stock_transfers_items` (
    `transfer_id` int(10) NOT NULL,
    `item_id` int(10) NOT NULL DEFAULT 0,
    `line` int(3) NOT NULL,
    `quantity` decimal(15, 3) DEFAULT 0,
    `description` varchar(255) NULL,
    `serialnumber` varchar(30) NULL,
    PRIMARY KEY (`transfer_id`, `item_id`, `line`),
    KEY `item_id` (`item_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_manufacturing_projects
-- =====================================================
CREATE TABLE `ospos_manufacturing_projects` (
    `project_id` int(10) NOT NULL AUTO_INCREMENT,
    `project_code` varchar(50) NOT NULL,
    `project_name` varchar(200) NOT NULL,
    `customer_id` int(10) NULL,
    `sale_id` int(10) NULL,
    `project_status` ENUM(
        'planned',
        'in_progress',
        'on_hold',
        'completed',
        'delivered'
    ) NOT NULL DEFAULT 'planned',
    `priority` ENUM('low', 'normal', 'high', 'urgent') NOT NULL DEFAULT 'normal',
    `start_date` date NULL,
    `target_completion_date` date NULL,
    `actual_completion_date` date NULL,
    `delivery_date` date NULL,
    `estimated_hours` decimal(10, 2) NULL,
    `actual_hours` decimal(10, 2) NULL,
    `budgeted_material_cost` decimal(15, 4) NULL,
    `budgeted_labor_cost` decimal(15, 4) NULL,
    `budgeted_overhead_cost` decimal(15, 4) NULL,
    `project_manager_id` int(10) NULL,
    `notes` text NULL,
    `created_by` int(10) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted` int(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`project_id`),
    UNIQUE KEY `uk_project_code` (`project_code`),
    KEY `idx_customer_id` (`customer_id`),
    KEY `idx_sale_id` (`sale_id`),
    KEY `idx_project_status` (`project_status`),
    KEY `idx_project_manager` (`project_manager_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_manufacturing_project_stages
-- =====================================================
CREATE TABLE `ospos_manufacturing_project_stages` (
    `stage_id` int(10) NOT NULL AUTO_INCREMENT,
    `project_id` int(10) NOT NULL,
    `stage_name` varchar(100) NOT NULL,
    `stage_sequence` int(10) NOT NULL DEFAULT 0,
    `stage_status` ENUM('pending', 'in_progress', 'completed', 'skipped') NOT NULL DEFAULT 'pending',
    `start_date` datetime NULL,
    `end_date` datetime NULL,
    `assigned_to` int(10) NULL,
    `notes` text NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`stage_id`),
    KEY `idx_project_id` (`project_id`),
    KEY `idx_stage_status` (`stage_status`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_manufacturing_stock_transfers
-- =====================================================
CREATE TABLE `ospos_manufacturing_stock_transfers` (
    `transfer_id` int(10) NOT NULL AUTO_INCREMENT,
    `transfer_code` varchar(50) NOT NULL,
    `project_id` int(10) NOT NULL,
    `source_location_id` int(10) NOT NULL,
    `transfer_type` ENUM('issue', 'return') NOT NULL DEFAULT 'issue',
    `transfer_date` datetime NOT NULL,
    `reference` varchar(100) NULL,
    `notes` text NULL,
    `status` ENUM('draft', 'confirmed', 'cancelled') NOT NULL DEFAULT 'draft',
    `created_by` int(10) NOT NULL,
    `confirmed_by` int(10) NULL,
    `confirmed_at` datetime NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted` int(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`transfer_id`),
    UNIQUE KEY `uk_transfer_code` (`transfer_code`),
    KEY `idx_project_id` (`project_id`),
    KEY `idx_source_location` (`source_location_id`),
    KEY `idx_transfer_date` (`transfer_date`),
    KEY `idx_transfer_type` (`transfer_type`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_manufacturing_stock_transfer_items
-- =====================================================
CREATE TABLE `ospos_manufacturing_stock_transfer_items` (
    `item_id` int(10) NOT NULL AUTO_INCREMENT,
    `transfer_id` int(10) NOT NULL,
    `item_id_fk` int(10) NOT NULL,
    `quantity` decimal(15, 4) NOT NULL,
    `unit_cost` decimal(15, 4) NOT NULL,
    `total_cost` decimal(15, 4) NOT NULL,
    `serial_number` varchar(100) NULL,
    `notes` varchar(255) NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`item_id`),
    KEY `idx_transfer_id` (`transfer_id`),
    KEY `idx_item_id` (`item_id_fk`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_manufacturing_project_costs
-- =====================================================
CREATE TABLE `ospos_manufacturing_project_costs` (
    `cost_id` int(10) NOT NULL AUTO_INCREMENT,
    `project_id` int(10) NOT NULL,
    `cost_type` ENUM('material', 'labor', 'overhead', 'other') NOT NULL,
    `cost_source` ENUM(
        'material_transfer',
        'labor_entry',
        'manual_entry',
        'overhead_allocation'
    ) NOT NULL,
    `reference_id` int(10) NULL,
    `description` varchar(255) NULL,
    `amount` decimal(15, 4) NOT NULL,
    `cost_date` date NOT NULL,
    `created_by` int(10) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`cost_id`),
    KEY `idx_project_id` (`project_id`),
    KEY `idx_cost_type` (`cost_type`),
    KEY `idx_cost_date` (`cost_date`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
CREATE TABLE IF NOT EXISTS `ospos_cashflow_accounts` (
    `account_id` int unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `type` varchar(50) DEFAULT 'bank',
    `opening_balance` decimal(15, 2) DEFAULT '0.00',
    `is_active` tinyint(1) DEFAULT '1',
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`account_id`),
    UNIQUE KEY `name` (`name`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb3;
-- =====================================================
-- Table: ospos_cashflow_categories
-- =====================================================
CREATE TABLE IF NOT EXISTS `ospos_cashflow_categories` (
    `category_id` int unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `entry_type` varchar(20) NOT NULL,
    `is_active` tinyint(1) DEFAULT '1',
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`category_id`),
    UNIQUE KEY `name_entry_type` (`name`, `entry_type`),
    KEY `entry_type` (`entry_type`)
) ENGINE = InnoDB AUTO_INCREMENT = 7 DEFAULT CHARSET = utf8mb3;
-- Dumping data for table test.ospos_cashflow_categories: ~6 rows (approximately)
INSERT INTO `ospos_cashflow_categories` (
        `category_id`,
        `name`,
        `entry_type`,
        `is_active`,
        `created_at`,
        `updated_at`
    )
VALUES (1, 'Sales Income', 'income', 1, NULL, NULL),
    (2, 'Other Income', 'income', 1, NULL, NULL),
    (3, 'Rent', 'outcome', 1, NULL, NULL),
    (4, 'Utilities', 'outcome', 1, NULL, NULL),
    (5, 'Supplies', 'outcome', 1, NULL, NULL),
    (6, 'Other Expense', 'outcome', 1, NULL, NULL);
-- =====================================================
-- Table: ospos_cashflow_entries
-- =====================================================
CREATE TABLE IF NOT EXISTS `ospos_cashflow_entries` (
    `entry_id` int unsigned NOT NULL AUTO_INCREMENT,
    `entry_date` datetime NOT NULL,
    `entry_type` varchar(20) NOT NULL,
    `category_id` int unsigned DEFAULT NULL,
    `amount` decimal(15, 2) NOT NULL,
    `description` text,
    `status` varchar(20) DEFAULT 'draft',
    `account_id` int unsigned DEFAULT NULL,
    `from_account_id` int unsigned DEFAULT NULL,
    `to_account_id` int unsigned DEFAULT NULL,
    `customer_id` int DEFAULT NULL,
    `supplier_id` int DEFAULT NULL,
    `sale_id` int DEFAULT NULL,
    `sale_payment_id` int DEFAULT NULL,
    `receiving_id` int DEFAULT NULL,
    `created_by` int NOT NULL,
    `deleted` tinyint(1) DEFAULT '0',
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`entry_id`),
    UNIQUE KEY `sale_payment_id` (`sale_payment_id`),
    KEY `entry_date` (`entry_date`),
    KEY `entry_type` (`entry_type`),
    KEY `status` (`status`),
    KEY `account_id` (`account_id`),
    KEY `from_account_id` (`from_account_id`),
    KEY `to_account_id` (`to_account_id`),
    KEY `category_id` (`category_id`),
    KEY `sale_id` (`sale_id`),
    KEY `fk_cashflow_entries_customer_id` (`customer_id`),
    KEY `fk_cashflow_entries_supplier_id` (`supplier_id`),
    KEY `fk_cashflow_entries_receiving_id` (`receiving_id`),
    KEY `fk_cashflow_entries_created_by` (`created_by`),
    CONSTRAINT `fk_cashflow_entries_account_id` FOREIGN KEY (`account_id`) REFERENCES `ospos_cashflow_accounts` (`account_id`) ON DELETE
    SET NULL ON UPDATE CASCADE,
        CONSTRAINT `fk_cashflow_entries_category_id` FOREIGN KEY (`category_id`) REFERENCES `ospos_cashflow_categories` (`category_id`) ON DELETE
    SET NULL ON UPDATE CASCADE,
        CONSTRAINT `fk_cashflow_entries_created_by` FOREIGN KEY (`created_by`) REFERENCES `ospos_employees` (`person_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
        CONSTRAINT `fk_cashflow_entries_customer_id` FOREIGN KEY (`customer_id`) REFERENCES `ospos_customers` (`person_id`) ON DELETE
    SET NULL ON UPDATE CASCADE,
        CONSTRAINT `fk_cashflow_entries_from_account_id` FOREIGN KEY (`from_account_id`) REFERENCES `ospos_cashflow_accounts` (`account_id`) ON DELETE
    SET NULL ON UPDATE CASCADE,
        CONSTRAINT `fk_cashflow_entries_receiving_id` FOREIGN KEY (`receiving_id`) REFERENCES `ospos_receivings` (`receiving_id`) ON DELETE
    SET NULL ON UPDATE CASCADE,
        CONSTRAINT `fk_cashflow_entries_sale_id` FOREIGN KEY (`sale_id`) REFERENCES `ospos_sales` (`sale_id`) ON DELETE
    SET NULL ON UPDATE CASCADE,
        CONSTRAINT `fk_cashflow_entries_sale_payment_id` FOREIGN KEY (`sale_payment_id`) REFERENCES `ospos_sales_payments` (`payment_id`) ON DELETE
    SET NULL ON UPDATE CASCADE,
        CONSTRAINT `fk_cashflow_entries_supplier_id` FOREIGN KEY (`supplier_id`) REFERENCES `ospos_suppliers` (`person_id`) ON DELETE
    SET NULL ON UPDATE CASCADE,
        CONSTRAINT `fk_cashflow_entries_to_account_id` FOREIGN KEY (`to_account_id`) REFERENCES `ospos_cashflow_accounts` (`account_id`) ON DELETE
    SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb3;
-- =====================================================
-- Table: ospos_cashflow_attachments
-- =====================================================
CREATE TABLE IF NOT EXISTS `ospos_cashflow_attachments` (
    `attachment_id` int unsigned NOT NULL AUTO_INCREMENT,
    `entry_id` int unsigned NOT NULL,
    `file_name` varchar(255) NOT NULL,
    `file_path` varchar(512) NOT NULL,
    `mime_type` varchar(100) DEFAULT NULL,
    `size` int unsigned DEFAULT '0',
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`attachment_id`),
    KEY `entry_id` (`entry_id`),
    CONSTRAINT `fk_cashflow_attachments_entry_id` FOREIGN KEY (`entry_id`) REFERENCES `ospos_cashflow_entries` (`entry_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb3;
-- =====================================================
-- Table: ospos_cashflow_category_types
-- =====================================================
CREATE TABLE IF NOT EXISTS `ospos_cashflow_category_types` (
    `type_code` varchar(50) NOT NULL,
    `type_label` varchar(255) NOT NULL,
    `calc_method` varchar(20) NOT NULL,
    `is_active` tinyint(1) DEFAULT '1',
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`type_code`),
    KEY `is_active` (`is_active`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb3;
-- Dumping data for table test.ospos_cashflow_category_types: ~3 rows (approximately)
INSERT INTO `ospos_cashflow_category_types` (
        `type_code`,
        `type_label`,
        `calc_method`,
        `is_active`,
        `created_at`,
        `updated_at`
    )
VALUES ('income', 'Income', 'add', 1, NULL, NULL),
    ('outcome', 'Outcome', 'subtract', 1, NULL, NULL),
    (
        'transfer',
        'Transfer',
        'transfer',
        1,
        NULL,
        NULL
    );
-- =====================================================
-- Table: ospos_manufacturing_labor_entries
-- =====================================================
CREATE TABLE `ospos_manufacturing_labor_entries` (
    `entry_id` int(10) NOT NULL AUTO_INCREMENT,
    `project_id` int(10) NOT NULL,
    `stage_id` int(10) NULL,
    `employee_id` int(10) NOT NULL,
    `work_date` date NOT NULL,
    `start_time` time NULL,
    `end_time` time NULL,
    `hours_worked` decimal(6, 2) NOT NULL,
    `hourly_rate` decimal(10, 4) NOT NULL,
    `total_cost` decimal(15, 4) NOT NULL,
    `work_description` varchar(255) NULL,
    `created_by` int(10) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`entry_id`),
    KEY `idx_project_id` (`project_id`),
    KEY `idx_employee_id` (`employee_id`),
    KEY `idx_work_date` (`work_date`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
-- =====================================================
-- Table: ospos_manufacturing_overhead_rates
-- =====================================================
CREATE TABLE `ospos_manufacturing_overhead_rates` (
    `rate_id` int(10) NOT NULL AUTO_INCREMENT,
    `rate_name` varchar(100) NOT NULL,
    `rate_type` ENUM(
        'percentage',
        'fixed_per_hour',
        'fixed_per_project'
    ) NOT NULL,
    `rate_value` decimal(10, 4) NOT NULL,
    `applies_to` ENUM(
        'material_cost',
        'labor_cost',
        'total_cost',
        'per_hour'
    ) NOT NULL,
    `is_active` int(1) NOT NULL DEFAULT 1,
    `effective_from` date NOT NULL,
    `effective_to` date NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`rate_id`),
    KEY `idx_is_active` (`is_active`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
INSERT INTO `ospos_manufacturing_overhead_rates` (
        `rate_name`,
        `rate_type`,
        `rate_value`,
        `applies_to`,
        `is_active`,
        `effective_from`
    )
VALUES (
        'Factory Overhead',
        'percentage',
        15.00,
        'labor_cost',
        1,
        CURDATE()
    ),
    (
        'Equipment Depreciation',
        'fixed_per_hour',
        5.00,
        'per_hour',
        1,
        CURDATE()
    ),
    (
        'Administrative Overhead',
        'percentage',
        5.00,
        'total_cost',
        1,
        CURDATE()
    );
-- =====================================================
-- FOREIGN KEY CONSTRAINTS
-- =====================================================
ALTER TABLE `ospos_employees`
ADD CONSTRAINT `ospos_employees_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `ospos_people` (`person_id`);
ALTER TABLE `ospos_suppliers`
ADD CONSTRAINT `ospos_suppliers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `ospos_people` (`person_id`);
ALTER TABLE `ospos_customers`
ADD CONSTRAINT `ospos_customers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `ospos_people` (`person_id`),
    ADD CONSTRAINT `ospos_customers_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `ospos_customers_packages` (`package_id`);
ALTER TABLE `ospos_customers_points`
ADD CONSTRAINT `ospos_customers_points_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `ospos_customers` (`person_id`),
    ADD CONSTRAINT `ospos_customers_points_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `ospos_customers_packages` (`package_id`),
    ADD CONSTRAINT `ospos_customers_points_ibfk_3` FOREIGN KEY (`sale_id`) REFERENCES `ospos_sales` (`sale_id`);
ALTER TABLE `ospos_giftcards`
ADD CONSTRAINT `ospos_giftcards_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `ospos_people` (`person_id`);
ALTER TABLE `ospos_items`
ADD CONSTRAINT `ospos_items_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `ospos_suppliers` (`person_id`);
ALTER TABLE `ospos_items_taxes`
ADD CONSTRAINT `ospos_items_taxes_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`) ON DELETE CASCADE;
ALTER TABLE `ospos_item_kit_items`
ADD CONSTRAINT `ospos_item_kit_items_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `ospos_item_kits` (`item_kit_id`) ON DELETE CASCADE,
    ADD CONSTRAINT `ospos_item_kit_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`) ON DELETE CASCADE;
ALTER TABLE `ospos_permissions`
ADD CONSTRAINT `ospos_permissions_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `ospos_modules` (`module_id`) ON DELETE CASCADE,
    ADD CONSTRAINT `ospos_permissions_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `ospos_stock_locations` (`location_id`) ON DELETE CASCADE;
ALTER TABLE `ospos_grants`
ADD CONSTRAINT `ospos_grants_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `ospos_permissions` (`permission_id`) ON DELETE CASCADE,
    ADD CONSTRAINT `ospos_grants_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `ospos_employees` (`person_id`) ON DELETE CASCADE;
ALTER TABLE `ospos_inventory`
ADD CONSTRAINT `ospos_inventory_ibfk_1` FOREIGN KEY (`trans_items`) REFERENCES `ospos_items` (`item_id`),
    ADD CONSTRAINT `ospos_inventory_ibfk_2` FOREIGN KEY (`trans_user`) REFERENCES `ospos_employees` (`person_id`),
    ADD CONSTRAINT `ospos_inventory_ibfk_3` FOREIGN KEY (`trans_location`) REFERENCES `ospos_stock_locations` (`location_id`);
ALTER TABLE `ospos_item_quantities`
ADD CONSTRAINT `ospos_item_quantities_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`),
    ADD CONSTRAINT `ospos_item_quantities_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `ospos_stock_locations` (`location_id`);
ALTER TABLE `ospos_receivings`
ADD CONSTRAINT `ospos_receivings_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `ospos_employees` (`person_id`),
    ADD CONSTRAINT `ospos_receivings_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `ospos_suppliers` (`person_id`);
ALTER TABLE `ospos_receivings_items`
ADD CONSTRAINT `ospos_receivings_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`),
    ADD CONSTRAINT `ospos_receivings_items_ibfk_2` FOREIGN KEY (`receiving_id`) REFERENCES `ospos_receivings` (`receiving_id`);
ALTER TABLE `ospos_sales`
ADD CONSTRAINT `ospos_sales_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `ospos_employees` (`person_id`),
    ADD CONSTRAINT `ospos_sales_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `ospos_customers` (`person_id`),
    ADD CONSTRAINT `ospos_sales_ibfk_3` FOREIGN KEY (`dinner_table_id`) REFERENCES `ospos_dinner_tables` (`dinner_table_id`);
ALTER TABLE `ospos_sales_items`
ADD CONSTRAINT `ospos_sales_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`),
    ADD CONSTRAINT `ospos_sales_items_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `ospos_sales` (`sale_id`),
    ADD CONSTRAINT `ospos_sales_items_ibfk_3` FOREIGN KEY (`item_location`) REFERENCES `ospos_stock_locations` (`location_id`);
ALTER TABLE `ospos_sales_items_taxes`
ADD CONSTRAINT `ospos_sales_items_taxes_ibfk_1` FOREIGN KEY (`sale_id`, `item_id`, `line`) REFERENCES `ospos_sales_items` (`sale_id`, `item_id`, `line`),
    ADD CONSTRAINT `ospos_sales_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`);
ALTER TABLE `ospos_sales_payments`
ADD CONSTRAINT `ospos_sales_payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `ospos_sales` (`sale_id`),
    ADD CONSTRAINT `ospos_sales_payments_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `ospos_employees` (`person_id`);
ALTER TABLE `ospos_sales_reward_points`
ADD CONSTRAINT `ospos_sales_reward_points_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `ospos_sales` (`sale_id`);
ALTER TABLE `ospos_attribute_definitions`
ADD CONSTRAINT `fk_ospos_attribute_definitions_ibfk_1` FOREIGN KEY (`definition_fk`) REFERENCES `ospos_attribute_definitions` (`definition_id`);
ALTER TABLE `ospos_attribute_links`
ADD CONSTRAINT `ospos_attribute_links_ibfk_1` FOREIGN KEY (`definition_id`) REFERENCES `ospos_attribute_definitions` (`definition_id`) ON DELETE CASCADE,
    ADD CONSTRAINT `ospos_attribute_links_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `ospos_attribute_values` (`attribute_id`) ON DELETE CASCADE,
    ADD CONSTRAINT `ospos_attribute_links_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`),
    ADD CONSTRAINT `ospos_attribute_links_ibfk_4` FOREIGN KEY (`receiving_id`) REFERENCES `ospos_receivings` (`receiving_id`),
    ADD CONSTRAINT `ospos_attribute_links_ibfk_5` FOREIGN KEY (`sale_id`) REFERENCES `ospos_sales` (`sale_id`);
ALTER TABLE `ospos_stock_transfers`
ADD CONSTRAINT `stock_transfers_ibfk_1` FOREIGN KEY (`source_location_id`) REFERENCES `ospos_stock_locations` (`location_id`),
    ADD CONSTRAINT `stock_transfers_ibfk_2` FOREIGN KEY (`destination_location_id`) REFERENCES `ospos_stock_locations` (`location_id`),
    ADD CONSTRAINT `stock_transfers_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `ospos_employees` (`person_id`);
ALTER TABLE `ospos_stock_transfers_items`
ADD CONSTRAINT `stock_transfers_items_ibfk_1` FOREIGN KEY (`transfer_id`) REFERENCES `ospos_stock_transfers` (`transfer_id`),
    ADD CONSTRAINT `stock_transfers_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`);
-- =====================================================
-- HR MODULE TABLES
-- =====================================================
-- Table: ospos_departments
-- =====================================================
CREATE TABLE `ospos_departments` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` varchar(255) DEFAULT NULL,
    `parent_id` int(10) unsigned DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `parent_id` (`parent_id`),
    CONSTRAINT `fk_departments_parent` FOREIGN KEY (`parent_id`) REFERENCES `ospos_departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
-- =====================================================
-- Table: ospos_positions
-- =====================================================
CREATE TABLE `ospos_positions` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` varchar(255) DEFAULT NULL,
    `department_id` int(10) unsigned DEFAULT NULL,
    `level` int(5) DEFAULT 1,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `department_id` (`department_id`),
    CONSTRAINT `fk_positions_department` FOREIGN KEY (`department_id`) REFERENCES `ospos_departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
-- =====================================================
-- Table: ospos_shifts
-- =====================================================
CREATE TABLE `ospos_shifts` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `code` varchar(20) NOT NULL,
    `start_time` time NOT NULL,
    `end_time` time NOT NULL,
    `grace_period_minutes` int(5) DEFAULT 0,
    `working_hours` decimal(5,2) DEFAULT 8.00,
    `overtime_threshold_minutes` int(5) DEFAULT 0,
    `night_shift_start` time DEFAULT NULL,
    `night_shift_end` time DEFAULT NULL,
    `is_night_shift` tinyint(1) DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
-- =====================================================
-- Table: ospos_employee_profiles
-- =====================================================
CREATE TABLE `ospos_employee_profiles` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `employee_id` int(10) NOT NULL,
    `department_id` int(10) unsigned DEFAULT NULL,
    `position_id` int(10) unsigned DEFAULT NULL,
    `shift_id` int(10) unsigned DEFAULT NULL,
    `employee_number` varchar(50) DEFAULT NULL,
    `basic_salary` decimal(15,4) DEFAULT 0,
    `hourly_rate` decimal(15,4) DEFAULT 0,
    `hire_date` date DEFAULT NULL,
    `termination_date` date DEFAULT NULL,
    `employment_type` enum('full_time','part_time','contract','intern') DEFAULT 'full_time',
    `employment_status` enum('active','on_leave','suspended','terminated') DEFAULT 'active',
    `bank_name` varchar(100) DEFAULT NULL,
    `bank_account` varchar(50) DEFAULT NULL,
    `tax_id` varchar(50) DEFAULT NULL,
    `social_security_number` varchar(50) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `employee_id` (`employee_id`),
    UNIQUE KEY `employee_number` (`employee_number`),
    KEY `department_id` (`department_id`),
    KEY `position_id` (`position_id`),
    KEY `shift_id` (`shift_id`),
    CONSTRAINT `fk_employee_profiles_employee` FOREIGN KEY (`employee_id`) REFERENCES `ospos_employees` (`person_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_employee_profiles_department` FOREIGN KEY (`department_id`) REFERENCES `ospos_departments` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_employee_profiles_position` FOREIGN KEY (`position_id`) REFERENCES `ospos_positions` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_employee_profiles_shift` FOREIGN KEY (`shift_id`) REFERENCES `ospos_shifts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
-- =====================================================
-- Table: ospos_emergency_contacts
-- =====================================================
CREATE TABLE `ospos_emergency_contacts` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `employee_id` int(10) NOT NULL,
    `name` varchar(100) NOT NULL,
    `relationship` varchar(50) DEFAULT NULL,
    `phone_number` varchar(20) DEFAULT NULL,
    `email` varchar(100) DEFAULT NULL,
    `address` varchar(255) DEFAULT NULL,
    `is_primary` tinyint(1) DEFAULT 0,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `employee_id` (`employee_id`),
    CONSTRAINT `fk_emergency_contacts_employee` FOREIGN KEY (`employee_id`) REFERENCES `ospos_employees` (`person_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
-- =====================================================
-- Table: ospos_salary_rule_groups
-- =====================================================
CREATE TABLE `ospos_salary_rule_groups` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `type` enum('earning','deduction') NOT NULL,
    `calculation_order` int(5) DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
-- =====================================================
-- Table: ospos_salary_rules
-- =====================================================
CREATE TABLE `ospos_salary_rules` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `group_id` int(10) unsigned NOT NULL,
    `name` varchar(100) NOT NULL,
    `code` varchar(50) NOT NULL,
    `rule_type` enum('fixed','percentage','formula','conditional') NOT NULL,
    `value` decimal(15,4) DEFAULT 0,
    `formula` text DEFAULT NULL,
    `based_on` enum('gross','basic','attendance','none') DEFAULT 'none',
    `conditions` json DEFAULT NULL,
    `attendance_type` enum('none','overtime','late','absent','night_shift') DEFAULT NULL,
    `attendance_rate` decimal(5,2) DEFAULT 1.00,
    `scope` enum('global','department','position','employee') DEFAULT 'global',
    `scope_id` int(10) unsigned DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `is_recurring` tinyint(1) DEFAULT 1,
    `priority` int(5) DEFAULT 0,
    `description` text DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `code` (`code`),
    KEY `group_id` (`group_id`),
    CONSTRAINT `fk_salary_rules_group` FOREIGN KEY (`group_id`) REFERENCES `ospos_salary_rule_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
-- =====================================================
-- Table: ospos_employee_salary_rules
-- =====================================================
CREATE TABLE `ospos_employee_salary_rules` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `employee_id` int(10) NOT NULL,
    `rule_id` int(10) unsigned NOT NULL,
    `custom_value` decimal(15,4) DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `employee_rule` (`employee_id`,`rule_id`),
    KEY `rule_id` (`rule_id`),
    CONSTRAINT `fk_employee_salary_rules_employee` FOREIGN KEY (`employee_id`) REFERENCES `ospos_employees` (`person_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_employee_salary_rules_rule` FOREIGN KEY (`rule_id`) REFERENCES `ospos_salary_rules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
-- =====================================================
-- Table: ospos_attendance
-- =====================================================
CREATE TABLE `ospos_attendance` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `employee_id` int(10) NOT NULL,
    `date` date NOT NULL,
    `clock_in` datetime DEFAULT NULL,
    `clock_out` datetime DEFAULT NULL,
    `scheduled_start` time DEFAULT NULL,
    `scheduled_end` time DEFAULT NULL,
    `status` enum('present','absent','late','early_out','on_leave','holiday') DEFAULT 'present',
    `late_minutes` int(5) DEFAULT 0,
    `overtime_minutes` int(5) DEFAULT 0,
    `early_out_minutes` int(5) DEFAULT 0,
    `night_shift_hours` decimal(5,2) DEFAULT 0,
    `worked_hours` decimal(5,2) DEFAULT 0,
    `notes` text DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `employee_date` (`employee_id`,`date`),
    KEY `employee_id` (`employee_id`),
    CONSTRAINT `fk_attendance_employee` FOREIGN KEY (`employee_id`) REFERENCES `ospos_employees` (`person_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
-- =====================================================
-- Table: ospos_leave_types
-- =====================================================
CREATE TABLE `ospos_leave_types` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `code` varchar(20) NOT NULL,
    `paid_unpaid` enum('paid','unpaid') DEFAULT 'unpaid',
    `default_days` decimal(5,2) DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
-- =====================================================
-- Table: ospos_leave_requests
-- =====================================================
CREATE TABLE `ospos_leave_requests` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `employee_id` int(10) NOT NULL,
    `leave_type_id` int(10) unsigned NOT NULL,
    `start_date` date NOT NULL,
    `end_date` date NOT NULL,
    `total_days` decimal(5,2) DEFAULT 0,
    `reason` text DEFAULT NULL,
    `status` enum('pending','approved','rejected','cancelled') DEFAULT 'pending',
    `approved_by` int(10) unsigned DEFAULT NULL,
    `approved_at` datetime DEFAULT NULL,
    `rejection_reason` text DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `employee_id` (`employee_id`),
    KEY `leave_type_id` (`leave_type_id`),
    KEY `approved_by` (`approved_by`),
    CONSTRAINT `fk_leave_requests_employee` FOREIGN KEY (`employee_id`) REFERENCES `ospos_employees` (`person_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_leave_requests_type` FOREIGN KEY (`leave_type_id`) REFERENCES `ospos_leave_types` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_leave_requests_approver` FOREIGN KEY (`approved_by`) REFERENCES `ospos_employees` (`person_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
-- =====================================================
-- Table: ospos_employee_leave_balances
-- =====================================================
CREATE TABLE `ospos_employee_leave_balances` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `employee_id` int(10) NOT NULL,
    `leave_type_id` int(10) unsigned NOT NULL,
    `year` year(4) NOT NULL,
    `entitled_days` decimal(5,2) DEFAULT 0,
    `used_days` decimal(5,2) DEFAULT 0,
    `pending_days` decimal(5,2) DEFAULT 0,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `employee_leave_year` (`employee_id`,`leave_type_id`,`year`),
    KEY `employee_id` (`employee_id`),
    KEY `leave_type_id` (`leave_type_id`),
    CONSTRAINT `fk_employee_leave_balances_employee` FOREIGN KEY (`employee_id`) REFERENCES `ospos_employees` (`person_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_employee_leave_balances_type` FOREIGN KEY (`leave_type_id`) REFERENCES `ospos_leave_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
-- =====================================================
-- Table: ospos_salary_components
-- =====================================================
CREATE TABLE `ospos_salary_components` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `employee_id` int(10) NOT NULL,
    `period_start` date NOT NULL,
    `period_end` date NOT NULL,
    `rule_id` int(10) unsigned DEFAULT NULL,
    `rule_name` varchar(100) NOT NULL,
    `rule_type` varchar(20) NOT NULL,
    `rule_group_type` enum('earning','deduction') NOT NULL,
    `calculated_value` decimal(15,4) NOT NULL,
    `calculation_details` json DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `employee_id` (`employee_id`),
    KEY `rule_id` (`rule_id`),
    CONSTRAINT `fk_salary_components_employee` FOREIGN KEY (`employee_id`) REFERENCES `ospos_employees` (`person_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_salary_components_rule` FOREIGN KEY (`rule_id`) REFERENCES `ospos_salary_rules` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
-- =====================================================
-- Table: ospos_salary_periods
-- =====================================================
CREATE TABLE `ospos_salary_periods` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `period_type` enum('monthly','weekly','bi-weekly','custom') DEFAULT 'monthly',
    `start_date` date NOT NULL,
    `end_date` date NOT NULL,
    `payment_date` date DEFAULT NULL,
    `status` enum('draft','calculated','approved','paid') DEFAULT 'draft',
    `total_earnings` decimal(15,4) DEFAULT 0,
    `total_deductions` decimal(15,4) DEFAULT 0,
    `total_net` decimal(15,4) DEFAULT 0,
    `approved_by` int(10) unsigned DEFAULT NULL,
    `approved_at` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `approved_by` (`approved_by`),
    CONSTRAINT `fk_salary_periods_approver` FOREIGN KEY (`approved_by`) REFERENCES `ospos_employees` (`person_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
-- =====================================================
-- Table: ospos_employee_shifts
-- =====================================================
CREATE TABLE `ospos_employee_shifts` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `employee_id` int(10) NOT NULL,
    `shift_id` int(10) unsigned NOT NULL,
    `effective_from` date NOT NULL,
    `effective_to` date DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `employee_id` (`employee_id`),
    KEY `shift_id` (`shift_id`),
    CONSTRAINT `fk_employee_shifts_employee` FOREIGN KEY (`employee_id`) REFERENCES `ospos_employees` (`person_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_employee_shifts_shift` FOREIGN KEY (`shift_id`) REFERENCES `ospos_shifts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
-- =====================================================
-- Table: ospos_employee_attachments
-- =====================================================
CREATE TABLE `ospos_employee_attachments` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `employee_id` int(10) NOT NULL,
    `doc_type` varchar(50) NOT NULL,
    `title` varchar(255) NOT NULL,
    `file_name` varchar(255) NOT NULL,
    `file_path` varchar(512) NOT NULL,
    `mime_type` varchar(100) DEFAULT NULL,
    `file_size` int unsigned DEFAULT 0,
    `description` text DEFAULT NULL,
    `expiry_date` date DEFAULT NULL,
    `is_verified` tinyint(1) DEFAULT 0,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `employee_id` (`employee_id`),
    CONSTRAINT `fk_employee_attachments_employee` FOREIGN KEY (`employee_id`) REFERENCES `ospos_employees` (`person_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
-- =====================================================
-- Default HR Data
-- =====================================================
INSERT INTO `ospos_departments` (`name`, `description`, `is_active`) VALUES
('General', 'General department', 1),
('Human Resources', 'HR department', 1),
('Finance', 'Finance department', 1);
INSERT INTO `ospos_positions` (`name`, `description`, `department_id`, `is_active`) VALUES
('Manager', 'Department manager', 1, 1),
('Staff', 'Regular staff', 1, 1);
INSERT INTO `ospos_shifts` (`name`, `code`, `start_time`, `end_time`, `working_hours`, `is_active`) VALUES
('Morning', 'MORN', '08:00:00', '16:00:00', 8.00, 1),
('Evening', 'EVEN', '16:00:00', '00:00:00', 8.00, 1),
('Night', 'NIGHT', '00:00:00', '08:00:00', 8.00, 1);
INSERT INTO `ospos_leave_types` (`name`, `code`, `paid_unpaid`, `default_days`, `is_active`) VALUES
('Annual Leave', 'AL', 'paid', 12.00, 1),
('Sick Leave', 'SL', 'paid', 7.00, 1),
('Personal Leave', 'PL', 'unpaid', 3.00, 1);
INSERT INTO `ospos_salary_rule_groups` (`name`, `type`, `calculation_order`, `is_active`) VALUES
('Basic Salary', 'earning', 1, 1),
('Allowances', 'earning', 2, 1),
('Deductions', 'deduction', 3, 1),
('Overtime', 'earning', 4, 1);
INSERT INTO `ospos_salary_rules` (`group_id`, `name`, `code`, `rule_type`, `value`, `based_on`, `is_active`) VALUES
(1, 'Basic Salary', 'BASIC', 'fixed', 0, 'none', 1),
(2, 'Transport Allowance', 'TRANSPORT', 'fixed', 50.00, 'none', 1),
(2, 'Housing Allowance', 'HOUSING', 'percentage', 10.00, 'basic', 1),
(3, 'Tax', 'TAX', 'percentage', 5.00, 'gross', 1),
(3, 'Social Security', 'SS', 'percentage', 2.00, 'basic', 1),
(4, 'Overtime Rate', 'OT', 'fixed', 1.50, 'attendance', 1);


