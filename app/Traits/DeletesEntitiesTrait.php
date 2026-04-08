<?php

namespace App\Traits;

use CodeIgniter\HTTP\ResponseInterface;

/**
 * Trait for standardized delete functionality in controllers.
 *
 * Provides common patterns for entity deletion used across multiple controllers including:
 * - Bulk deletion
 * - Permission checking
 * - Standard response formatting
 *
 * @see Secure_Controller
 */
trait DeletesEntitiesTrait
{
    /**
     * Get IDs for deletion from POST request.
     *
     * @return array Array of IDs to delete
     */
    protected function getDeleteIds(): array
    {
        $ids = $this->request->getPost('ids');

        if (!is_array($ids)) {
            $ids = $ids !== null ? [$ids] : [];
        }

        // Sanitize all IDs
        return array_map(function ($id) {
            return is_numeric($id) ? (int) $id : $id;
        }, $ids);
    }

    /**
     * Validate that IDs are not empty.
     *
     * @param array $ids The IDs to validate
     * @return bool True if valid
     */
    protected function validateDeleteIds(array $ids): bool
    {
        return !empty($ids);
    }

    /**
     * Build a successful delete response.
     *
     * @param int $count Number of items deleted
     * @param string $singleKey Lang key for singular item (e.g., 'Items.one')
     * @param string $pluralKey Lang key for plural items (e.g., 'Items.multiple')
     * @return ResponseInterface
     */
    protected function deleteSuccessResponse(int $count, string $singleKey, string $pluralKey): ResponseInterface
    {
        $message = lang($singleKey . '.successful_deleted') . ' ' . $count . ' ' . lang($pluralKey . '.one_or_multiple');
        return $this->response->setJSON([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Build a failed delete response.
     *
     * @param string $cannotDeleteKey Lang key for cannot delete message
     * @return ResponseInterface
     */
    protected function deleteErrorResponse(string $cannotDeleteKey): ResponseInterface
    {
        return $this->response->setJSON([
            'success' => false,
            'message' => lang($cannotDeleteKey . '.cannot_be_deleted')
        ]);
    }

    /**
     * Check if user has delete permission.
     *
     * @param string $permissionId The permission ID to check
     * @return bool True if user has permission
     */
    protected function hasDeletePermission(string $permissionId): bool
    {
        $personId = $this->employee->get_logged_in_employee_info()->person_id;
        return $this->employee->has_grant($permissionId, $personId);
    }

    /**
     * Return permission denied response.
     *
     * @param string $messageKey Lang key for permission denied message
     * @return ResponseInterface
     */
    protected function deletePermissionDenied(string $messageKey): ResponseInterface
    {
        return $this->response->setJSON([
            'success' => false,
            'message' => lang($messageKey . '.permission_denied')
        ]);
    }

    /**
     * Execute a soft delete on entities.
     *
     * @param object $model The model to use
     * @param array $ids IDs to delete
     * @param string $deletedField Field to set for soft delete (default 'deleted')
     * @return int Number of records updated
     */
    protected function softDeleteEntities(object $model, array $ids, string $deletedField = 'deleted'): int
    {
        if (empty($ids)) {
            return 0;
        }

        $model->whereIn($model->primaryKey ?? 'id', $ids)
            ->set([$deletedField => 1])
            ->update();

        return $model->affectedRows();
    }

    /**
     * Execute a hard delete on entities.
     *
     * @param object $model The model to use
     * @param array $ids IDs to delete
     * @return int Number of records deleted
     */
    protected function hardDeleteEntities(object $model, array $ids): int
    {
        if (empty($ids)) {
            return 0;
        }

        $model->whereIn($model->primaryKey ?? 'id', $ids)->delete();
        return $model->affectedRows();
    }
}
