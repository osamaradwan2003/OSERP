<?php

namespace App\Controllers;

use App\Models\Employee;
use App\Models\Hr\Attendance;
use App\Models\Hr\Department;
use App\Models\Hr\EmployeeLeaveBalance;
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
        $this->employee = new Employee();
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
        $data['departments'] = $this->department->get_with_children();
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
        $data['requests'] = $status === 'pending'
            ? $this->leaveRequest->get_pending_requests()
            : $this->leaveRequest->findAll();
        $data['status_filter'] = $status;
        return view('hr/leave/manage', $data);
    }

    public function getLeaveRequest(int $id = 0): string
    {
        $request = $id ? $this->leaveRequest->find($id) : null;
        $data['request'] = $request;
        $data['leave_type_options'] = $this->leaveType->get_options();
        return view('hr/leave/form', $data);
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
