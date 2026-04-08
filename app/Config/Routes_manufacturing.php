<?php
/**
 * Manufacturing Module Routes
 *
 * Add these routes to app/Config/Routes.php
 */

// Manufacturing Module Routes
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
