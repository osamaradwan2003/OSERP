<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHrModule extends Migration
{
    public function up()
    {
        // Add HR module
        $this->db->query("
            INSERT IGNORE INTO `ospos_modules` (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`)
            VALUES ('module_hr', 'module_hr_desc', 85, 'hr')
        ");

        // Add HR permissions
        $permissions = [
            ['permission_id' => 'hr', 'module_id' => 'hr'],
            ['permission_id' => 'hr_dashboard', 'module_id' => 'hr'],
            ['permission_id' => 'hr_departments', 'module_id' => 'hr'],
            ['permission_id' => 'hr_positions', 'module_id' => 'hr'],
            ['permission_id' => 'hr_shifts', 'module_id' => 'hr'],
            ['permission_id' => 'hr_profiles', 'module_id' => 'hr'],
            ['permission_id' => 'hr_salary_rules', 'module_id' => 'hr'],
            ['permission_id' => 'hr_calculate', 'module_id' => 'hr'],
            ['permission_id' => 'hr_attendance', 'module_id' => 'hr'],
            ['permission_id' => 'hr_leave', 'module_id' => 'hr'],
        ];

        foreach ($permissions as $perm) {
            $this->db->query(
                "INSERT IGNORE INTO `ospos_permissions` (`permission_id`, `module_id`) VALUES (?, ?)",
                [$perm['permission_id'], $perm['module_id']]
            );
        }

        // Grant HR permissions to admin (person_id = 1)
        foreach ($permissions as $perm) {
            $this->db->query(
                "INSERT IGNORE INTO `ospos_grants` (`permission_id`, `person_id`, `menu_group`) VALUES (?, 1, 'office')",
                [$perm['permission_id']]
            );
        }
    }

    public function down()
    {
        $permissions = [
            'hr', 'hr_dashboard', 'hr_departments', 'hr_positions', 'hr_shifts',
            'hr_profiles', 'hr_salary_rules', 'hr_calculate', 'hr_attendance', 'hr_leave'
        ];

        foreach ($permissions as $perm) {
            $this->db->query("DELETE FROM `ospos_grants` WHERE `permission_id` = ?", [$perm]);
            $this->db->query("DELETE FROM `ospos_permissions` WHERE `permission_id` = ?", [$perm]);
        }

        $this->db->query("DELETE FROM `ospos_modules` WHERE `module_id` = 'hr'");
    }
}
