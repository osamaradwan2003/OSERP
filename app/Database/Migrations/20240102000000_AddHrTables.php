<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHrTables extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        // Departments
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'parent_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('parent_id', 'departments', 'id', true, 'SET NULL');
        $this->forge->createTable('departments', true);

        // Positions
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'department_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'level' => ['type' => 'INT', 'constraint' => 5, 'default' => 1],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('department_id', 'departments', 'id', true, 'SET NULL');
        $this->forge->createTable('positions', true);

        // Shifts
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'code' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false],
            'start_time' => ['type' => 'TIME', 'null' => false],
            'end_time' => ['type' => 'TIME', 'null' => false],
            'grace_period_minutes' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'working_hours' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 8.00],
            'overtime_threshold_minutes' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'night_shift_start' => ['type' => 'TIME', 'null' => true],
            'night_shift_end' => ['type' => 'TIME', 'null' => true],
            'is_night_shift' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('shifts', true);

        // Employee Profiles
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'employee_id' => ['type' => 'INT', 'constraint' => 10, 'null' => false],
            'department_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'position_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'shift_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'employee_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'basic_salary' => ['type' => 'DECIMAL', 'constraint' => '15,4', 'default' => 0],
            'hourly_rate' => ['type' => 'DECIMAL', 'constraint' => '15,4', 'default' => 0],
            'hire_date' => ['type' => 'DATE', 'null' => true],
            'termination_date' => ['type' => 'DATE', 'null' => true],
            'employment_type' => ['type' => 'ENUM', 'constraint' => ['full_time', 'part_time', 'contract', 'intern'], 'default' => 'full_time'],
            'employment_status' => ['type' => 'ENUM', 'constraint' => ['active', 'on_leave', 'suspended', 'terminated'], 'default' => 'active'],
            'bank_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'bank_account' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'tax_id' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'social_security_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('employee_id');
        $this->forge->addUniqueKey('employee_number');
        $this->forge->addForeignKey('employee_id', 'employees', 'person_id', true, 'CASCADE');
        $this->forge->addForeignKey('department_id', 'departments', 'id', true, 'SET NULL');
        $this->forge->addForeignKey('position_id', 'positions', 'id', true, 'SET NULL');
        $this->forge->addForeignKey('shift_id', 'shifts', 'id', true, 'SET NULL');
        $this->forge->createTable('employee_profiles', true);

        // Emergency Contacts
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'employee_id' => ['type' => 'INT', 'constraint' => 10, 'null' => false],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'relationship' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'phone_number' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'address' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'is_primary' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employee_id', 'employees', 'person_id', true, 'CASCADE');
        $this->forge->createTable('emergency_contacts', true);

        // Salary Rule Groups
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'type' => ['type' => 'ENUM', 'constraint' => ['earning', 'deduction'], 'null' => false],
            'calculation_order' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('salary_rule_groups', true);

        // Salary Rules
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'group_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => false],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'code' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'rule_type' => ['type' => 'ENUM', 'constraint' => ['fixed', 'percentage', 'formula', 'conditional'], 'null' => false],
            'value' => ['type' => 'DECIMAL', 'constraint' => '15,4', 'default' => 0],
            'formula' => ['type' => 'TEXT', 'null' => true],
            'based_on' => ['type' => 'ENUM', 'constraint' => ['gross', 'basic', 'attendance', 'none'], 'default' => 'none'],
            'conditions' => ['type' => 'JSON', 'null' => true],
            'attendance_type' => ['type' => 'ENUM', 'constraint' => ['none', 'overtime', 'late', 'absent', 'night_shift'], 'null' => true],
            'attendance_rate' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 1.00],
            'scope' => ['type' => 'ENUM', 'constraint' => ['global', 'department', 'position', 'employee'], 'default' => 'global'],
            'scope_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'is_recurring' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'priority' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'description' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');
        $this->forge->addForeignKey('group_id', 'salary_rule_groups', 'id', true, 'CASCADE');
        $this->forge->createTable('salary_rules', true);

        // Employee Salary Rules (Per-employee rule assignments)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'employee_id' => ['type' => 'INT', 'constraint' => 10, 'null' => false],
            'rule_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => false],
            'custom_value' => ['type' => 'DECIMAL', 'constraint' => '15,4', 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['employee_id', 'rule_id']);
        $this->forge->addForeignKey('employee_id', 'employees', 'person_id', true, 'CASCADE');
        $this->forge->addForeignKey('rule_id', 'salary_rules', 'id', true, 'CASCADE');
        $this->forge->createTable('employee_salary_rules', true);

        // Attendance
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'employee_id' => ['type' => 'INT', 'constraint' => 10, 'null' => false],
            'date' => ['type' => 'DATE', 'null' => false],
            'clock_in' => ['type' => 'DATETIME', 'null' => true],
            'clock_out' => ['type' => 'DATETIME', 'null' => true],
            'scheduled_start' => ['type' => 'TIME', 'null' => true],
            'scheduled_end' => ['type' => 'TIME', 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['present', 'absent', 'late', 'early_out', 'on_leave', 'holiday'], 'default' => 'present'],
            'late_minutes' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'overtime_minutes' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'early_out_minutes' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'night_shift_hours' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'worked_hours' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['employee_id', 'date']);
        $this->forge->addForeignKey('employee_id', 'employees', 'person_id', true, 'CASCADE');
        $this->forge->createTable('attendance', true);

        // Leave Types
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'code' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false],
            'paid_unpaid' => ['type' => 'ENUM', 'constraint' => ['paid', 'unpaid'], 'default' => 'unpaid'],
            'default_days' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('leave_types', true);

        // Leave Requests
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'employee_id' => ['type' => 'INT', 'constraint' => 10, 'null' => false],
            'leave_type_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => false],
            'start_date' => ['type' => 'DATE', 'null' => false],
            'end_date' => ['type' => 'DATE', 'null' => false],
            'total_days' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'reason' => ['type' => 'TEXT', 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'rejected', 'cancelled'], 'default' => 'pending'],
            'approved_by' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'approved_at' => ['type' => 'DATETIME', 'null' => true],
            'rejection_reason' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employee_id', 'employees', 'person_id', true, 'CASCADE');
        $this->forge->addForeignKey('leave_type_id', 'leave_types', 'id', true, 'CASCADE');
        $this->forge->createTable('leave_requests', true);

        // Employee Leave Balances
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'employee_id' => ['type' => 'INT', 'constraint' => 10, 'null' => false],
            'leave_type_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => false],
            'year' => ['type' => 'YEAR', 'null' => false],
            'entitled_days' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'used_days' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'pending_days' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['employee_id', 'leave_type_id', 'year']);
        $this->forge->addForeignKey('employee_id', 'employees', 'person_id', true, 'CASCADE');
        $this->forge->addForeignKey('leave_type_id', 'leave_types', 'id', true, 'CASCADE');
        $this->forge->createTable('employee_leave_balances', true);

        // Salary Components (calculated salary per employee per period)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'employee_id' => ['type' => 'INT', 'constraint' => 10, 'null' => false],
            'period_start' => ['type' => 'DATE', 'null' => false],
            'period_end' => ['type' => 'DATE', 'null' => false],
            'rule_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'rule_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'rule_type' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false],
            'rule_group_type' => ['type' => 'ENUM', 'constraint' => ['earning', 'deduction'], 'null' => false],
            'calculated_value' => ['type' => 'DECIMAL', 'constraint' => '15,4', 'not_null' => true],
            'calculation_details' => ['type' => 'JSON', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employee_id', 'employees', 'person_id', true, 'CASCADE');
        $this->forge->createTable('salary_components', true);

        // Salary Periods
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'period_type' => ['type' => 'ENUM', 'constraint' => ['monthly', 'weekly', 'bi-weekly', 'custom'], 'default' => 'monthly'],
            'start_date' => ['type' => 'DATE', 'null' => false],
            'end_date' => ['type' => 'DATE', 'null' => false],
            'payment_date' => ['type' => 'DATE', 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['draft', 'calculated', 'approved', 'paid'], 'default' => 'draft'],
            'total_earnings' => ['type' => 'DECIMAL', 'constraint' => '15,4', 'default' => 0],
            'total_deductions' => ['type' => 'DECIMAL', 'constraint' => '15,4', 'default' => 0],
            'total_net' => ['type' => 'DECIMAL', 'constraint' => '15,4', 'default' => 0],
            'approved_by' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'approved_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('salary_periods', true);

        // Employee Shifts (for shift scheduling)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'employee_id' => ['type' => 'INT', 'constraint' => 10, 'null' => false],
            'shift_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => false],
            'effective_from' => ['type' => 'DATE', 'null' => false],
            'effective_to' => ['type' => 'DATE', 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employee_id', 'employees', 'person_id', true, 'CASCADE');
        $this->forge->addForeignKey('shift_id', 'shifts', 'id', true, 'CASCADE');
        $this->forge->createTable('employee_shifts', true);

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        $tables = [
            'employee_shifts',
            'salary_periods',
            'salary_components',
            'employee_leave_balances',
            'leave_requests',
            'leave_types',
            'attendance',
            'employee_salary_rules',
            'salary_rules',
            'salary_rule_groups',
            'emergency_contacts',
            'employee_profiles',
            'shifts',
            'positions',
            'departments',
        ];

        foreach ($tables as $table) {
            $this->forge->dropTable($table, true);
        }

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }
}
