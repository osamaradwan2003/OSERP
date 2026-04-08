<?php

namespace App\Models;

use CodeIgniter\Model;

class Manufacturing_overhead_rate extends Model
{
    protected $table = 'manufacturing_overhead_rates';
    protected $primaryKey = 'rate_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'rate_name',
        'rate_type',
        'rate_value',
        'applies_to',
        'is_active',
        'effective_from',
        'effective_to'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all active rates
     */
    public function get_active(): array
    {
        $today = date('Y-m-d');
        return $this->where('is_active', 1)
                    ->where('effective_from <=', $today)
                    ->groupStart()
                    ->where('effective_to IS NULL')
                    ->orWhere('effective_to >=', $today)
                    ->groupEnd()
                    ->findAll();
    }

    /**
     * Calculate overhead for a project
     */
    public function calculate_overhead(float $material_cost, float $labor_cost, float $total_hours): float
    {
        $rates = $this->get_active();
        $overhead = 0.0;

        foreach ($rates as $rate) {
            switch ($rate['applies_to']) {
                case 'material_cost':
                    if ($rate['rate_type'] === 'percentage') {
                        $overhead += $material_cost * ($rate['rate_value'] / 100);
                    }
                    break;

                case 'labor_cost':
                    if ($rate['rate_type'] === 'percentage') {
                        $overhead += $labor_cost * ($rate['rate_value'] / 100);
                    }
                    break;

                case 'total_cost':
                    if ($rate['rate_type'] === 'percentage') {
                        $overhead += ($material_cost + $labor_cost) * ($rate['rate_value'] / 100);
                    }
                    break;

                case 'per_hour':
                    if ($rate['rate_type'] === 'fixed_per_hour') {
                        $overhead += $total_hours * $rate['rate_value'];
                    }
                    break;
            }
        }

        return $overhead;
    }

    /**
     * Get rate by name
     */
    public function get_by_name(string $name): array
    {
        return $this->where('rate_name', $name)->first() ?? [];
    }
}
