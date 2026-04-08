<?php

namespace App\Traits;

use App\Models\Person;
use stdClass;

/**
 * Trait for handling person-related data in controllers.
 *
 * Provides common patterns for handling person data used in:
 * - Customers controller
 * - Employees controller
 * - Suppliers controller
 *
 * @see Persons
 * @see Secure_Controller
 */
trait PersonDataTrait
{
    /**
     * Build person data array from POST request.
     *
     * @return array{
     *     first_name: string,
     *     last_name: string,
     *     gender: int|null,
     *     email: string,
     *     phone_number: string,
     *     address_1: string,
     *     address_2: string,
     *     city: string,
     *     state: string,
     *     zip: string,
     *     country: string,
     *     comments: string
     * }
     */
    protected function buildPersonData(): array
    {
        $firstName = $this->getTrimmedString('first_name');
        $lastName = $this->getTrimmedString('last_name');
        $email = strtolower($this->getTrimmedString('email', true));

        // Format names properly using nameize
        $firstName = $this->nameize($firstName);
        $lastName = $this->nameize($lastName);

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'gender' => $this->getNullableInt('gender'),
            'email' => $email,
            'phone_number' => $this->getTrimmedString('phone_number'),
            'address_1' => $this->getTrimmedString('address_1'),
            'address_2' => $this->getTrimmedString('address_2'),
            'city' => $this->getTrimmedString('city'),
            'state' => $this->getTrimmedString('state'),
            'zip' => $this->getTrimmedString('zip'),
            'country' => $this->getTrimmedString('country'),
            'comments' => $this->getTrimmedString('comments'),
        ];
    }

    /**
     * Create empty stats object for customers/employees.
     *
     * @return stdClass Object with empty stat properties
     */
    protected function createEmptyStats(): stdClass
    {
        $stats = new stdClass();
        $stats->total = 0;
        $stats->min = 0;
        $stats->max = 0;
        $stats->average = 0;
        $stats->avg_discount = 0;
        $stats->quantity = 0;
        return $stats;
    }

    /**
     * Format person name for display.
     *
     * @param string $firstName First name
     * @param string $lastName Last name
     * @return string Full name
     */
    protected function formatPersonName(string $firstName, string $lastName): string
    {
        return trim($firstName . ' ' . $lastName);
    }

    /**
     * Format company name with optional person name fallback.
     *
     * @param string|null $companyName Company name
     * @param string $personName Person name for fallback
     * @return string Display name
     */
    protected function formatCompanyName(?string $companyName, string $personName): string
    {
        $company = trim($companyName ?? '');
        return $company !== '' ? $company : $personName;
    }

    /**
     * Build location string from zip and city.
     *
     * @param string|null $zip ZIP code
     * @param string|null $city City name
     * @return string Location string
     */
    protected function buildLocationString(?string $zip, ?string $city): string
    {
        $zip = trim($zip ?? '');
        $city = trim($city ?? '');

        if (empty($zip) && empty($city)) {
            return '';
        }

        return trim($zip . ' ' . $city);
    }

    /**
     * Capitalize segments of a name, and put the rest into lower case.
     *
     * Handles special cases like O'Grady, McDonald, etc.
     *
     * @param string $input The input name
     * @return string The properly formatted name
     */
    protected function nameize(string $input): string
    {
        if (empty($input)) {
            return '';
        }

        // Use the namecase library if available
        if (function_exists('str_name_case')) {
            $adjusted_name = str_name_case($input);

            // Convert HTML entities to lowercase (workaround for namecase issue)
            return preg_replace_callback('/&[a-zA-Z0-9#]+;/', function ($matches) {
                return strtolower($matches[0]);
            }, $adjusted_name);
        }

        // Fallback: simple capitalization
        return ucwords(strtolower($input));
    }
}
