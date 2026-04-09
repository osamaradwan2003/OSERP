<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FixHrModuleLangKeys extends Seeder
{
    public function run()
    {
        $this->db->query("UPDATE `ospos_modules` SET `name_lang_key` = 'Module.hr', `desc_lang_key` = 'Module.hr_desc' WHERE `module_id` = 'hr'");
    }
}
