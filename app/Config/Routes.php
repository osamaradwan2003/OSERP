<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultController('Login');

$routes->get('/', 'Login::index');
$routes->get('login', 'Login::index');
$routes->post('login', 'Login::index');

$routes->add('no_access/index/(:segment)', 'No_access::index/$1');
$routes->add('no_access/index/(:segment)/(:segment)', 'No_access::index/$1/$2');

$routes->add('reports/summary_(:any)/(:any)/(:any)', 'Reports::Summary_$1/$2/$3/$4');
$routes->add('reports/summary_payments', 'Reports::date_input_only');
$routes->add('reports/summary_discounts', 'Reports::summary_discounts_input');
$routes->add('reports/summary_(:any)', 'Reports::date_input');

$routes->add('reports/graphical_(:any)/(:any)/(:any)', 'Reports::Graphical_$1/$2/$3/$4');
$routes->add('reports/graphical_summary_discounts', 'Reports::summary_discounts_input');
$routes->add('reports/graphical_(:any)', 'Reports::date_input');

$routes->add('reports/inventory_(:any)/(:any)', 'Reports::Inventory_$1/$2');
$routes->add('reports/inventory_low', 'Reports::inventory_low');
$routes->add('reports/inventory_items_flow', 'Reports::inventory_items_flow_input');
$routes->add('reports/inventory_items_flow/(:any)/(:any)/(:any)', 'Reports::inventory_items_flow/$1/$2/$3');
$routes->add('reports/inventory_summary', 'Reports::inventory_summary_input');
$routes->add('reports/inventory_summary/(:any)/(:any)/(:any)', 'Reports::inventory_summary/$1/$2/$3');

$routes->add('reports/detailed_(:any)/(:any)/(:any)/(:any)', 'Reports::Detailed_$1/$2/$3/$4');
$routes->add('reports/detailed_sales', 'Reports::date_input_sales');
$routes->add('reports/detailed_receivings', 'Reports::date_input_recv');

$routes->add('reports/specific_(:any)/(:any)/(:any)/(:any)', 'Reports::Specific_$1/$2/$3/$4');
$routes->add('reports/specific_customers', 'Reports::specific_customer_input');
$routes->add('reports/specific_employees', 'Reports::specific_employee_input');
$routes->add('reports/specific_discounts', 'Reports::specific_discount_input');
$routes->add('reports/specific_suppliers', 'Reports::specific_supplier_input');
$routes->add('reports/moneytransactions', 'Reports::moneytransactions_input');
$routes->add('reports/moneytransactions/(:any)/(:any)/(:any)/(:any)', 'Reports::moneytransactions/$1/$2/$3/$4');

$routes->add('reports/cashflow_ledger', 'Cashflow_reports::ledger_input');
$routes->add('reports/cashflow_ledger/(:any)/(:any)/(:any)/(:any)', 'Cashflow_reports::ledger/$1/$2/$3/$4');
$routes->add('reports/cashflow_summary', 'Cashflow_reports::summary_input');
$routes->add('reports/cashflow_summary/(:any)/(:any)/(:any)', 'Cashflow_reports::summary/$1/$2/$3');
$routes->add('reports/cashflow_account_balance', 'Cashflow_reports::account_balance_input');
$routes->add('reports/cashflow_account_balance/(:any)/(:any)/(:any)', 'Cashflow_reports::account_balance/$1/$2/$3');
$routes->add('reports/financial_overview', 'Cashflow_reports::financial_overview_input');
$routes->add('reports/financial_overview/(:any)/(:any)', 'Cashflow_reports::financial_overview/$1/$2');

// Accountant module routes
$routes->get('accountant', 'Accountant::getIndex');
$routes->get('accountant/cashflow', 'Accountant::getCashflow');
$routes->get('accountant/accounts', 'Accountant::getAccounts');
$routes->get('accountant/categories', 'Accountant::getCategories');
$routes->get('accountant/category_types', 'Accountant::getCategoryTypes');
$routes->get('accountant/drafts', 'Accountant::getDrafts');
$routes->get('accountant/reports', 'Accountant::getReports');
$routes->get('accountant/financial_overview', 'Accountant::getFinancialOverview');
$routes->get('accountant/account_balance', 'Accountant::getAccountBalance');

// Price offers routes
$routes->get('price_offers', 'Price_offers::getIndex');
$routes->get('price_offers/create', 'Price_offers::getCreate');
$routes->get('price_offers/view/(:num)', 'Price_offers::getView/$1');
$routes->get('price_offers/conditions', 'Price_offers::getConditions');
$routes->post('price_offers/save_condition', 'Price_offers::postSaveCondition');
$routes->post('price_offers/delete_condition', 'Price_offers::postDeleteCondition');
$routes->post('price_offers/save_offer_conditions/(:num)', 'Price_offers::postSaveOfferConditions/$1');

// Manufacturing module routes
$routes->group('manufacturing', function($routes) {
    // Dashboard
    $routes->get('/', 'Manufacturing::getIndex');
    $routes->get('stats', 'Manufacturing::getStats');

    // Projects
    $routes->get('projects', 'Manufacturing_projects::getIndex');
    $routes->get('projects/search', 'Manufacturing_projects::getSearch');
    $routes->get('projects/view/(:num)', 'Manufacturing_projects::getView/$1');
    $routes->get('projects/view', 'Manufacturing_projects::getView');
    $routes->post('projects/save/(:num)', 'Manufacturing_projects::postSave/$1');
    $routes->post('projects/save', 'Manufacturing_projects::postSave');
    $routes->post('projects/delete', 'Manufacturing_projects::postDelete');
    $routes->get('projects/row/(:num)', 'Manufacturing_projects::getRow/$1');

    // Stages
    $routes->post('projects/stages/save', 'Manufacturing_projects::postSaveStage');
    $routes->post('projects/stages/update-status', 'Manufacturing_projects::postUpdateStageStatus');
    $routes->get('projects/stages/delete/(:num)', 'Manufacturing_projects::getDeleteStage/$1');

    // Stock Transfers
    $routes->get('transfers', 'Manufacturing_transfers::getIndex');
    $routes->get('transfers/search', 'Manufacturing_transfers::getSearch');
    $routes->get('transfers/create/(:num)', 'Manufacturing_transfers::getCreate/$1');
    $routes->get('transfers/create', 'Manufacturing_transfers::getCreate');
    $routes->get('transfers/view/(:num)', 'Manufacturing_transfers::getView/$1');
    $routes->post('transfers/save', 'Manufacturing_transfers::postSave');
    $routes->post('transfers/confirm/(:num)', 'Manufacturing_transfers::postConfirm/$1');
    $routes->post('transfers/cancel/(:num)', 'Manufacturing_transfers::postCancel/$1');
    $routes->post('transfers/add-item', 'Manufacturing_transfers::postAddItem');
    $routes->get('transfers/delete-item/(:num)', 'Manufacturing_transfers::getDeleteItem/$1');
    $routes->get('transfers/receipt/(:num)', 'Manufacturing_transfers::getReceipt/$1');
    $routes->get('transfers/item-search', 'Manufacturing_transfers::getItemSearch');

    // Labor Entries
    $routes->get('labor', 'Manufacturing_labor::getIndex');
    $routes->get('labor/search', 'Manufacturing_labor::getSearch');
    $routes->get('labor/create/(:num)', 'Manufacturing_labor::getCreate/$1');
    $routes->get('labor/create', 'Manufacturing_labor::getCreate');
    $routes->get('labor/view/(:num)', 'Manufacturing_labor::getView/$1');
    $routes->post('labor/save', 'Manufacturing_labor::postSave');
    $routes->post('labor/delete', 'Manufacturing_labor::postDelete');

    // Reports
    $routes->get('reports', 'Manufacturing_reports::getIndex');
    $routes->get('reports/project-costs', 'Manufacturing_reports::getProjectCosts');
    $routes->get('reports/material-usage', 'Manufacturing_reports::getMaterialUsage');
    $routes->get('reports/project-progress', 'Manufacturing_reports::getProjectProgress');
    $routes->get('reports/mrp', 'Manufacturing_reports::getMrp');
    $routes->get('reports/cost-variance', 'Manufacturing_reports::getCostVariance');
});

// HR module routes
$routes->group('hr', function($routes) {
    // Dashboard
    $routes->get('/', 'Hr::getIndex');
    
    // Departments
    $routes->get('departments', 'Hr::getDepartments');
    $routes->get('department/(:num)', 'Hr::getDepartment/$1');
    $routes->get('department', 'Hr::getDepartment');
    $routes->post('department/save', 'Hr::postSaveDepartment');
    $routes->post('department/delete', 'Hr::postDeleteDepartment');
    
    // Positions
    $routes->get('positions', 'Hr::getPositions');
    $routes->get('position/(:num)', 'Hr::getPosition/$1');
    $routes->get('position', 'Hr::getPosition');
    $routes->post('position/save', 'Hr::postSavePosition');
    $routes->post('position/delete', 'Hr::postDeletePosition');
    
    // Shifts
    $routes->get('shifts', 'Hr::getShifts');
    $routes->get('shift/(:num)', 'Hr::getShift/$1');
    $routes->get('shift', 'Hr::getShift');
    $routes->post('shift/save', 'Hr::postSaveShift');
    $routes->post('shift/delete', 'Hr::postDeleteShift');
    $routes->post('shift/assign', 'Hr::postAssignShift');
    $routes->get('employee/(:num)/shifts', 'Hr::getEmployeeShifts/$1');
    
    // Employee Profiles
    $routes->get('profiles', 'Hr::getProfiles');
    $routes->get('profile/(:num)', 'Hr::getProfile/$1');
    $routes->get('profile', 'Hr::getProfile');
    $routes->post('profile/save', 'Hr::postSaveProfile');
    
    // Salary Rules
    $routes->get('salary_rules', 'Hr::getSalaryRules');
    $routes->get('salary_rule/(:num)', 'Hr::getSalaryRule/$1');
    $routes->get('salary_rule', 'Hr::getSalaryRule');
    $routes->post('salary_rule/save', 'Hr::postSaveSalaryRule');
    $routes->post('salary_rule/delete', 'Hr::postDeleteSalaryRule');
    $routes->get('salary_rule_groups', 'Hr::getSalaryRuleGroups');
    $routes->post('salary_rule_group/save', 'Hr::postSaveSalaryRuleGroup');
    $routes->get('employee/(:num)/salary_rules', 'Hr::getEmployeeSalaryRules/$1');
    $routes->post('salary_rule/assign', 'Hr::postAssignSalaryRule');
    $routes->post('salary_rule/remove', 'Hr::postRemoveSalaryRule');
    
    // Salary Calculation
    $routes->get('calculate', 'Hr::getCalculate');
    $routes->post('calculate', 'Hr::postCalculate');
    $routes->get('payslip/(:num)/(:any)/(:any)', 'Hr::getPayslip/$1/$2/$3');
    
    // Attendance
    $routes->get('attendance', 'Hr::getAttendance');
    $routes->post('attendance/clock_in', 'Hr::postClockIn');
    $routes->post('attendance/clock_out', 'Hr::postClockOut');
    
    // Leave Management
    $routes->get('leave_requests', 'Hr::getLeaveRequests');
    $routes->get('leave_request/(:num)', 'Hr::getLeaveRequest/$1');
    $routes->get('leave_request', 'Hr::getLeaveRequest');
    $routes->post('leave_request/save', 'Hr::postSaveLeaveRequest');
    $routes->post('leave/approve', 'Hr::postApproveLeave');
    $routes->post('leave/reject', 'Hr::postRejectLeave');
    $routes->get('leave_types', 'Hr::getLeaveTypes');
    $routes->get('leave_type/(:num)', 'Hr::getLeaveType/$1');
    $routes->get('leave_type', 'Hr::getLeaveType');
    $routes->post('leave_type/save', 'Hr::postSaveLeaveType');
});

