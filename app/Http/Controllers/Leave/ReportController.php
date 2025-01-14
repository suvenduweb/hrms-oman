<?php

namespace App\Http\Controllers\Leave;

use Carbon\Carbon;
use App\Model\OnDuty;
use App\Model\Employee;
use App\Model\LeaveType;
use App\Model\Department;
use Illuminate\Http\Request;
use App\Model\LeavePermission;
use App\Model\LeaveApplication;
use App\Model\PrintHeadSetting;
use Illuminate\Support\Facades\DB;
use App\Exports\SummaryLeaveReport;
use App\Exports\ReportFromBladeView;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Lib\Enumerations\LeaveStatus;
use App\Repositories\LeaveRepository;

class ReportController extends Controller
{

    protected $leaveRepository;

    public function __construct(LeaveRepository $leaveRepository)
    {
        $this->leaveRepository = $leaveRepository;
    }

    public function employeeLeaveReport(Request $request)
    {
        if ((decrypt(session('logged_session_data.role_id'))) != 1 && (decrypt(session('logged_session_data.role_id'))) != 2) {
            $departmentList = Department::where('department_id', decrypt(session('logged_session_data.department_id')))->get();
        } else {
            $departmentList = Department::get();
        }

        $results = LeaveApplication::with(['employee', 'leaveType', 'approveBy'])
            ->whereHas('employee.userName', function ($q) {
                $q->where('role_id', '>=', 3);
            })
            ->when($request->department_id != '', function ($q) use ($request) {
                $q->whereHas('employee', function ($eq) use ($request) {
                    $eq->where('department_id', $request->department_id);
                });
            })
            ->whereBetween('application_date', [dateConvertFormtoDB($request->from_date), dateConvertFormtoDB($request->to_date)])
            ->where('status', LeaveStatus::$APPROVE)
            ->orderBy('leave_application_id', 'DESC')
            ->get();

        return view('admin.leave.report.employeeLeaveReport', ['results' => $results, 'departmentList' => $departmentList, 'department_id' => $request->department_id, 'from_date' => $request->from_date, 'to_date' => $request->to_date]);
    }

    public function downloadLeaveReport(Request $request)
    {
        $printHead = PrintHeadSetting::first();
        $results = LeaveApplication::with(['employee', 'leaveType', 'approveBy'])
            ->whereHas('employee.userName', function ($q) {
                $q->where('role_id', '>=', 3);
            })
            ->when($request->department_id != '', function ($q) use ($request) {
                $q->whereHas('employee', function ($eq) use ($request) {
                    $eq->where('department_id', $request->department_id);
                });
            })
            ->whereBetween('application_date', [dateConvertFormtoDB($request->from_date), dateConvertFormtoDB($request->to_date)])
            ->where('status', LeaveStatus::$APPROVE)
            ->select('leave_application.*')->orderBy('leave_application_id', 'DESC')
            ->get();

        $data = [
            'results' => $results,
            'from_date' => dateConvertFormtoDB($request->from_date),
            'to_date' => dateConvertFormtoDB($request->to_date),
            'printHead' => $printHead,
            'department_id' => $request->department_id,
        ];
        $pdf = \PDF::loadView('admin.leave.report.pdf.employeeLeaveReportPdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pageName = "leave-report.pdf";
        return $pdf->download($pageName);
    }
    public function downloadLeaveReportExcel(Request $request)
    {
        $results = LeaveApplication::with(['employee', 'leaveType', 'approveBy'])
            ->whereHas('employee.userName', function ($q) {
                $q->where('role_id', '>=', 3);
            })
            ->when($request->department_id != '', function ($q) use ($request) {
                $q->whereHas('employee', function ($eq) use ($request) {
                    $eq->where('department_id', $request->department_id);
                });
            })
            ->whereBetween('application_date', [dateConvertFormtoDB($request->from_date), dateConvertFormtoDB($request->to_date)])
            ->where('status', LeaveStatus::$APPROVE)
            ->select('leave_application.*')->orderBy('leave_application_id', 'DESC')
            ->get();

        $data = [
            'results' => $results,
            'from_date' => dateConvertFormtoDB($request->from_date),
            'to_date' => dateConvertFormtoDB($request->to_date),
            'department_id' => $request->department_id,
        ];

        $excel = new ReportFromBladeView('admin.leave.report.pagination.employeeLeaveReportPagination', $data);

        $excelFile = Excel::download($excel, 'leaveReport-' . date('Y-m-d', strtotime($data['from_date'])) . ' to ' . date('Y-m-d', strtotime($data['to_date'])) . '.xlsx');

        return $excelFile;
    }

    public function employeePermissionReport(Request $request)
    {
        if ((decrypt(session('logged_session_data.role_id'))) != 1 && (decrypt(session('logged_session_data.role_id'))) != 2) {
            $departmentList = Department::where('department_id', decrypt(session('logged_session_data.department_id')))->get();
        } else {
            $departmentList = Department::get();
        }

        $results = LeavePermission::with(['employee', 'approveBy'])
            ->whereHas('employee.userName', function ($q) {
                $q->where('role_id', '>=', 3);
            })
            ->when($request->department_id != '', function ($q) use ($request) {
                $q->whereHas('employee', function ($eq) use ($request) {
                    $eq->where('department_id', $request->department_id);
                });
            })
            ->whereBetween('leave_permission_date', [dateConvertFormtoDB($request->from_date), dateConvertFormtoDB($request->to_date)])
            ->where('status', LeaveStatus::$APPROVE)
            ->select('leave_permission.*')->orderBy('leave_permission_id', 'DESC')
            ->get();


        return view('admin.leave.report.employeePermissionReport', ['results' => $results, 'departmentList' => $departmentList, 'department_id' => $request->department_id, 'from_date' => $request->from_date, 'to_date' => $request->to_date]);
    }
    public function downloadPermissionReport(Request $request)
    {

        $printHead = PrintHeadSetting::first();

        $results = LeavePermission::with(['employee', 'approveBy'])
            ->whereHas('employee.userName', function ($q) {
                $q->where('role_id', '>=', 3);
            })
            ->when($request->department_id != '', function ($q) use ($request) {
                $q->whereHas('employee', function ($eq) use ($request) {
                    $eq->where('department_id', $request->department_id);
                });
            })
            ->whereBetween('leave_permission_date', [dateConvertFormtoDB($request->from_date), dateConvertFormtoDB($request->to_date)])
            ->where('status', LeaveStatus::$APPROVE)
            ->orderBy('leave_permission_id', 'DESC')
            ->get();

        $data =    [
            'results' => $results,
            'department_id' => $request->department_id,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'printHead' => $printHead
        ];

        $pdf = \PDF::loadView('admin.leave.report.pdf.employeePermissionReportPdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pageName = "permission-report.pdf";
        return $pdf->download($pageName);
    }

    public function downloadPermissionReportExcel(Request $request)
    {
        $results = LeavePermission::with(['employee', 'approveBy'])
            ->whereHas('employee.userName', function ($q) {
                $q->where('role_id', '>=', 3);
            })
            ->when($request->department_id != '', function ($q) use ($request) {
                $q->whereHas('employee', function ($eq) use ($request) {
                    $eq->where('department_id', $request->department_id);
                });
            })
            ->whereBetween('leave_permission_date', [dateConvertFormtoDB($request->from_date), dateConvertFormtoDB($request->to_date)])
            ->where('status', LeaveStatus::$APPROVE)
            ->orderBy('leave_permission_id', 'DESC')
            ->get();

        $data =    [
            'results' => $results,
            'department_id' => $request->department_id,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
        ];

        $excel = new ReportFromBladeView('admin.leave.report.pagination.employeePermissionReportPagination', $data);
        $excelFile = Excel::download($excel, 'permissionReport-' . date('Y-m-d', strtotime($data['from_date'])) . ' to ' . date('Y-m-d', strtotime($data['to_date'])) . '.xlsx');
        return $excelFile;
    }

    public function employeeOndutyReport(Request $request)
    {
        if ((decrypt(session('logged_session_data.role_id'))) != 1 && (decrypt(session('logged_session_data.role_id'))) != 2) {
            $departmentList = Department::where('department_id', decrypt(session('logged_session_data.department_id')))->get();
        } else {
            $departmentList = Department::get();
        }

        $results = OnDuty::with(['employee', 'approveBy'])
            ->whereHas('employee.userName', function ($q) {
                $q->where('role_id', '>=', 3);
            })
            ->when($request->department_id != '', function ($q) use ($request) {
                $q->whereHas('employee', function ($eq) use ($request) {
                    $eq->where('department_id', $request->department_id);
                });
            })
            ->whereBetween('application_date', [dateConvertFormtoDB($request->from_date), dateConvertFormtoDB($request->to_date)])
            ->where('status', LeaveStatus::$APPROVE)
            ->orderBy('on_duty_id', 'DESC')
            ->get();

        return view('admin.leave.report.employeeOndutyReport', ['results' => $results, 'departmentList' => $departmentList, 'department_id' => $request->department_id, 'from_date' => $request->from_date, 'to_date' => $request->to_date]);
    }

    public function myLeaveReport(Request $request)
    {
        $employeeList = Employee::where('status', 1)->where('employee_id', decrypt(session('logged_session_data.employee_id')))->get();
        $results = LeaveApplication::with(['employee', 'leaveType', 'approveBy'])
            ->where('status', LeaveStatus::$APPROVE)
            ->where('employee_id', decrypt(session('logged_session_data.employee_id')))
            ->whereBetween('application_date', [dateConvertFormtoDB($request->from_date), dateConvertFormtoDB($request->to_date)])
            ->orderBy('leave_application_id', 'DESC')
            ->get();

        return view('admin.leave.report.myLeaveReport', ['results' => $results, 'employeeList' => $employeeList, 'from_date' => $request->from_date, 'to_date' => $request->to_date]);
    }

    public function downloadMyLeaveReport(Request $request)
    {

        $employeeInfo = Employee::with('department')->where('employee_id', decrypt(session('logged_session_data.employee_id')))->first();
        $printHead = PrintHeadSetting::first();
        $results = LeaveApplication::with(['employee', 'leaveType', 'approveBy'])
            ->where('status', LeaveStatus::$APPROVE)
            ->where('employee_id', decrypt(session('logged_session_data.employee_id')))
            ->whereBetween('application_date', [dateConvertFormtoDB($request->from_date), dateConvertFormtoDB($request->to_date)])
            ->orderBy('leave_application_id', 'DESC')
            ->get();
        $data = [
            'results' => $results,
            'from_date' => dateConvertFormtoDB($request->from_date),
            'to_date' => dateConvertFormtoDB($request->to_date),
            'printHead' => $printHead,
            'employee_name' => $employeeInfo->first_name . ' ' . $employeeInfo->last_name,
            'department_name' => $employeeInfo->department->department_name,
        ];

        $pdf = \PDF::loadView('admin.leave.report.pdf.myLeaveReportPdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pageName = "my-leave-report.pdf";
        return $pdf->download($pageName);
    }

    public function downloadMyLeaveReportExcel(Request $request)
    {

        $employeeInfo = Employee::with('department')->where('employee_id', decrypt(session('logged_session_data.employee_id')))->first();
        $results = LeaveApplication::with(['employee', 'leaveType', 'approveBy'])
            ->where('status', LeaveStatus::$APPROVE)
            ->where('employee_id', decrypt(session('logged_session_data.employee_id')))
            ->whereBetween('application_date', [dateConvertFormtoDB($request->from_date), dateConvertFormtoDB($request->to_date)])
            ->orderBy('leave_application_id', 'DESC')
            ->get();
        $data = [
            'results' => $results,
            'from_date' => dateConvertFormtoDB($request->from_date),
            'to_date' => dateConvertFormtoDB($request->to_date),
            'employee_name' => $employeeInfo->first_name . ' ' . $employeeInfo->last_name,
            'department_name' => $employeeInfo->department->department_name,
        ];

        $excel = new ReportFromBladeView('admin.leave.report.pagination.myLeaveReportPagination', $data);
        $excelFile = Excel::download($excel, 'myLeaveReport-' . date('Y-m-d', strtotime($data['from_date'])) . ' to ' . date('Y-m-d', strtotime($data['to_date'])) . '.xlsx');
        return $excelFile;
    }

    public function summaryReport(Request $request)
    {
        set_time_limit(0);
        if (decrypt(session('logged_session_data.role_id')) != 1 && decrypt(session('logged_session_data.role_id')) != 2) {
            $employeeList = Employee::where('status', 1)->where('employee.supervisor_id', decrypt(session('logged_session_data.employee_id')))
                ->orwhere('employee.employee_id', decrypt(session('logged_session_data.employee_id')))->get();
        }
        if ((decrypt(session('logged_session_data.role_id')) == 3)) {
            $employeeList = Employee::where('status', 1)->where('employee.operation_manager_id', decrypt(session('logged_session_data.employee_id')))
                ->orwhere('employee.employee_id', decrypt(session('logged_session_data.employee_id')))->get();
        } else {
            $employeeList = Employee::where('status', 1)->get();
        }
        $leaveTypes = LeaveType::where('status', 1)->get();

        $result = [];

        if ($_POST) {
            $result = $this->summaryReportDataFormat($request->employee_id, $request->from_date, $request->to_date);
        }

        $data = [
            'results' => $result,
            'employeeList' => $employeeList,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'employee_id' => $request->employee_id,
            'leaveTypes' => $leaveTypes,
        ];

        return view('admin.leave.report.summaryReport', $data);
    }

    public function downloadSummaryReport(Request $request)
    {
        \set_time_limit(0);

        $printHead = PrintHeadSetting::first();
        $leaveTypes = LeaveType::orderBy('leave_type_id')->get();
        $results = $this->summaryReportDataFormat($request->employee_id, $request->from_date, $request->to_date);
        $employee = Employee::where('employee_id', $request->employee_id)->with('department')->firstOrFail();

        $data = [
            'results' => $results,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'employee_id' => $request->employee_id,
            'leaveTypes' => $leaveTypes,
            'printHead' => $printHead,
            'employee' => $employee,

        ];

        $pdf = \PDF::loadView('admin.leave.report.pdf.summaryReportPdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pageName = "summary-report" . date('YmdHis') . ".pdf";
        return $pdf->download($pageName);
    }
    public function downloadSummaryReportExcel(Request $request)
    {
        \set_time_limit(0);

        $leaveTypes = LeaveType::orderBy('leave_type_id')->get();

        $results = [];

        $results = $this->summaryReportDataFormat($request->employee_id, $request->from_date, $request->to_date);

        $data = [
            'results' => $results,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'employee_id' => $request->employee_id,
            'leaveTypes' => $leaveTypes,
        ];

        $excel = new SummaryLeaveReport('admin.leave.report.pagination.summaryReportPagination', $data, -3);

        $excelFile = Excel::download($excel, 'summaryLeaveReport-' . date('Y-m-d', strtotime($request->from_date)) . ' to ' . date('Y-m-d', strtotime($request->to_date)) . '.xlsx');

        return $excelFile;
    }

    public function summaryReportDataFormat($employee_id, $from_date, $to_date)
    {
        $leaveType = LeaveType::where('status', 1)->get();

        $output = [];

        $time = strtotime($from_date);
        // $last = Carbon::createFromFormat('d/m/Y', $to_date)->format('Y-m');
        $last   = date('Y-m', strtotime($to_date));

        do {
            $month = date('Y-m', $time);
            $name = date('F', mktime(0, 0, 0, date('m', strtotime($month)), 1, date('Y', strtotime($month))));
            $total = date('t', $time);

            $output[] = [
                'month' => $month,
                'name' => $name,
                'total' => $total,
            ];

            $time = strtotime('+1 month', $time);
            // dump($last, $month);
        } while ($month != $last);

        $leaveDataFormat = [];
        $tempArray = [];

        foreach ($output as $key => $value) {

            $start_date = $value['month'] . '-01';
            $end_date = date("Y-m-t", strtotime($start_date));

            $employee = Employee::where('employee_id', $employee_id)->with('department', 'designation')->firstOrFail();
            $employee = $employee->toArray();

            $leaveType = LeaveType::where('status', 1)->get();
            $leaveTypeArray = [];

            foreach ($leaveType as $key => $type) {
                $leaveTypeArray[$type->leave_type_name] = 0;
            }

            $leaveApplications = LeaveApplication::join('employee', 'employee.employee_id', 'leave_application.employee_id')
                ->join('leave_type', 'leave_type.leave_type_id', 'leave_application.leave_type_id')
                ->where('leave_application.status', LeaveStatus::$APPROVE)
                ->where('employee.employee_id', $employee_id)
                ->whereRaw("application_from_date >= '" . $start_date . "' and application_to_date <= '" . $end_date . "'")
                ->select('leave_application.*', 'leave_type.*')
                ->get()->toArray();

            $tempArray['month'] = $value['month'];
            $tempArray['month_name'] = $value['name'];
            $tempArray['employee_id'] = $employee_id;
            $tempArray['finger_id'] = $employee['finger_id'];
            $tempArray['full_name'] = $employee['last_name'] != null ? $employee['first_name'] . ' ' . $employee['last_name'] : $employee['first_name'];
            $tempArray['department_name'] = $employee['department']['department_name'];
            $tempArray['designation_name'] = $employee['designation']['designation_name'];

            if (count($leaveApplications) > 0) {
                foreach ($leaveApplications as $key => $leaveApplication) {
                    foreach ($leaveType as $key => $type) {
                        if ($type->leave_type_name == $leaveApplication['leave_type_name']) {
                            $leaveTypeArray[$type->leave_type_name] += $leaveApplication['number_of_day'];
                            $tempArray['leaveType'][$type->leave_type_name] = $leaveTypeArray[$type->leave_type_name];
                            // $tempArray['leaveType'][$type->leave_type_name]['leave_type_name'] = $leaveApplication['leave_type_name'];
                        }
                    }
                }
            } else {
                foreach ($leaveType as $key => $type) {
                    $tempArray['leaveType'][$type->leave_type_name] = '';
                    // $tempArray['leaveType'][$type->leave_type_name]['leave_type_name'] = $type->leave_type_name;
                }
            }

            $leaveDataFormat[] = $tempArray;
        }

        // dd($leaveDataFormat);
        return $leaveDataFormat;
    }

    public function summaryReportDataFormat1($employee_id, $month)
    {
        $leaveType = LeaveType::where('status', 1)->get();
        $data = findMonthToAllDate($month);
        $start_date = $month . '-01';
        $end_date = date("Y-m-t", strtotime($start_date));

        $employeeTotalLeaveDetails = LeaveApplication::select('leave_application.*', DB::raw('SUM(leave_application.number_of_day) as leaveConsume'))
            ->where('employee_id', $employee_id)
            ->groupBy('leave_application.leave_type_id')
            ->get()->toArray();
        $arrayFormat = [];
        foreach ($leaveType as $value) {
            if ($value->leave_type_id == 1) {
                $action = "getEarnLeaveBalanceAndExpenseBalance";
                $getNumberOfEarnLeave = $this->leaveRepository->calculateEmployeeEarnLeave($value->leave_type_id, $employee_id, $action);
                $temp['num_of_day'] = $getNumberOfEarnLeave['totalEarnLeave'];
                $temp['leave_consume'] = $getNumberOfEarnLeave['leaveConsume'];
                $temp['current_balance'] = $getNumberOfEarnLeave['currentBalance'];
            } elseif ($value->leave_type_id == 2) {
                $action = "getEarnLeaveBalanceAndExpenseBalance";
                $getNumberOfEarnLeave = $this->leaveRepository->calculateEmployeePaidLeave($value->leave_type_id, $employee_id, $action);
                $temp['num_of_day'] = $getNumberOfEarnLeave['totalPaidLeave'];
                $temp['leave_consume'] = $getNumberOfEarnLeave['leaveConsume'];
                $temp['current_balance'] = $getNumberOfEarnLeave['currentBalance'];
            } else {
                $temp['num_of_day'] = $value->num_of_day;
                $a = array_search($value->leave_type_id, array_column($employeeTotalLeaveDetails, 'leave_type_id'));
                if (gettype($a) == 'integer') {
                    $temp['leave_consume'] = $employeeTotalLeaveDetails[$a]['leaveConsume'];
                    $temp['current_balance'] = $value->num_of_day - $employeeTotalLeaveDetails[$a]['leaveConsume'];
                } else {
                    $temp['leave_consume'] = 0;
                    $temp['current_balance'] = $value->num_of_day;
                }
            }
            $temp['leave_type_id'] = $value->leave_type_id;
            $temp['leave_type_name'] = $value->leave_type_name;
            $arrayFormat[] = $temp;
        }

        return $arrayFormat;
    }
}
