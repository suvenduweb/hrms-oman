<?php

namespace App\Http\Controllers\Api;

use App\Model\Employee;
use App\Components\Common;
use Illuminate\Http\Request;
use App\Model\LeavePermission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\LeaveRepository;
use App\Repositories\CommonRepository;

class ApplyForPermissionController extends Controller
{
    protected $commonRepository;
    protected $leaveRepository;

    public function __construct(CommonRepository $commonRepository, LeaveRepository $leaveRepository)
    {
        $this->commonRepository = $commonRepository;
        $this->leaveRepository  = $leaveRepository;
    }

    public function index(Request $request)
    {
        $data = [];
        $employee = Employee::where('employee_id', $request->employee_id)->first();

        $permission_data = LeavePermission::with(['employee'])
            ->where('employee_id', $employee->employee_id)
            ->orderBy('leave_permission_date', 'desc')
            ->get();
        $permission_status = "";

        foreach ($permission_data as $permission_row) {

            if ($permission_row->status == 3) {
                $permission_status = "rejected";
            } elseif (($permission_row->status == 2)) {
                $permission_status = "Approved";
            } elseif (($permission_row->status == 1)) {
                $permission_status = "Pending";
            } else {
                $permission_status = "Pending";
            }
            $data[] = array(
                'leave_permission_date' => date("d-m-Y", strtotime($permission_row->leave_permission_date)),
                'permission_duration' => $permission_row->permission_duration,
                'from_time' => $permission_row->from_time,
                'to_time' => $permission_row->to_time,
                'leave_permission_purpose' => $permission_row->leave_permission_purpose,
                'permission_status' => $permission_status,
                'p_status' => $permission_row->status,
                'first_name' => $employee->first_name,
                'finger_id' => $employee->finger_id,
                'remark' => $permission_row->remarks,
            );
        }

        if ($data) {
            return response()->json([
                'status' => true,
                'data'         => $data,
                'message'      => 'Permission Request Details Successfully Received',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No Data Found',
            ], 200);
        }
    }

    public function create()
    {
        return response()->json([
            'message' => 'No Data Found',
            'status' => false,
        ], 200);
    }


    public function store(Request $request)

    {
        // info($request->all());
        $employee_data = Employee::where('employee_id', $request->employee_id)->first();

        $input                            = $request->all();
        $input['leave_permission_date']   = dateConvertFormtoDB($request->permission_date);
        $input['permission_duration']     = $request->permission_duration;
        $input['leave_permission_purpose'] = $request->purpose;
        $input['department_head']         = $employee_data->supervisor_id;
        $input['from_time']               = $request->from_time;
        $input['to_time']                 = $request->to_time;

        if (empty($employee_data->supervisor_id)) {
            return $this->responseWithError('Department Head Data Not Given');
        }

        if (empty($request->permission_date) || $request->permission_date === '0000-00-00') {
            return $this->responseWithError('Permission Date Not Given');
        }

        if (empty($request->permission_duration)) {
            return $this->responseWithError('Permission Duration Not Given');
        }

        if (empty($request->purpose)) {
            return $this->responseWithError('Permission Purpose Not Given');
        }

        if (empty($request->from_time)) {
            return $this->responseWithError('Permission From Time Not Given');
        }

        if (empty($request->to_time)) {
            return $this->responseWithError('Permission To Time Not Given');
        }
        try {
            $employeeId = $request->employee_id;
            $requestedMonth = date('m', strtotime(dateConvertFormtoDB($request->permission_date)));

            $countPermission = LeavePermission::where('employee_id', $employeeId)
                ->whereMonth('leave_permission_date', $requestedMonth)
                ->count();

            if ($countPermission >= 3) {
                return $this->responseWithError('You have already taken Three permissions this month. Please try again next month');
            }

            $if_exists = LeavePermission::where('employee_id', $employeeId)
                ->where('leave_permission_date', dateConvertFormtoDB($request->permission_date))
                ->first();

            if (!$if_exists) {
                $employee_data = Employee::where('employee_id', $request->employee_id)->first();
                $first_level = Employee::where('employee_id', $employee_data->operation_manager_id)->first();
                LeavePermission::create($input);
                if (isset($first_level) && $first_level->email) {
                    $maildata = Common::mail('emails/mail', $first_level->email, 'Permission Request Notification', [
                        'head_name' => $first_level->first_name . ' ' . $first_level->last_name,
                        'request_info' => $employee_data->first_name . ' ' . $employee_data->last_name . ', have requested for permission (Purpose: ' . $request->purpose . ') On ' . ' ' . dateConvertFormtoDB($request->permission_date) . ' fromTime ' . ' ' . $request->from_time . ' toTime ' . $request->to_time,
                        'status_info' => '',
                    ]);
                }
                return $this->responseWithSuccess('Permission Request Sent Successfully.');
            } else {
                return $this->responseWithError('Permission Request Already Exist.');
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage());
        }
    }

    private function responseWithError($message)
    {
        return response()->json([
            'message' => $message,
            'status' => false,
        ], 200);
    }

    private function responseWithSuccess($message)
    {
        return response()->json([
            'message' => $message,
            'status' => true,
        ], 200);
    }
}
