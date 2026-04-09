<?php

namespace App\Models\Hr;

use CodeIgniter\Model;

class EmployeeAttachment extends Model
{
    protected $table = 'employee_attachments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'employee_id', 'doc_type', 'title', 'file_name', 'file_path',
        'mime_type', 'file_size', 'description', 'expiry_date', 'is_verified',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public const DOC_TYPE_ID = 'id';
    public const DOC_TYPE_PASSPORT = 'passport';
    public const DOC_TYPE_RESUME = 'resume';
    public const DOC_TYPE_CONTRACT = 'contract';
    public const DOC_TYPE_CERTIFICATE = 'certificate';
    public const DOC_TYPE_LICENSE = 'license';
    public const DOC_TYPE_OTHER = 'other';

    public static function getDocTypes(): array
    {
        return [
            self::DOC_TYPE_ID => 'National ID',
            self::DOC_TYPE_PASSPORT => 'Passport',
            self::DOC_TYPE_RESUME => 'Resume/CV',
            self::DOC_TYPE_CONTRACT => 'Employment Contract',
            self::DOC_TYPE_CERTIFICATE => 'Certificate/Degree',
            self::DOC_TYPE_LICENSE => 'Work Permit/License',
            self::DOC_TYPE_OTHER => 'Other',
        ];
    }

    public function get_by_employee(int $employeeId): array
    {
        return $this->where('employee_id', $employeeId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function get_verified(int $employeeId): array
    {
        return $this->where('employee_id', $employeeId)
            ->where('is_verified', 1)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function get_expiring(int $days = 30): array
    {
        $futureDate = date('Y-m-d', strtotime("+{$days} days"));
        return $this->where('expiry_date <=', $futureDate)
            ->where('expiry_date >=', date('Y-m-d'))
            ->where('is_verified', 1)
            ->findAll();
    }

    public function delete_attachment(int $id): bool
    {
        $attachment = $this->find($id);
        if ($attachment) {
            $filePath = FCPATH . $attachment['file_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            return $this->delete($id);
        }
        return false;
    }
}
