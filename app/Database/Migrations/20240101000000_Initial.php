<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Initial extends Migration
{
    public function up()
    {
        $sql = file_get_contents(APPPATH . 'Database/initial_database.sql');
        
        $queries = array_filter(array_map('trim', explode(";\n", $sql)));
        
        foreach ($queries as $query) {
            if (!empty($query) && $query !== ';') {
                $this->db->query($query);
            }
        }
    }

    public function down()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        $tables = [
            'ospos_manufacturing_stock_transfer_items',
            'ospos_manufacturing_stock_transfers',
            'ospos_manufacturing_labor_entries',
            'ospos_manufacturing_project_costs',
            'ospos_manufacturing_project_stages',
            'ospos_manufacturing_projects',
            'ospos_manufacturing_overhead_rates',
            'ospos_stock_transfers_items',
            'ospos_stock_transfers',
            'ospos_price_offer_condition_links',
            'ospos_price_offer_conditions',
            'ospos_attribute_links',
            'ospos_attribute_values',
            'ospos_attribute_definitions',
            'ospos_cash_up',
            'ospos_sales_reward_points',
            'ospos_customers_points',
            'ospos_customers_packages',
            'ospos_sales_payments',
            'ospos_sales_taxes',
            'ospos_sales_items_taxes',
            'ospos_sales_items',
            'ospos_sales',
            'ospos_receivings_items',
            'ospos_receivings',
            'ospos_item_quantities',
            'ospos_inventory',
            'ospos_item_kit_items',
            'ospos_item_kits',
            'ospos_items_taxes',
            'ospos_items',
            'ospos_giftcards',
            'ospos_grants',
            'ospos_permissions',
            'ospos_stock_locations',
            'ospos_dinner_tables',
            'ospos_tax_rates',
            'ospos_tax_jurisdictions',
            'ospos_tax_code_rates',
            'ospos_tax_codes',
            'ospos_tax_categories',
            'ospos_customers',
            'ospos_employees',
            'ospos_suppliers',
            'ospos_people',
            'ospos_modules',
            'ospos_app_config',
            'ospos_sessions',
        ];

        foreach ($tables as $table) {
            $this->db->query("DROP TABLE IF EXISTS `{$table}`");
        }

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }
}
