<?php

namespace App\Traits;

use CodeIgniter\HTTP\ResponseInterface;

/**
 * Trait for standardized input validation in controllers.
 *
 * Provides common validation patterns used across multiple controllers including:
 * - Input sanitization helpers
 * - Standard validation responses
 * - Common validation rules
 *
 * @see Secure_Controller
 */
trait ValidatesInputTrait
{
    /**
     * Get a sanitized POST value with optional filter.
     *
     * @param string $key The POST key
     * @param int|null $filter PHP filter constant (e.g., FILTER_SANITIZE_NUMBER_INT)
     * @param mixed $default Default value if not set
     * @return mixed The sanitized value
     */
    protected function getPost(string $key, ?int $filter = null, mixed $default = null): mixed
    {
        $value = $this->request->getPost($key, $filter);
        return $value !== null ? $value : $default;
    }

    /**
     * Get a sanitized GET value with optional filter.
     *
     * @param string $key The GET key
     * @param int|null $filter PHP filter constant
     * @param mixed $default Default value if not set
     * @return mixed The sanitized value
     */
    protected function getQuery(string $key, ?int $filter = null, mixed $default = null): mixed
    {
        $value = $this->request->getGet($key, $filter);
        return $value !== null ? $value : $default;
    }

    /**
     * Get a nullable integer from POST data.
     *
     * @param string $key The POST key
     * @return int|null The integer value or null
     */
    protected function getNullableInt(string $key): ?int
    {
        $value = $this->request->getPost($key, FILTER_SANITIZE_NUMBER_INT);
        if ($value === null || $value === '' || $value === false) {
            return null;
        }
        return (int) $value;
    }

    /**
     * Get a decimal/float value from POST data.
     *
     * @param string $key The POST key
     * @param float $default Default value
     * @return float The parsed decimal value
     */
    protected function getDecimal(string $key, float $default = 0.0): float
    {
        $value = $this->request->getPost($key);
        $parsed = parse_decimals($value);
        return $parsed !== false ? (float) $parsed : $default;
    }

    /**
     * Get a trimmed string from POST data.
     *
     * @param string $key The POST key
     * @param bool $sanitize Whether to apply FILTER_SANITIZE_FULL_SPECIAL_CHARS
     * @return string The trimmed string
     */
    protected function getTrimmedString(string $key, bool $sanitize = true): string
    {
        $value = $sanitize
            ? $this->request->getPost($key, FILTER_SANITIZE_FULL_SPECIAL_CHARS)
            : $this->request->getPost($key);
        return $value !== null ? trim((string) $value) : '';
    }

    /**
     * Get a boolean from POST data (checkbox style).
     *
     * @param string $key The POST key
     * @return bool True if the key exists and is truthy
     */
    protected function getCheckbox(string $key): bool
    {
        return $this->request->getPost($key) !== null;
    }

    /**
     * Build a success JSON response.
     *
     * @param string $message Success message
     * @param mixed $id Optional ID of the saved entity
     * @param array $additionalData Additional data to include
     * @return ResponseInterface
     */
    protected function successResponse(string $message, mixed $id = null, array $additionalData = []): ResponseInterface
    {
        $response = ['success' => true, 'message' => $message];
        if ($id !== null) {
            $response['id'] = $id;
        }
        return $this->response->setJSON(array_merge($response, $additionalData));
    }

    /**
     * Build an error JSON response.
     *
     * @param string $message Error message
     * @param mixed $id Optional ID (usually NEW_ENTRY for errors)
     * @param array $additionalData Additional data to include
     * @return ResponseInterface
     */
    protected function errorResponse(string $message, mixed $id = null, array $additionalData = []): ResponseInterface
    {
        $response = ['success' => false, 'message' => $message];
        if ($id !== null) {
            $response['id'] = $id;
        }
        return $this->response->setJSON(array_merge($response, $additionalData));
    }

    /**
     * Validate required fields are present.
     *
     * @param array $fields List of required field names
     * @return array Array of missing field names (empty if all present)
     */
    protected function validateRequired(array $fields): array
    {
        $missing = [];
        foreach ($fields as $field) {
            if ($this->request->getPost($field) === null || $this->request->getPost($field) === '') {
                $missing[] = $field;
            }
        }
        return $missing;
    }

    /**
     * Check if an ID represents a new entry.
     *
     * @param int $id The ID to check
     * @return bool True if this is a new entry
     */
    protected function isNewEntry(int $id): bool
    {
        return $id === NEW_ENTRY || $id <= 0;
    }
}
