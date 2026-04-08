<?php

namespace App\Controllers;

use App\Libraries\Manufacturing_lib;
use App\Models\Manufacturing_project;
use App\Traits\SearchableTrait;
use App\Traits\ValidatesInputTrait;
use CodeIgniter\HTTP\ResponseInterface;

require_once('Secure_Controller.php');

/**
 * Manufacturing Controller
 *
 * Main controller for the Manufacturing module
 */
class Manufacturing extends Secure_Controller
{
    use SearchableTrait;
    use ValidatesInputTrait;

    private Manufacturing_lib $manufacturing_lib;
    private Manufacturing_project $projectModel;

    public function __construct()
    {
        parent::__construct('manufacturing');
        $this->manufacturing_lib = new Manufacturing_lib();
        $this->projectModel = model(Manufacturing_project::class);
    }

    /**
     * Dashboard view
     */
    public function getIndex(): string
    {
        $stats = $this->manufacturing_lib->get_dashboard_stats();

        $data = [
            'stats' => $stats,
            'controller_name' => 'manufacturing'
        ];

        return view('manufacturing/dashboard', $data);
    }

    /**
     * Get dashboard stats via AJAX
     */
    public function getStats(): ResponseInterface
    {
        $stats = $this->manufacturing_lib->get_dashboard_stats();
        return $this->response->setJSON($stats);
    }
}
