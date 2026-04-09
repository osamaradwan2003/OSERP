<?php

namespace App\Controllers;

use App\Models\Employee;
use function Tamtamchik\NameCase\str_name_case;
use App\Models\Hr\Attendance;
use App\Models\Hr\Department;
use App\Models\Hr\EmployeeLeaveBalance;
use App\Models\Hr\EmployeeAttachment;
use App\Models\Hr\EmployeeProfile;
use App\Models\Hr\EmployeeSalaryRule;
use App\Models\Hr\EmployeeShift;
use App\Models\Hr\LeaveRequest;
use App\Models\Hr\LeaveType;
use App\Models\Hr\Position;
use App\Models\Hr\SalaryComponent;
use App\Models\Hr\SalaryRule;
use App\Models\Hr\SalaryRuleGroup;
use App\Models\Hr\Shift;
use App\Services\Hr\SalaryCalculator;
use CodeIgniter\HTTP\ResponseInterface;

class Hr extends Secure_Controller
{
    private Department $department;
    private Position $position;
    private Shift $shift;
    private EmployeeProfile $employeeProfile;
    private EmployeeSalaryRule $employeeSalaryRule;
    private EmployeeShift $employeeShift;
    private SalaryRule $salaryRule;
    private SalaryRuleGroup $salaryRuleGroup;
    private SalaryComponent $salaryComponent;
    private Attendance $attendance;
    private LeaveType $leaveType;
    private LeaveRequest $leaveRequest;
    private EmployeeLeaveBalance $leaveBalance;
    private SalaryCalculator $salaryCalculator;
    private EmployeeAttachment $employeeAttachment;
    protected Employee $employee;

    public function __construct()
    {
        parent::__construct('hr');

        $this->department = new Department();
        $this->position = new Position();
        $this->shift = new Shift();
        $this->employeeProfile = new EmployeeProfile();
        $this->employeeSalaryRule = new EmployeeSalaryRule();
        $this->employeeShift = new EmployeeShift();
        $this->salaryRule = new SalaryRule();
        $this->salaryRuleGroup = new SalaryRuleGroup();
        $this->salaryComponent = new SalaryComponent();
        $this->attendance = new Attendance();
        $this->leaveType = new LeaveType();
        $this->leaveRequest = new LeaveRequest();
        $this->leaveBalance = new EmployeeLeaveBalance();
        $this->salaryCalculator = new SalaryCalculator();
        $this->employeeAttachment = new EmployeeAttachment();
        $this->employee = new Employee();
    }

    protected function nameize(string $input): string
    {
        if (empty($input)) {
            return '';
        }

        if (function_exists('str_name_case')) {
            $adjusted_name = str_name_case($input);

            return preg_replace_callback('/&[a-zA-Z0-9#]+;/', function ($matches) {
                return strtolower($matches[0]);
            }, $adjusted_name);
        }

        return ucwords(strtolower($input));
    }

    public function getIndex(): string
    {
        $data = $this->get_dashboard_data();
        return view('hr/dashboard', $data);
    }

    private function get_dashboard_data(): array
    {
        $currentUser = $this->employee->get_logged_in_employee_info();

        return [
            'total_departments' => count($this->department->get_all_active()),
            'total_positions' => count($this->position->get_all_active()),
            'total_shifts' => count($this->shift->get_all_active()),
            'total_salary_rules' => count($this->salaryRule->get_all_active()),
            'pending_leave_requests' => count($this->leaveRequest->get_pending_requests()),
            'recent_attendance' => $this->attendance->get_by_date(date('Y-m-d')),
        ];
    }

    // ============ Departments ============

    public function getDepartments(): string
    {
        $data['departments'] = $this->department->get_with_parents();
        return view('hr/departments/manage', $data);
    }

    public function getDepartment(int $id = 0): string
    {
        $data['department'] = $id ? $this->department->find($id) : null;
        $data['parent_options'] = ['' => lang('Common.none_selected_text')] + $this->department->get_options();
        return view('hr/departments/form', $data);
    }

    public function postSaveDepartment(): ResponseInterface
    {
        $id = $this->request->getPost('id', FILTER_SANITIZE_NUMBER_INT) ?: null;

        $data = [
            'name' => $this->request->getPost('name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'description' => $this->request->getPost('description', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'parent_id' => $this->request->getPost('parent_id', FILTER_SANITIZE_NUMBER_INT) ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($id) {
            $this->department->update($id, $data);
            $message = lang('Common.successful_update');
        } else {
            $this->department->insert($data);
            $id = $this->department->getInsertID();
            $message = lang('Common.successful_adding');
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $message,
            'id' => $id
        ]);
    }

    public function postDeleteDepartment(): ResponseInterface
    {
        $ids = $this->request->getPost('ids', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (empty($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Common.none_selected')]);
        }

        foreach ($ids as $id) {
            $this->department->delete($id);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => lang('Common.successful_delete')
        ]);
    }

    // ============ Positions ============

    public function getPositions(): string
    {
        $data['positions'] = $this->position->get_with_department();
        return view('hr/positions/manage', $data);
    }

    public function getPosition(int $id = 0): string
    {
        $data['position'] = $id ? $this->position->find($id) : null;
        $data['department_options'] = ['' => lang('Common.none_selected_text')] + $this->department->get_options();
        return view('hr/positions/form', $data);
    }

    public function postSavePosition(): ResponseInterface
    {
        $id = $this->request->getPost('id', FILTER_SANITIZE_NUMBER_INT) ?: null;

        $data = [
            'name' => $this->request->getPost('name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'description' => $this->request->getPost('description', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'department_id' => $this->request->getPost('department_id', FILTER_SANITIZE_NUMBER_INT) ?: null,
            'level' => $this->request->getPost('level', FILTER_SANITIZE_NUMBER_INT) ?: 1,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($id) {
            $this->position->update($id, $data);
            $message = lang('Common.successful_update');
        } else {
            $this->position->insert($data);
            $id = $this->position->getInsertID();
            $message = lang('Common.successful_adding');
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $message,
            'id' => $id
        ]);
    }

    public function postDeletePosition(): ResponseInterface
    {
        $ids = $this->request->getPost('ids', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (empty($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Common.none_selected')]);
        }

        foreach ($ids as $id) {
            $this->position->delete($id);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => lang('Common.successful_delete')
        ]);
    }

    // ============ Shifts ============

    public function getShifts(): string
    {
        $data['shifts'] = $this->shift->get_all_active();
        return view('hr/shifts/manage', $data);
    }

    public function getShift(int $id = 0): string
    {
        $data['shift'] = $id ? $this->shift->find($id) : null;
        return view('hr/shifts/form', $data);
    }

    public function postSaveShift(): ResponseInterface
    {
        $id = $this->request->getPost('id', FILTER_SANITIZE_NUMBER_INT) ?: null;

        $data = [
            'name' => $this->request->getPost('name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'code' => $this->request->getPost('code', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'grace_period_minutes' => $this->request->getPost('grace_period_minutes', FILTER_SANITIZE_NUMBER_INT) ?: 0,
            'working_hours' => $this->request->getPost('working_hours') ?: 8.00,
            'overtime_threshold_minutes' => $this->request->getPost('overtime_threshold_minutes', FILTER_SANITIZE_NUMBER_INT) ?: 0,
            'night_shift_start' => $this->request->getPost('night_shift_start') ?: null,
            'night_shift_end' => $this->request->getPost('night_shift_end') ?: null,
            'is_night_shift' => $this->request->getPost('is_night_shift') ? 1 : 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($id) {
            $this->shift->update($id, $data);
            $message = lang('Common.successful_update');
        } else {
            $this->shift->insert($data);
            $id = $this->shift->getInsertID();
            $message = lang('Common.successful_adding');
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $message,
            'id' => $id
        ]);
    }

    public function postDeleteShift(): ResponseInterface
    {
        $ids = $this->request->getPost('ids', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (empty($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Common.none_selected')]);
        }

        foreach ($ids as $id) {
            $this->shift->delete($id);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => lang('Common.successful_delete')
        ]);
    }

    // ============ Employee Profiles ============

    public function getProfiles(): string
    {
        $data['profiles'] = $this->employeeProfile->get_all_with_details();
        return view('hr/profiles/manage', $data);
    }

    public function getProfile(int $employeeId = 0)
    {
        $profile = $this->employeeProfile->get_info($employeeId);
        $employee = $this->employee->get_info($employeeId);

        if (!$employee) {
            return redirect()->to('hr/profiles')->with('error', lang('Hr.employee_not_found'));
        }

        $data = [
            'employee' => $employee,
            'profile' => $profile,
            'department_options' => ['' => lang('Common.none_selected_text')] + $this->department->get_options(),
            'position_options' => ['' => lang('Common.none_selected_text')] + $this->position->get_options(),
            'shift_options' => ['' => lang('Common.none_selected_text')] + $this->shift->get_simple_options(),
        ];

        return view('hr/profiles/form', $data);
    }

    public function postSaveProfile(): ResponseInterface
    {
        $employeeId = $this->request->getPost('employee_id', FILTER_SANITIZE_NUMBER_INT);

        $data = [
            'employee_id' => $employeeId,
            'department_id' => $this->request->getPost('department_id', FILTER_SANITIZE_NUMBER_INT) ?: null,
            'position_id' => $this->request->getPost('position_id', FILTER_SANITIZE_NUMBER_INT) ?: null,
            'shift_id' => $this->request->getPost('shift_id', FILTER_SANITIZE_NUMBER_INT) ?: null,
            'employee_number' => $this->request->getPost('employee_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'basic_salary' => $this->request->getPost('basic_salary') ?: 0,
            'hourly_rate' => $this->request->getPost('hourly_rate') ?: 0,
            'hire_date' => $this->request->getPost('hire_date') ?: null,
            'termination_date' => $this->request->getPost('termination_date') ?: null,
            'employment_type' => $this->request->getPost('employment_type') ?: 'full_time',
            'employment_status' => $this->request->getPost('employment_status') ?: 'active',
            'bank_name' => $this->request->getPost('bank_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'bank_account' => $this->request->getPost('bank_account', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'tax_id' => $this->request->getPost('tax_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'social_security_number' => $this->request->getPost('social_security_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        ];

        $existing = $this->employeeProfile->exists($employeeId);

        if ($existing) {
            $this->employeeProfile->where('employee_id', $employeeId)->set($data)->update();
            $message = lang('Common.successful_update');
        } else {
            $this->employeeProfile->insert($data);
            $message = lang('Common.successful_adding');
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $message,
            'id' => $employeeId
        ]);
    }

    // ============ Employee Management ============

    public function getEmployees(): string
    {
        $data['employees'] = $this->employeeProfile->get_all_with_details();
        return view('hr/employees/manage', $data);
    }

    public function getEmployee(int $employeeId = 0): string
    {
        $person_info = null;
        $profile = [];

        if ($employeeId > 0) {
            $person_info = $this->employee->get_info($employeeId);
            $profile = $this->employeeProfile->get_info($employeeId) ?? [];
        }

        if (!$person_info) {
            $person_info = (object)[
                'person_id' => '',
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'phone_number' => '',
                'address' => '',
                'city' => '',
                'state' => '',
                'zip' => '',
                'country' => '',
                'comments' => '',
                'username' => '',
                'language' => ''
            ];
        }

        $data = [
            'employee_id' => $employeeId ?: 0,
            'person_info' => $person_info,
            'profile' => $profile,
            'department_options' => $this->department->get_options(),
            'position_options' => $this->position->get_options(),
            'shift_options' => $this->shift->get_simple_options(),
            'attachments' => $employeeId > 0 ? $this->employeeAttachment->get_by_employee($employeeId) : [],
        ];

        return view('hr/employees/form', $data);
    }

    public function getEmployeeInfo(int $employeeId)
    {
        $employee = $this->employee->get_info($employeeId);
        
        if (!$employee) {
            return redirect()->to('hr/employees')->with('error', lang('Hr.employee_not_found'));
        }

        $employeeArray = (array) $employee;
        $profile = $this->employeeProfile->get_info($employeeId) ?? [];
        $attachments = $this->employeeAttachment->get_by_employee($employeeId);
        
        // Get attendance history (last 30 days)
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        $attendance_history = $this->attendance->get_by_employee($employeeId, $startDate, $endDate);
        
        // Get leave history
        $leave_history = $this->leaveRequest->get_employee_requests($employeeId);
        
        // Get leave balances for this employee
        $leave_balances = $this->getEmployeeLeaveBalances($employeeId);
        
        // Get recent salary (if salary calculator service exists)
        $recent_salary = [];
        if (isset($this->salaryCalculator)) {
            $periodStart = date('Y-m-01', strtotime('-1 month'));
            $periodEnd = date('Y-m-t', strtotime('-1 month'));
            $recent_salary = $this->salaryCalculator->calculate($employeeId, $periodStart, $periodEnd);
            $recent_salary = $recent_salary['success'] ? $recent_salary['payslip'] ?? [] : [];
        }

        // Country options for inline edit
        $countries = [
            'United States' => 'United States',
            'United Kingdom' => 'United Kingdom',
            'Canada' => 'Canada',
            'Australia' => 'Australia',
            'Germany' => 'Germany',
            'France' => 'France',
            'Spain' => 'Spain',
            'Italy' => 'Italy',
            'Netherlands' => 'Netherlands',
            'UAE' => 'United Arab Emirates',
            'Saudi Arabia' => 'Saudi Arabia',
            'Other' => 'Other',
        ];

        $data = [
            'employee' => $employeeArray,
            'profile' => $profile,
            'attachments' => $attachments,
            'attendance_history' => $attendance_history,
            'leave_history' => $leave_history,
            'leave_balances' => $leave_balances,
            'recent_salary' => $recent_salary,
            'countries' => $countries,
            'department_options' => $this->department->get_options(),
            'position_options' => $this->position->get_options(),
            'shift_options' => $this->shift->get_simple_options(),
        ];

        return view('hr/employees/info', $data);
    }

    public function getEmployeePdfPreview(int $employeeId)
    {
        $employee = $this->employee->get_info($employeeId);
        
        if (!$employee) {
            return redirect()->to('hr/employees')->with('error', lang('Hr.employee_not_found'));
        }

        $employeeArray = (array) $employee;
        $profile = $this->employeeProfile->get_info($employeeId) ?? [];
        $attachments = $this->employeeAttachment->get_by_employee($employeeId);
        
        // Get recent salary
        $recent_salary = [];
        if (isset($this->salaryCalculator)) {
            $periodStart = date('Y-m-01', strtotime('-1 month'));
            $periodEnd = date('Y-m-t', strtotime('-1 month'));
            $recent_salary = $this->salaryCalculator->calculate($employeeId, $periodStart, $periodEnd);
            $recent_salary = $recent_salary['success'] ? $recent_salary['payslip'] ?? [] : [];
        }

        $data = [
            'employee' => $employeeArray,
            'profile' => $profile,
            'attachments' => $attachments,
            'recent_salary' => $recent_salary,
        ];

        return view('hr/employees/pdf_template', $data);
    }

    public function getEmployeePdf(int $employeeId)
    {
        $employee = $this->employee->get_info($employeeId);
        
        if (!$employee) {
            return redirect()->to('hr/employees')->with('error', lang('Hr.employee_not_found'));
        }

        $employeeArray = (array) $employee;
        $profile = $this->employeeProfile->get_info($employeeId) ?? [];
        $attachments = $this->employeeAttachment->get_by_employee($employeeId);
        
        // Get recent salary
        $recent_salary = [];
        if (isset($this->salaryCalculator)) {
            $periodStart = date('Y-m-01', strtotime('-1 month'));
            $periodEnd = date('Y-m-t', strtotime('-1 month'));
            $recent_salary = $this->salaryCalculator->calculate($employeeId, $periodStart, $periodEnd);
            $recent_salary = $recent_salary['success'] ? $recent_salary['payslip'] ?? [] : [];
        }

        $data = [
            'employee' => $employeeArray,
            'profile' => $profile,
            'attachments' => $attachments,
            'recent_salary' => $recent_salary,
        ];

        $html = view('hr/employees/pdf_template', $data);
        
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = 'Employee_' . preg_replace('/[^a-zA-Z0-9]/', '_', $employeeArray['first_name'] . '_' . $employeeArray['last_name']) . '_' . date('Ymd') . '.pdf';
        
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }

    public function getEmployeeAttachmentsZip(int $employeeId): ResponseInterface
    {
        $employee = $this->employee->get_info($employeeId);
        
        if (!$employee) {
            return redirect()->to('hr/employees')->with('error', lang('Hr.employee_not_found'));
        }

        $attachments = $this->employeeAttachment->get_by_employee($employeeId);
        
        if (empty($attachments)) {
            return redirect()->to('hr/employee/info/' . $employeeId)->with('error', lang('Hr.no_attachments'));
        }

        $employeeArray = (array) $employee;
        $zipFilename = 'Employee_' . preg_replace('/[^a-zA-Z0-9]/', '_', $employeeArray['first_name'] . '_' . $employeeArray['last_name']) . '_Documents_' . date('Ymd') . '.zip';
        
        $zip = new \ZipArchive();
        $zipPath = WRITEPATH . 'temp/' . $zipFilename;
        
        if (!is_dir(WRITEPATH . 'temp')) {
            mkdir(WRITEPATH . 'temp', 0755, true);
        }
        
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            // Add index file with employee info
            $indexContent = "EMPLOYEE DOCUMENTS INDEX\n";
            $indexContent .= "========================\n\n";
            $indexContent .= "Employee: " . $employeeArray['first_name'] . ' ' . $employeeArray['last_name'] . "\n";
            $indexContent .= "Employee Number: " . ($this->employeeProfile->get_info($employeeId)['employee_number'] ?? 'N/A') . "\n";
            $indexContent .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";
            $indexContent .= "DOCUMENTS:\n";
            $indexContent .= "----------\n\n";
            
            foreach ($attachments as $i => $attachment) {
                $filePath = FCPATH . $attachment['file_path'];
                if (file_exists($filePath)) {
                    $extension = pathinfo($attachment['file_name'], PATHINFO_EXTENSION);
                    $newFilename = ($i + 1) . '_' . lang('Hr.doc_type_' . $attachment['doc_type']) . '_' . $attachment['file_name'];
                    $zip->addFile($filePath, $newFilename);
                    
                    $indexContent .= ($i + 1) . ". " . lang('Hr.doc_type_' . $attachment['doc_type']) . "\n";
                    $indexContent .= "   Title: " . $attachment['title'] . "\n";
                    $indexContent .= "   File: " . $newFilename . "\n";
                    $indexContent .= "   Expiry: " . ($attachment['expiry_date'] ? date('Y-m-d', strtotime($attachment['expiry_date'])) : 'N/A') . "\n";
                    $indexContent .= "   Verified: " . ($attachment['is_verified'] ? 'Yes' : 'No') . "\n\n";
                }
            }
            
            $zip->addFromString('index.txt', $indexContent);
            $zip->close();
            
            return $this->response
                ->setHeader('Content-Type', 'application/zip')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $zipFilename . '"')
                ->setBody(file_get_contents($zipPath));
        }
        
        return redirect()->to('hr/employee/info/' . $employeeId)->with('error', lang('Common.error'));
    }

    public function postSaveEmployee(int $employeeId = 0): ResponseInterface
    {
        $first_name = $this->request->getPost('first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last_name = $this->request->getPost('last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = strtolower($this->request->getPost('email', FILTER_SANITIZE_EMAIL));

        $first_name = $this->nameize($first_name);
        $last_name = $this->nameize($last_name);

        $person_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone_number' => $this->request->getPost('phone_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'address_1' => $this->request->getPost('address', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'city' => $this->request->getPost('city', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'state' => $this->request->getPost('state', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'zip' => $this->request->getPost('zip', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'country' => $this->request->getPost('country', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'comments' => $this->request->getPost('comments', FILTER_SANITIZE_FULL_SPECIAL_CHARS)
        ];

        $username = $this->request->getPost('username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = $this->request->getPost('password');
        $language = $this->request->getPost('language', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $has_login_account = $this->request->getPost('has_login_account') == 1;

        $employee_data = [];
        if ($has_login_account && !empty($username)) {
            $exploded = explode(':', $language);
            $employee_data = [
                'username' => $username,
                'language_code' => $exploded[0] ?? '',
                'language' => $exploded[1] ?? ''
            ];

            if (!empty($password)) {
                $employee_data['password'] = password_hash($password, PASSWORD_DEFAULT);
                $employee_data['hash_version'] = 2;
            }
        }

        $grants_array = [];
        if ($employeeId == 0 && $has_login_account) {
            $grants_array[] = ['permission_id' => 'hr', 'menu_group' => 'hr'];
        }

        $saved = $this->employee->save_employee($person_data, $employee_data, $grants_array, $employeeId);

        if ($saved) {
            $employee_id = $employeeId > 0 ? $employeeId : ($person_data['person_id'] ?? 0);

            $profile_data = [
                'employee_id' => $employee_id,
                'department_id' => $this->request->getPost('department_id', FILTER_SANITIZE_NUMBER_INT) ?: null,
                'position_id' => $this->request->getPost('position_id', FILTER_SANITIZE_NUMBER_INT) ?: null,
                'shift_id' => $this->request->getPost('shift_id', FILTER_SANITIZE_NUMBER_INT) ?: null,
                'employee_number' => $this->request->getPost('employee_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'basic_salary' => $this->request->getPost('basic_salary') ?: 0,
                'hourly_rate' => $this->request->getPost('hourly_rate') ?: 0,
                'hire_date' => $this->request->getPost('hire_date') ?: null,
                'employment_type' => $this->request->getPost('employment_type') ?: 'full_time',
                'employment_status' => 'active',
                'bank_name' => $this->request->getPost('bank_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'bank_account' => $this->request->getPost('bank_account', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'tax_id' => $this->request->getPost('tax_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'social_security_number' => $this->request->getPost('social_security_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            ];

            if ($this->employeeProfile->exists($employee_id)) {
                $this->employeeProfile->update($employee_id, $profile_data);
            } else {
                $this->employeeProfile->insert($profile_data);
            }

            $message = $employeeId > 0 ? lang('Common.successful_update') : lang('Common.successful_adding');

            return $this->response->setJSON([
                'success' => true,
                'message' => $message,
                'id' => $employee_id
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => lang('Common.error') . ' ' . $first_name . ' ' . $last_name,
            'id' => -1
        ]);
    }

    // ============ Employee Attachments ============

    public function postUploadAttachment(): ResponseInterface
    {
        $employeeId = $this->request->getPost('employee_id', FILTER_SANITIZE_NUMBER_INT);
        
        if (!$employeeId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Hr.employee_not_found')
            ]);
        }

        $file = $this->request->getFile('attachment_file');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Hr.please_select_file')
            ]);
        }

        $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
        $extension = $file->getExtension();
        
        if (!in_array(strtolower($extension), $allowedTypes)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Hr.allowed_file_types')
            ]);
        }

        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file->getSize() > $maxSize) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Hr.max_file_size')
            ]);
        }

        $uploadDir = FCPATH . 'uploads/employee_attachments/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $originalName = $file->getName();
        $newName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $filePath = 'uploads/employee_attachments/' . $newName;
        $fullPath = $uploadDir . $newName;

        if ($file->move($uploadDir, $newName)) {
            $movedFile = new \CodeIgniter\Files\File($fullPath);
            
            $data = [
                'employee_id' => $employeeId,
                'doc_type' => $this->request->getPost('doc_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'title' => $this->request->getPost('title', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'file_name' => $originalName,
                'file_path' => $filePath,
                'mime_type' => $movedFile->getMimeType(),
                'file_size' => filesize($fullPath),
                'expiry_date' => $this->request->getPost('expiry_date') ?: null,
                'is_verified' => 0,
            ];

            $this->employeeAttachment->insert($data);
            $id = $this->employeeAttachment->getInsertID();

            return $this->response->setJSON([
                'success' => true,
                'message' => lang('Hr.attachment_uploaded'),
                'id' => $id,
                'file_name' => $originalName,
                'file_size' => filesize($fullPath)
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => lang('Common.error')
        ]);
    }

    public function postDeleteAttachment(): ResponseInterface
    {
        $id = $this->request->getPost('id', FILTER_SANITIZE_NUMBER_INT);
        
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Common.error')
            ]);
        }

        $attachment = $this->employeeAttachment->find($id);
        
        if (!$attachment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Common.error')
            ]);
        }

        $filePath = FCPATH . $attachment['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $this->employeeAttachment->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => lang('Hr.attachment_deleted')
        ]);
    }

    public function getDownloadAttachment(int $id): ResponseInterface
    {
        $attachment = $this->employeeAttachment->find($id);
        
        if (!$attachment) {
            return redirect()->to('hr/employees')->with('error', lang('Common.error'));
        }

        $filePath = FCPATH . $attachment['file_path'];
        
        if (!file_exists($filePath)) {
            return redirect()->to('hr/employees')->with('error', lang('Common.error'));
        }

        return $this->response
            ->setHeader('Content-Type', $attachment['mime_type'])
            ->setHeader('Content-Disposition', 'attachment; filename="' . $attachment['file_name'] . '"')
            ->setHeader('Content-Length', filesize($filePath))
            ->setBody(file_get_contents($filePath));
    }

    // ============ Salary Rules ============

    public function getSalaryRules(): string
    {
        $data['rules'] = $this->salaryRule->get_all_with_group();
        $data['groups'] = $this->salaryRuleGroup->get_all_active();
        return view('hr/salary_rules/manage', $data);
    }

    public function getSalaryRule(int $id = 0): string
    {
        $rule = $id ? $this->salaryRule->find($id) : null;

        $data = [
            'rule' => $rule,
            'group_options' => $this->salaryRuleGroup->get_options(),
            'department_options' => ['' => lang('Common.none_selected_text')] + $this->department->get_options(),
            'position_options' => ['' => lang('Common.none_selected_text')] + $this->position->get_options(),
        ];

        return view('hr/salary_rules/form', $data);
    }

    public function postSaveSalaryRule(): ResponseInterface
    {
        $id = $this->request->getPost('id', FILTER_SANITIZE_NUMBER_INT) ?: null;

        $conditions = $this->request->getPost('conditions');
        if ($conditions) {
            $conditions = json_decode($conditions, true);
        }

        $data = [
            'group_id' => $this->request->getPost('group_id', FILTER_SANITIZE_NUMBER_INT),
            'name' => $this->request->getPost('name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'code' => $this->request->getPost('code', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'rule_type' => $this->request->getPost('rule_type'),
            'value' => $this->request->getPost('value') ?: 0,
            'formula' => $this->request->getPost('formula'),
            'based_on' => $this->request->getPost('based_on') ?: 'none',
            'conditions' => $conditions ? json_encode($conditions) : null,
            'attendance_type' => $this->request->getPost('attendance_type') ?: null,
            'attendance_rate' => $this->request->getPost('attendance_rate') ?: 1.00,
            'scope' => $this->request->getPost('scope') ?: 'global',
            'scope_id' => $this->request->getPost('scope_id', FILTER_SANITIZE_NUMBER_INT) ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'is_recurring' => $this->request->getPost('is_recurring') ? 1 : 0,
            'priority' => $this->request->getPost('priority', FILTER_SANITIZE_NUMBER_INT) ?: 0,
            'description' => $this->request->getPost('description', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        ];

        if ($id) {
            $this->salaryRule->update($id, $data);
            $message = lang('Common.successful_update');
        } else {
            $this->salaryRule->insert($data);
            $id = $this->salaryRule->getInsertID();
            $message = lang('Common.successful_adding');
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $message,
            'id' => $id
        ]);
    }

    public function postDeleteSalaryRule(): ResponseInterface
    {
        $ids = $this->request->getPost('ids', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (empty($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Common.none_selected')]);
        }

        foreach ($ids as $id) {
            $this->salaryRule->delete($id);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => lang('Common.successful_delete')
        ]);
    }

    // ============ Salary Rule Groups ============

    public function getSalaryRuleGroups(): string
    {
        $data['groups'] = $this->salaryRuleGroup->findAll();
        return view('hr/salary_rules/groups', $data);
    }

    public function postSaveSalaryRuleGroup(): ResponseInterface
    {
        $id = $this->request->getPost('id', FILTER_SANITIZE_NUMBER_INT) ?: null;

        $data = [
            'name' => $this->request->getPost('name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'type' => $this->request->getPost('type'),
            'calculation_order' => $this->request->getPost('calculation_order', FILTER_SANITIZE_NUMBER_INT) ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($id) {
            $this->salaryRuleGroup->update($id, $data);
        } else {
            $this->salaryRuleGroup->insert($data);
            $id = $this->salaryRuleGroup->getInsertID();
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => lang('Common.successful_update'),
            'id' => $id
        ]);
    }

    // ============ Salary Calculation ============

    public function getCalculate(): string
    {
        $data['employees'] = $this->employeeProfile->get_all_with_details();
        return view('hr/salary/calculate', $data);
    }

    public function postCalculate(): ResponseInterface
    {
        $employeeId = $this->request->getPost('employee_id', FILTER_SANITIZE_NUMBER_INT);
        $periodStart = $this->request->getPost('period_start');
        $periodEnd = $this->request->getPost('period_end');

        if (!$employeeId || !$periodStart || !$periodEnd) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Hr.fill_required_fields')
            ]);
        }

        $result = $this->salaryCalculator->calculate($employeeId, $periodStart, $periodEnd);

        return $this->response->setJSON($result);
    }

    public function getPayslip(int $employeeId, string $periodStart, string $periodEnd): string
    {
        $data = $this->salaryCalculator->get_payslip($employeeId, $periodStart, $periodEnd);
        return view('hr/salary/payslip', $data);
    }

    // ============ Employee Shift Assignment ============

    public function postAssignShift(): ResponseInterface
    {
        $employeeId = $this->request->getPost('employee_id', FILTER_SANITIZE_NUMBER_INT);
        $shiftId = $this->request->getPost('shift_id', FILTER_SANITIZE_NUMBER_INT);
        $effectiveFrom = $this->request->getPost('effective_from');
        $effectiveTo = $this->request->getPost('effective_to');

        if (!$employeeId || !$shiftId || !$effectiveFrom) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Hr.fill_required_fields')
            ]);
        }

        $success = $this->employeeShift->assign_shift($employeeId, $shiftId, $effectiveFrom, $effectiveTo);

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? lang('Common.successful_update') : lang('Common.error')
        ]);
    }

    public function getEmployeeShifts(int $employeeId): string
    {
        $data['shifts'] = $this->employeeShift->get_employee_shifts($employeeId);
        $data['shift_options'] = $this->shift->get_simple_options();
        $data['employee_id'] = $employeeId;
        return view('hr/shifts/employee_shifts', $data);
    }

    // ============ Attendance ============

    public function getAttendance(): string
    {
        $date = $this->request->getGet('date') ?: date('Y-m-d');
        $data['attendance_records'] = $this->attendance->get_by_date($date);
        $data['current_date'] = $date;
        $data['employees'] = $this->employeeProfile->get_all_with_details();
        return view('hr/attendance/manage', $data);
    }

    public function postClockIn(): ResponseInterface
    {
        $employeeId = $this->request->getPost('employee_id', FILTER_SANITIZE_NUMBER_INT);
        $date = $this->request->getPost('date') ?: date('Y-m-d');
        $time = $this->request->getPost('time') ?: date('H:i:s');

        $shift = $this->employeeShift->get_shift_for_date($employeeId, $date);

        $attendanceData = [
            'clock_in' => $date . ' ' . $time,
            'scheduled_start' => $shift['start_time'] ?? null,
            'scheduled_end' => $shift['end_time'] ?? null,
            'status' => 'present'
        ];

        $success = $this->attendance->record_attendance($employeeId, $date, $attendanceData);

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? lang('Hr.clock_in_success') : lang('Common.error')
        ]);
    }

    public function postClockOut(): ResponseInterface
    {
        $employeeId = $this->request->getPost('employee_id', FILTER_SANITIZE_NUMBER_INT);
        $date = $this->request->getPost('date') ?: date('Y-m-d');
        $time = $this->request->getPost('time') ?: date('H:i:s');

        $success = $this->attendance->clock_out($employeeId, $date, $time);

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? lang('Hr.clock_out_success') : lang('Common.error')
        ]);
    }

    // ============ Leave Management ============

    public function getLeaveRequests(): string
    {
        $status = $this->request->getGet('status');
        if ($status === 'pending') {
            $data['requests'] = $this->leaveRequest->get_pending_requests();
        } elseif ($status) {
            $data['requests'] = $this->leaveRequest->select('ospos_leave_requests.*, ospos_leave_types.name as leave_type_name,
                    ospos_people.first_name, ospos_people.last_name')
                ->join('ospos_leave_types', 'ospos_leave_types.id = ospos_leave_requests.leave_type_id', 'left')
                ->join('ospos_people', 'ospos_people.person_id = ospos_leave_requests.employee_id', 'inner')
                ->where('ospos_leave_requests.status', $status)
                ->orderBy('ospos_leave_requests.created_at', 'DESC')
                ->findAll();
        } else {
            $data['requests'] = $this->leaveRequest->get_all_with_details();
        }
        $data['status_filter'] = $status;
        return view('hr/leave/manage', $data);
    }

    public function getLeaveRequest(int $id = 0): string
    {
        $request = $id ? $this->leaveRequest->find($id) : null;
        $data['request'] = $request;
        $data['leave_type_options'] = $this->leaveType->get_options();
        
        // Get leave balances for current employee
        $employeeId = $this->session->get('person_id');
        $data['leave_balances'] = $this->getEmployeeLeaveBalances($employeeId);
        
        return view('hr/leave/form', $data);
    }
    
    private function getEmployeeLeaveBalances(int $employeeId): array
    {
        $leaveTypes = $this->leaveType->get_all_active();
        $balances = [];
        $db = db_connect();
        
        foreach ($leaveTypes as $type) {
            // Get approved leave days for this type
            $approvedDays = $db->table('leave_requests')
                ->select('COALESCE(SUM(total_days), 0) as used_days')
                ->where('employee_id', $employeeId)
                ->where('leave_type_id', $type['id'])
                ->where('status', 'approved')
                ->get()
                ->getRowArray();
            
            $balances[] = [
                'type_id' => $type['id'],
                'type_name' => $type['name'],
                'total_days' => $type['default_days'],
                'used_days' => $approvedDays['used_days'] ?? 0,
                'remaining' => ($type['default_days'] ?? 0) - ($approvedDays['used_days'] ?? 0)
            ];
        }
        
        return $balances;
    }

    public function postSaveLeaveRequest(): ResponseInterface
    {
        $id = $this->request->getPost('id', FILTER_SANITIZE_NUMBER_INT) ?: null;
        $employeeId = $this->session->get('person_id');
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');

        $totalDays = $this->leaveRequest->calculate_days($startDate, $endDate);

        $data = [
            'employee_id' => $employeeId,
            'leave_type_id' => $this->request->getPost('leave_type_id', FILTER_SANITIZE_NUMBER_INT),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'reason' => $this->request->getPost('reason', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'status' => 'pending',
        ];

        if ($id) {
            $this->leaveRequest->update($id, $data);
        } else {
            $this->leaveRequest->insert($data);
            $id = $this->leaveRequest->getInsertID();
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => lang('Common.successful_update'),
            'id' => $id
        ]);
    }

    public function postApproveLeave(): ResponseInterface
    {
        $id = $this->request->getPost('id', FILTER_SANITIZE_NUMBER_INT);
        $approverId = $this->session->get('person_id');

        $leaveRequest = $this->leaveRequest->find($id);
        if (!$leaveRequest) {
            return $this->response->setJSON(['success' => false, 'message' => lang('Common.error')]);
        }

        $year = date('Y', strtotime($leaveRequest['start_date']));
        $this->leaveBalance->initialize_balance(
            $leaveRequest['employee_id'],
            $leaveRequest['leave_type_id'],
            $year,
            0
        );
        $this->leaveBalance->update_balance(
            $leaveRequest['employee_id'],
            $leaveRequest['leave_type_id'],
            $year,
            0,
            $leaveRequest['total_days']
        );

        $success = $this->leaveRequest->approve($id, $approverId);

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? lang('Hr.leave_approved') : lang('Common.error')
        ]);
    }

    public function postRejectLeave(): ResponseInterface
    {
        $id = $this->request->getPost('id', FILTER_SANITIZE_NUMBER_INT);
        $reason = $this->request->getPost('reason', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $success = $this->leaveRequest->reject($id, $reason);

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? lang('Hr.leave_rejected') : lang('Common.error')
        ]);
    }

    // ============ Leave Types ============

    public function getLeaveTypes(): string
    {
        $data['leave_types'] = $this->leaveType->findAll();
        return view('hr/leave/types_manage', $data);
    }

    public function getLeaveType(int $id = 0): string
    {
        $data['leave_type'] = $id ? $this->leaveType->find($id) : null;
        return view('hr/leave/types', $data);
    }

    public function postSaveLeaveType(): ResponseInterface
    {
        $id = $this->request->getPost('id', FILTER_SANITIZE_NUMBER_INT) ?: null;

        $data = [
            'name' => $this->request->getPost('name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'code' => $this->request->getPost('code', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'paid_unpaid' => $this->request->getPost('paid_unpaid'),
            'default_days' => $this->request->getPost('default_days') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($id) {
            $this->leaveType->update($id, $data);
        } else {
            $this->leaveType->insert($data);
            $id = $this->leaveType->getInsertID();
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => lang('Common.successful_update'),
            'id' => $id
        ]);
    }

    // ============ Employee Salary Rules ============

    public function getEmployeeSalaryRules(int $employeeId): string
    {
        $data['employee_rules'] = $this->employeeSalaryRule->get_employee_rules($employeeId);
        $data['all_rules'] = $this->salaryRule->get_all_active();
        $data['employee_id'] = $employeeId;
        return view('hr/salary_rules/employee_rules', $data);
    }

    public function postAssignSalaryRule(): ResponseInterface
    {
        $employeeId = $this->request->getPost('employee_id', FILTER_SANITIZE_NUMBER_INT);
        $ruleId = $this->request->getPost('rule_id', FILTER_SANITIZE_NUMBER_INT);
        $customValue = $this->request->getPost('custom_value');

        $success = $this->employeeSalaryRule->assign_rule(
            $employeeId,
            $ruleId,
            $customValue ? (float) $customValue : null
        );

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? lang('Common.successful_update') : lang('Common.error')
        ]);
    }

    public function postRemoveSalaryRule(): ResponseInterface
    {
        $employeeId = $this->request->getPost('employee_id', FILTER_SANITIZE_NUMBER_INT);
        $ruleId = $this->request->getPost('rule_id', FILTER_SANITIZE_NUMBER_INT);

        $success = $this->employeeSalaryRule->remove_rule($employeeId, $ruleId);

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? lang('Common.successful_update') : lang('Common.error')
        ]);
    }
}
