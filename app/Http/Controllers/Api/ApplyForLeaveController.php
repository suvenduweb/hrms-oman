<?php

namespace App\Http\Controllers\Api;

use DateTime;
use Carbon\Carbon;
use App\Model\Employee;
use App\Model\LeaveType;
use App\Components\Common;
use Illuminate\Support\Str;
use App\Model\EarnLeaveRule;
use App\Model\PaidLeaveRule;
use Illuminate\Http\Request;
use App\Model\LeaveApplication;
use App\Mail\LeaveApplicationMail;
use Illuminate\Support\Facades\DB;
use App\Model\PaidLeaveApplication;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Repositories\LeaveRepository;
use Illuminate\Support\Facades\Route;
use App\Repositories\CommonRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ApplyForLeaveRequest;

class ApplyForLeaveController extends Controller
{
    protected $commonRepository;
    protected $leaveRepository;
    protected $authController;
    protected $controller;
    public $api = false;

    public function __construct(Controller $controller, CommonRepository $commonRepository, LeaveRepository $leaveRepository, AuthController $authController)
    {
        $this->commonRepository = $commonRepository;
        $this->leaveRepository = $leaveRepository;
        $this->authController = $authController;
        $this->controller = $controller;
    }

    public function index(Request $request)
    {
        // $request->validate(['employee_id' => 'required']);

        $employee_id = $request->employee_id;

        try {
            $results = LeaveApplication::with(['employee', 'leaveType', 'approveBy', 'rejectBy'])
                ->where('employee_id', $employee_id)
                ->orderBy('leave_application_id', 'desc')
                ->get();

            $leaveType = $this->commonRepository->leaveTypeListapi($employee_id);
            $leaveTypeIds = array_values(array_flip($leaveType));
            $leaveType = DB::table('leave_type')->whereIn('leave_type_id', $leaveTypeIds)->get();

            return $this->controller->successdualdata("Datas Successfully Received !!!", $results, $leaveType);
        } catch (\Throwable $th) {
            //throw $th;
            info($th);

            return $this->controller->custom_error("Something went wrong! please try again.");
            $bug = 1;
        }


        // if (count($results) > 0) {
        //     return $this->controller->successdualdata("Datas Successfully Received !!!", $results, $leaveType);
        // } elseif (count($results) == 0) {
        //     return $this->controller->custom_error("Leave Application Not Found.");
        // } else {
        //     return $this->controller->custom_error("Something went wrong! please try again.");
        // }
    }

    public function create(Request $request)
    {
        // $request->validate(['employee_id' => 'required']);
        // $auth_user_id = auth()->user()->user_id;
        $auth_user_id = $request->employee_id;
        $leaveTypeList = $this->commonRepository->leaveTypeList();
        $getEmployeeInfo = $this->commonRepository->getLimitedEmployeeInfo($auth_user_id);

        // $leaveType             = LeaveType::pluck('leave_type_name');
        $leaveType = LeaveType::all();
        $totalPaidLeaveTaken = PaidLeaveApplication::where('employee_id', $auth_user_id)->where('status', 2)->where('created_at', Carbon::now()->year)->pluck('number_of_day');
        $totalLeaveTaken = LeaveApplication::where('employee_id', $auth_user_id)->where('status', 2)->whereYear('created_at', Carbon::now()->year)->pluck('number_of_day');
        $sumOfLeaveTaken = (int) $totalLeaveTaken->sum();
        $sumOfPaidLeaveTaken = (int) $totalPaidLeaveTaken->sum();
        $permissableLeave = LeaveType::sum('num_of_day');
        $checkPaidLeaveEligibility = $sumOfLeaveTaken <= $permissableLeave;
        $leaveBalance = $permissableLeave - $sumOfLeaveTaken;

        $data = [
            'checkPaidLeaveEligibility' => $checkPaidLeaveEligibility == true ? 'Eligibile' : 'Not Eligibile',
            'leaveType' => $leaveType,
            'permissableLeave' => $permissableLeave,
            'sumOfLeaveTaken' => $sumOfLeaveTaken,
            'leaveBalance' => $leaveBalance,
            'leaveTypeList' => $leaveTypeList,
            'sumOfPaidLeaveTaken' => $sumOfPaidLeaveTaken,
            'getEmployeeInfo' => $getEmployeeInfo,
        ];

        return $data;

        return $this->controller->success("Leave Details Successfully Received !!!", $data);
    }

    public function store(Request $request)
    {
        $applicationFromDate = date('Y-m-d', strtotime($request->application_from_date));
        $applicationToDate = date('Y-m-d', strtotime($request->application_to_date));

        if ($applicationFromDate > $applicationToDate) {
            return $this->controller->custom_error("The application from date must be earlier than the application to date.");
        }

        $request_data = [
            'application_date' => date('Y-m-d'),
            'application_from_date' => $request->application_from_date,
            'application_to_date' => $request->application_to_date,
            'leave_type_id' => $request->leave_type_id,
            'employee_id' => $request->employee_id,
            'purpose' => $request->purpose,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $employee = Employee::where('employee_id', $request->employee_id)->first();
        $leaveType = LeaveType::where('leave_type_id', $request->leave_type_id)->first();

        if (
            isset($employee) &&
            isset($leaveType)
        ) {
            $religionStatus = 0;
            $status = 0;
            $genderStatus = 0;
            $nationalityStatus = 0;
            //Both...
            if ($leaveType->nationality == 2 && $leaveType->religion == 2 && $leaveType->gender == 2) {
                $status = true;
                $nationalityStatus =  $religionStatus = $genderStatus = 1;
            } elseif ($leaveType->nationality == 2  && $leaveType->religion != 2 && $leaveType->gender != 2) {
                $nationalityStatus = 1;
                if ($leaveType->religion != 2) {
                    if ($leaveType->religion == $employee->religion) {
                        $religionStatus = 1;
                        $status = 1;
                    }
                }

                if ($leaveType->gender != 2) {
                    if ($leaveType->gender == $employee->gender) {
                        $status = 1;
                        $genderStatus = 1;
                    }
                }
                if ($religionStatus == 1 && $genderStatus == 1 && $nationalityStatus == 1) {
                    $status = 1;
                } else {
                    $status = 0;
                }
            } elseif ($leaveType->religion == 2 && $leaveType->nationality != 2 &&  $leaveType->gender != 2) {
                $religionStatus = 1;

                if ($leaveType->nationality != 2) {
                    if ($leaveType->nationality == $employee->nationality) {
                        $nationalityStatus = 1;
                        $status = 1;
                    }
                } else {
                    $nationalityStatus = 0;
                }
                if ($leaveType->gender != 2) {
                    if ($leaveType->gender == $employee->gender) {
                        $status = 1;
                        $genderStatus = 1;
                    }
                } else {
                    $nationalityStatus = 0;
                }
                if ($religionStatus == 1 && $genderStatus == 1 && $nationalityStatus == 1) {
                    $status = 1;
                } else {
                    $status = 0;
                }
            } elseif ($leaveType->gender == 2 && $leaveType->religion != 2 && $leaveType->nationality != 2) {

                $genderStatus = 1;
                if ($leaveType->religion != 2) {
                    if ($leaveType->religion == $employee->religion) {
                        $religionStatus = 1;
                        $status = 1;
                    }
                }
                if ($leaveType->nationality != 2) {
                    if ($leaveType->nationality == $employee->nationality) {
                        $nationalityStatus = 1;
                        $status = 1;
                    }
                }
                if ($religionStatus == 1 && $genderStatus == 1 && $nationalityStatus == 1) {
                    $status = 1;
                } else {
                    $status = 0;
                }
            } elseif ($leaveType->nationality == 2 && $leaveType->religion == 2 && $leaveType->gender != 2) {

                $nationalityStatus = $religionStatus = 1;
                if ($leaveType->gender == $employee->gender) {
                    $genderStatus = 1;
                } else {
                    $genderStatus = 0;
                }
            } elseif ($leaveType->nationality == 2 && $leaveType->gender == 2 && $leaveType->religion != 2) {
                $nationalityStatus = $genderStatus = 1;
                if ($leaveType->religion == $employee->religion) {
                    $religionStatus = 1;
                } else {
                    $religionStatus = 0;
                }
            } elseif ($leaveType->religion == 2 && $leaveType->gender == 2 && $leaveType->nationality != 2) {
                $religionStatus = $genderStatus = 1;
                if ($leaveType->nationality == $employee->nationality) {
                    $nationalityStatus = 1;
                } else {
                    $nationalityStatus = 0;
                }
            } elseif ($leaveType->nationality != 2 && $leaveType->religion != 2 && $leaveType->gender != 2) {
                if ($employee->nationality == $leaveType->nationality) {
                    $nationalityStatus = 1;
                    $status = 1;
                } else {
                    $nationalityStatus = 0;
                }
                if ($employee->religion == $leaveType->religion) {
                    $religionStatus = 1;
                    $status = 1;
                } else {
                    $religionStatus = 0;
                }
                if ($employee->gender == $leaveType->gender) {
                    $genderStatus = 1;
                    $status = 1;
                } else {
                    $genderStatus = 0;
                }
                if ($religionStatus == 1 && $genderStatus == 1 && $nationalityStatus == 1) {
                    $status = 1;
                } else {
                    $status = 0;
                }
            }

            if ($religionStatus == 1 && $nationalityStatus == 1 && $genderStatus == 1) {

                $status = 1;
            } else {
                $status = 0;
            }


            if ($status == 1) {
                $leave_type_id = $request->leave_type_id;
                $employee_id = $request->employee_id;
                if ($leave_type_id != '' && $employee_id != '') {
                    $leave_balance = $this->leaveRepository->calculateEmployeeLeaveBalance1($leave_type_id, $employee_id);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not eligible for selected leave type!',
                    'leave_balance' => 0,
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Update the Fields in Employee(gender,religion,nationality)',
                'leave_balance' => 0,
            ]);
        }

        $ApplyForLeaveController = new \App\Http\Controllers\Leave\ApplyForLeaveController(new CommonRepository, new LeaveRepository);
        $ApplyForLeaveController->api = true;
        $number_of_day =  $ApplyForLeaveController->applyForTotalNumberOfDays($request);

        $number_of_day = $number_of_day['number_of_day'];
        $leave_application = array('number_of_day' => $number_of_day, 'leave_balance' => $leave_balance);

        $leave_application = \array_merge(array('number_of_day' => $number_of_day), $request_data);
        $mfile = $request->file('mfile');

        if ($request->mfile) {
            @list($type, $file_data) = explode(';', $request->mfile);
            @list(, $file_data) = explode(',', $file_data);
            $imageName = \md5(Str::random(30) . time() . '_' . uniqid()) . '.' .  $request->mfile_ext;
            $employeePhoto['face_id'] = $imageName;
            file_put_contents("uploads/leave_document/" . $imageName, base64_decode($request->mfile));
            $leave_application = \array_merge(array('document' => $imageName), $request_data);
        }

        $leave_application['number_of_day'] = $number_of_day;

        $if_exists = LeaveApplication::where('application_from_date', '>=', $request_data['application_from_date'])->where('application_to_date', '<=', $request_data['application_to_date'])
            ->where('employee_id', $request_data['employee_id'])->whereIn('status', [1, 2])->first();

        if (!$if_exists) {
            try {
                if ($number_of_day <= $leave_balance) {
                    // Insert leave application
                    $leaveId = DB::table('leave_application')->insertGetID($leave_application);

                    // Get employee and supervisor information
                    $employee_data = Employee::where('employee_id', $request->employee_id)->first();
                    $first_level = Employee::where('employee_id', $employee_data->operation_manager_id)->first();

                    // Notify supervisor via email
                    if ($first_level && $first_level->email) {
                        $maildata = Common::mail('emails/mail', $first_level->email, 'Leave Request Notification', [
                            'head_name' => $first_level->first_name . ' ' . $first_level->last_name,
                            'request_info' => $employee_data->first_name . ' ' . $employee_data->last_name . ', have requested for leave (Purpose: ' . $request->purpose . ') from ' . ' ' . dateConvertFormtoDB($request->application_from_date) . ' to ' . dateConvertFormtoDB($request->application_to_date),
                            'status_info' => '',
                        ]);
                    }

                    $responce = $this->controller->success("Leave Application Sent Successfully!", $leaveId);
                } else {
                    $responce = $this->controller->custom_error("Leave balance does not  exists for selected leave type.");
                }
            } catch (\Throwable $e) {
                $message = $e->getMessage();
                $responce = $this->controller->custom_error($message);
            } finally {
                return $responce;
            }
        } else {
            return $this->controller->custom_error("Leave application already exists, try different dates ranges.");
        }
    }

    public function dateChanged(Request $request)
    {
        $input = Validator::make($request->all(), [
            'employee_id' => 'required',
            'leave_type_id' => 'required|exists:leave_type,leave_type_id',
            'application_from_date' => 'required',
        ]);

        if ($input->fails()) {
            return Controller::custom_error($input->errors()->first());
        }

        $ApplyForLeaveController = new \App\Http\Controllers\Leave\ApplyForLeaveController(new CommonRepository, new LeaveRepository);
        $ApplyForLeaveController->api = true;

        // 5 = Maternity Leave, 6 = Paternity Leave addtional information added $result variable to handle UI, please refer in app\Http\Controllers\Leave\ApplyForLeaveController.php method applyForTotalNumberOfDays

        $result = $ApplyForLeaveController->applyForTotalNumberOfDays($request);
        info($result);
        return $this->controller->success("Leave Date Changes Info Successfully Received!", $result);
    }


    public function update(Request $request)
    {
        try {

            $update_data = [
                'approve_by' => $request->approve_by,
                'reject_by' => $request->reject_by,
                'approve_date' => $request->approve_date,
                'reject_date' => $request->reject_date,
                'remarks' => $request->remarks,
                'status' => $request->status,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $raw_data = LeaveApplication::where('leave_application_id', $request->leave_application_id)->first()->toArray();
            $data = \array_merge($raw_data, $update_data);

            // dd($data);

            DB::beginTransaction();
            $leave_application = LeaveApplication::where('leave_application_id', $request->leave_application_id)->update($data);
            DB::commit();

            $raw_data = LeaveApplication::where('leave_application_id', $request->leave_application_id)->first();

            $responce = $this->controller->success("Leave Details Saved Successfully !", $raw_data);
        } catch (\Throwable $e) {

            DB::rollback();
            $message = $e->getMessage();
            $responce = $this->controller->custom_error($message);
        } finally {

            return $responce;
        }
    }

    public function getEmployeeLeaveBalance($leave_type_id, $employee_id)
    {
        // dd(23);
        $employee = Employee::where('employee_id', $employee_id)->first();
        $leaveType = LeaveType::where('status', 1)->where('leave_type_id', $leave_type_id)->first();
        // dd([$employee, $leaveType]);
        if (
            isset($employee) &&
            isset($leaveType) &&
            $employee->nationality != null &&
            $employee->religion != null &&
            $employee->gender != null

        ) {
            $religionStatus = true;
            $status = true;
            $genderStatus = true;
            $nationalityStatus = true;

            // Both...
            if ($leaveType->nationality == 2 && $leaveType->religion == 2 && $leaveType->gender == 2) {
                $status = true;
            } elseif ($leaveType->nationality == 2) {

                $nationalityStatus = true;

                if ($leaveType->religion != 2) {
                    if ($leaveType->religion == $employee->religion) {
                        $religionStatus = true;
                        $status = true;
                    }
                }

                if ($leaveType->gender != 2) {
                    if ($leaveType->gender == $employee->gender) {
                        $status = true;
                        $genderStatus = true;
                    }
                }

                if ($religionStatus == true && $genderStatus == true && $nationalityStatus == true) {
                    $status = true;
                } else {
                    $status = false;
                }
            } elseif ($leaveType->religion == 2) {
                $religionStatus == true;
                if ($leaveType->nationality != 2) {
                    if ($leaveType->nationality == $employee->nationality) {
                        $nationalityStatus = true;
                        $status = true;
                    }
                }

                if ($leaveType->gender != 2) {
                    if ($leaveType->gender == $employee->gender) {
                        $status = true;
                        $genderStatus = true;
                    }
                } else {
                    $status = false;
                }
                if ($religionStatus == true && $genderStatus == true && $nationalityStatus == true) {
                    $status = true;
                } else {
                    $status = false;
                }
            } elseif ($leaveType->gender == 2) {
                $genderStatus = true;
                if ($leaveType->religion != 2) {
                    if ($leaveType->religion == $employee->religion) {
                        $religionStatus = true;
                        $status = true;
                    }
                }
                if ($leaveType->nationality != 2) {
                    if ($leaveType->nationality == $employee->nationality) {
                        $nationalityStatus = true;
                        $status = true;
                    }
                }
                if ($religionStatus == true && $genderStatus == true && $nationalityStatus == true) {
                    $status = true;
                } else {
                    $status = false;
                }
            } elseif ($leaveType->nationality != 2 && $leaveType->religion != 2 && $leaveType->gender != 2) {
                // dd(123);
                if ($employee->nationality == $leaveType->nationality) {
                    $nationalityStatus = true;
                    $status = true;
                } else {
                    $nationalityStatus = false;
                }
                if ($employee->religion == $leaveType->religion) {
                    $religionStatus = true;
                    $status = true;
                } else {
                    $religionStatus = false;
                }
                if ($employee->gender == $leaveType->gender) {
                    $genderStatus = true;
                    $status = true;
                } else {
                    $genderStatus = false;
                }
                if ($religionStatus == true && $genderStatus == true && $nationalityStatus == true) {
                    $status = true;
                } else {
                    $status = false;
                }
            }

            // dd($status);
            if ($status) {
                $leave_type_id = $leave_type_id;
                $employee_id = $employee_id;
                if ($leave_type_id != '' && $employee_id != '') {
                    return $this->leaveRepository->calculateEmployeeLeaveBalance1($leave_type_id, $employee_id);
                }
            } else {

                return 0;
            }
        } else {

            return 0;
        }
    }

    public function applyForTotalNumberOfDays($from_date, $to_date, $employee_id)
    {
        $application_from_date = dateConvertFormtoDB($from_date);
        $application_to_date = dateConvertFormtoDB($to_date);
        $data = $this->leaveRepository->calculateTotalNumberOfLeaveDaysIncludingHolidays($application_from_date, $application_to_date, $employee_id);
        return $data['countDay'];
    }

    public function applyForLeave(ApplyForLeaveRequest $request)
    {

        // $request->validate([
        //     'application_from_date' => 'required',
        //     'application_to_date' => 'required',
        //     'leave_type_id' => 'required',
        // ]);

        $leave_status = [];

        $fdate = $request->application_from_date;
        $tdate = $request->application_to_date;
        $leave_type_id = $request->leave_type_id;

        $input = $request->all();
        $input['application_from_date'] = dateConvertFormtoDB($fdate);
        $input['application_to_date'] = dateConvertFormtoDB($tdate);
        $input['application_date'] = date('Y-m-d');

        $existing_records = $this->create($request);
        $total_leave_taken = $existing_records['sumOfLeaveTaken'];

        $leave_avaliable = LeaveType::where('leave_type_id', $leave_type_id)->pluck('num_of_day');
        $leave_taken = LeaveApplication::where('leave_type_id', $leave_type_id)->pluck('number_of_day');
        $common_leave_taken = (int) $leave_taken->sum();
        $common_leave_avaliable = (int) $leave_avaliable->sum();
        $leave_balance = $common_leave_avaliable - $common_leave_taken;

        if ($leave_type_id == 1) {

            $month = date('m', \strtotime($fdate));
            $earn_leave_rule = EarnLeaveRule::sum('day_of_earn_leave');
            $leave_status['leave_status'] = $common_leave_taken < ((int) $month * (int) $earn_leave_rule);
        } elseif ($leave_type_id == 2) {

            $paid_leave_rule = PaidLeaveRule::sum('day_of_paid_leave');
            $leave_status['leave_status'] = $total_leave_taken < $paid_leave_rule;
        } else {
            $datetime1 = new DateTime($fdate);
            $datetime2 = new DateTime($tdate);
            $interval = $datetime1->diff($datetime2);
            $common_leave_applied = $interval->format('%a');
            $leave_status['leave_status'] = $common_leave_applied <= $leave_balance;
        }

        if ($leave_status['leave_status']) {
            try {

                DB::beginTransaction();
                $if_exists = LeaveApplication::where('application_from_date', $input['application_from_date'])->where('application_to_date', $input['application_to_date'])
                    ->where('employee_id', $request->employee_id)->where('status', 2)->count();
                $if_exists > 0 ? $bug = 1 : $bug = null && LeaveApplication::create($input);
                DB::commit();
            } catch (\Exception $e) {

                DB::rollback();
                $bug = $e->getMessage();
            }

            if ($bug == \null) {

                return $this->controller->success("Leave application sent successfully.", \array_merge($input, $leave_status));
            } elseif ($bug == 1) {

                return $this->controller->custom_error("Leave application already exists for selected dates.");
            } else {

                return $this->controller->error();
            }
        }

        return $this->controller->custom_error('Leave Balance Not Avaliable For Selected Leave Type.');
    }

    public function sendLeaveMail($leave_application_id, $emp_id, $finger_id, $name, $email, $from, $to, $type, $days, $application_date)
    {

        $data = [
            'url_a' => 'http://localhost:8074/propeople/mail/approve/' . $leave_application_id,
            'url_b' => 'http://localhost:8074/propeople/mail/reject/' . $leave_application_id,
            'date' => $application_date,
            'name' => $name,
            'emp_id' => $emp_id,
            'finger_id' => $finger_id,
            'email' => $email,
            'from' => $from,
            'to' => $to,
            'type' => $type,
            'days' => $days,
        ];

        Mail::to($email)->send(new LeaveApplicationMail($body = $data));
    }

    public function approve($leave_application_id)
    {

        $bool = LeaveApplication::where('leave_application_id', $leave_application_id)->where('status', 1)->first();

        $raw_data = LeaveApplication::where('leave_application_id', $leave_application_id)->first()->toArray();

        $body = LeaveApplication::join('employee', 'employee.employee_id', '=', 'leave_application.employee_id')
            ->join('leave_type', 'leave_type.leave_type_id', '=', 'leave_application.leave_type_id')
            ->where('leave_application_id', $leave_application_id)->select(
                'leave_type.*',
                'leave_application.*',
                'employee.first_name',
                'employee.email',
                'employee.employee_id',
                'employee.finger_id'
            )->first();

        $employee_id = (session('logged_session_data.employee_id'));

        if (isset($employee_id)) {

            $user_data = (session('logged_session_data')) != null ? ((session('logged_session_data'))) : [];

            $update_data = [
                'approve_by' => $employee_id,
                'approve_date' => date('Y-m-d'),
                'remarks' => 'approved',
                'status' => 2,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $data = \array_merge($raw_data, $update_data);
        }

        if ($bool && (session('logged_session_data')) != null && (session('logged_session_data.role_id')) == 1) {

            LeaveApplication::where('leave_application_id', $leave_application_id)->update($data);

            return view('emails.accepted', ['body' => $body, 'user' => $user_data])->with('status', 'success');
        } elseif ($body->status == 2 && isset($employee_id) && (session('logged_session_data.role_id')) == 1) {

            return view('emails.accepted', ['body' => $body, 'user' => $user_data])->with('status', 'success');
        } elseif ($employee_id == "" || $employee_id == null) {

            return \view('admin.login');
        } else {

            return \view('errors.404');
        }
    }

    public function reject($leave_application_id)
    {
        $update_data = [
            'reject_by' => 1,
            'reject_date' => date('Y-m-d'),
            'remarks' => 'rejected',
            'status' => 3,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $employee_id = (session('logged_session_data.employee_id'));

        if (isset($employee_id)) {
            $user_data = ((session('logged_session_data')));
        }

        $bool = LeaveApplication::where('leave_application_id', $leave_application_id)->where('status', 1)->first();

        $raw_data = LeaveApplication::where('leave_application_id', $leave_application_id)->first()->toArray();

        $data = \array_merge($raw_data, $update_data);

        $body = LeaveApplication::join('employee', 'employee.employee_id', '=', 'leave_application.employee_id')
            ->join('leave_type', 'leave_type.leave_type_id', '=', 'leave_application.leave_type_id')
            ->where('leave_application_id', $leave_application_id)->select(
                'leave_type.*',
                'leave_application.*',
                'employee.first_name',
                'employee.email',
                'employee.employee_id',
                'employee.finger_id'
            )->first();

        if ($bool && (session('logged_session_data.role_id')) == 1) {
            LeaveApplication::where('leave_application_id', $leave_application_id)->update($data);
            return view('emails.rejected', ['body' => $body, 'user' => $user_data])->with('status', 'success');
        } elseif ($body->status == 3 && isset($employee_id) && (session('logged_session_data.role_id')) == 1) {
            return view('emails.rejected', ['body' => $body, 'user' => $user_data])->with('status', 'success');
        } elseif ($employee_id == "" || $employee_id == null) {
            return \view('admin.login');
        } else {
            return \view('errors.404');
        }
    }

    // public function sample(Request $request)
    // {
    //     // $path =  Request::path();
    //     // $getQueryString =  Request::getPathInfo();
    //     // $url = Request::url();
    //     $getFacadeRoot = Route::getFacadeRoot()->current()->uri();
    //     $getCurrentRoute = Route::getCurrentRoute()->getActionName();
    //     $request = $request->is('api/*');

    //     // $array = array($path, $getQueryString, $url);
    //     $array = array($getFacadeRoot, $getCurrentRoute, $request);

    //     return response()->json([
    //         'message' => "API works fine",
    //         'array' => $array,
    //     ], 200);
    // }

    public function approve1(Request $request)
    {
        try {

            $update_data = [
                'approve_by' => 1,
                'approve_date' => date('Y-m-d'),
                'remarks' => 'approved',
                'status' => 2,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $raw_data = LeaveApplication::where('leave_application_id', $request->leave_application_id)->first()->toArray();
            $data = \array_merge($raw_data, $update_data);

            // dd($data);

            DB::beginTransaction();
            LeaveApplication::where('leave_application_id', $request->leave_application_id)->update($data);
            DB::commit();

            $raw_data = LeaveApplication::where('leave_application_id', $request->leave_application_id)->first();

            $responce = $this->success("Leave Details Saved Successfully !", $raw_data);
        } catch (\Throwable $e) {

            DB::rollback();
            $message = $e->getMessage();
            $responce = $this->custom_error($message);
        } finally {

            return $responce;
        }
    }

    public function reject1(Request $request)
    {
        try {

            $update_data = [
                'reject_by' => 1,
                'reject_date' => date('Y-m-d'),
                'remarks' => 'rejected',
                'status' => 3,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $raw_data = LeaveApplication::where('leave_application_id', $request->leave_application_id)->first()->toArray();
            $data = \array_merge($raw_data, $update_data);

            // dd($data);

            DB::beginTransaction();
            $leave_application = LeaveApplication::where('leave_application_id', $request->leave_application_id)->update($data);
            DB::commit();

            $raw_data = LeaveApplication::where('leave_application_id', $request->leave_application_id)->first();

            $responce = $this->success("Leave Details Saved Successfully !", $raw_data);
        } catch (\Throwable $e) {

            DB::rollback();
            $message = $e->getMessage();
            $responce = $this->custom_error($message);
        } finally {

            return $responce;
        }
    }

    public function leaveBalanceTemplate()
    {
    }
    public function importLeaveBalance()
    {
    }
}
