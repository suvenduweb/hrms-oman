<?php

namespace App\Repositories;

use App\Model\Role;
use App\Model\Employee;
use App\Model\PayGrade;
use App\Model\WorkShift;
use App\Model\TrainingType;
use App\Model\PerformanceCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CommonRepository
{

    public function roleList()
    {
        $results = Role::get();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->role_id] = $value->role_name;
        }
        return $options;
    }

    public function userList()
    {
        $results = DB::table('user')->where('status', 1)->get();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->user_id] = $value->user_name;
        }
        return $options;
    }

    public function departmentList()
    {
        $results = DB::table('department')->get();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->department_id] = $value->department_name;
        }
        return $options;
    }

    public function monthList()
    {

        $results = array();
        $options =  ['' => '---- Please select ----'];
        $startDate = Date('Y-m-d', strtotime('-6 months'));
        $endDate = Date('Y-m-d', strtotime('+6 months'));
        $i = -1;
        while (strtotime($startDate) <= strtotime($endDate)) {
            $i++;
            $options[$i] = date('Y', strtotime($startDate)) . '-' . date('m', strtotime($startDate));
            $startDate = date('01 M Y', strtotime($startDate . '+ 1 month'));
        }
        return $options;
    }

    public function shiftList()
    {
        $results = DB::table('work_shift')->get();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->work_shift_id] = $value->shift_name;
        }
        return $options;
    }

    public function designationList()
    {
        $results = DB::table('designation')->get();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->designation_id] = $value->designation_name;
        }
        return $options;
    }

    public function branchList()
    {
        $results = DB::table('branch')->get();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->branch_id] = $value->branch_name;
        }
        return $options;
    }

    public function supervisorList()
    {
        $results = DB::table('employee')->where('status', 1)->get();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->employee_id] = $value->first_name . ' ' . $value->last_name;
        }
        return $options;
    }

    public function holidayList()
    {
        $results = DB::table('holiday')->get();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->holiday_id] = $value->holiday_name;
        }
        return $options;
    }

    public function leaveTimingList()
    {
        $results = ['Half Day', 'Full Day'];
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$key] = $value;
        }
        return $options;
    }
    public function leaveTypeListapi($employee_id)
    {

        // info($employee_id);

        $results = DB::table('leave_type')->get();

        $employee = Employee::where('employee_id', $employee_id)->first();

        // info($employee);
        // dd($employee);
        $options = ['' => '---- Please select ----'];
        // info($results);
        foreach ($results as $leaveType) {
            info($leaveType->gender);
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
                // dd(123);
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
            if ($status) {
                $options[$leaveType->leave_type_id] = $leaveType->leave_type_name;
            }
        }
        // }
        // foreach ($results as $key => $value) {
        //     $options[$value->leave_type_id] = $value->leave_type_name;
        // }
        return $options;
    }

    public function weekList()
    {
        $results = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value] = $value;
        }
        return $options;
    }

    public function leaveTypeList()
    {
        $results = DB::table('leave_type')->get();
        $employee = Employee::where('employee_id', decrypt(session('logged_session_data.employee_id')))->first();
        $options = ['' => '---- Please select ----'];

        foreach ($results as $leaveType) {
            if ($leaveType->nationality == 2 && $leaveType->religion == 2 && $leaveType->gender == 2) {
                $status = true;
                $nationalityStatus =  $religionStatus = $genderStatus = 1;
                info('222');
            } elseif ($leaveType->nationality == 2  && $leaveType->religion != 2 && $leaveType->gender != 2) {
                $nationalityStatus = 1;
                info('200');
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
                info('020');
                info([$employee->nationality, $leaveType->nationality]);

                if ($leaveType->nationality != 2) {
                    if ($leaveType->nationality == $employee->nationality) {
                        $nationalityStatus = 1;
                        $status = 1;
                    }
                } else {
                    $nationalityStatus = 0;
                }
                info("na" . $nationalityStatus);
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
                info('002');

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
                info('220');

                $nationalityStatus = $religionStatus = 1;
                if ($leaveType->gender == $employee->gender) {
                    $genderStatus = 1;
                } else {
                    $genderStatus = 0;
                }
            } elseif ($leaveType->nationality == 2 && $leaveType->gender == 2 && $leaveType->religion != 2) {
                info('202');
                $nationalityStatus = $genderStatus = 1;
                if ($leaveType->religion == $employee->religion) {
                    $religionStatus = 1;
                } else {
                    $religionStatus = 0;
                }
            } elseif ($leaveType->religion == 2 && $leaveType->gender == 2 && $leaveType->nationality != 2) {
                info('022');
                $religionStatus = $genderStatus = 1;
                if ($leaveType->nationality == $employee->nationality) {
                    $nationalityStatus = 1;
                } else {
                    $nationalityStatus = 0;
                }
            } elseif ($leaveType->nationality != 2 && $leaveType->religion != 2 && $leaveType->gender != 2) {
                // dd(123);
                info('002');
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
            info([$religionStatus, $nationalityStatus, $genderStatus]);

            if ($religionStatus == 1 && $nationalityStatus == 1 && $genderStatus == 1) {

                $status = 1;
            } else {
                $status = 0;
            }
            if ($status) {
                $options[$leaveType->leave_type_id] = $leaveType->leave_type_name;
            }
        }
        return $options;
    }
    public function leaveTypeList1($id)
    {
        $results = DB::table('leave_type')->get();
        $employee = Employee::where('employee_id', $id)->first();
        $options = ['' => '---- Please select ----'];

        foreach ($results as $leaveType) {
            if ($leaveType->nationality == 2 && $leaveType->religion == 2 && $leaveType->gender == 2) {
                $status = true;
                $nationalityStatus =  $religionStatus = $genderStatus = 1;
                info('222');
            } elseif ($leaveType->nationality == 2  && $leaveType->religion != 2 && $leaveType->gender != 2) {
                $nationalityStatus = 1;
                info('200');
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
                info('020');
                info([$employee->nationality, $leaveType->nationality]);

                if ($leaveType->nationality != 2) {
                    if ($leaveType->nationality == $employee->nationality) {
                        $nationalityStatus = 1;
                        $status = 1;
                    }
                } else {
                    $nationalityStatus = 0;
                }
                info("na" . $nationalityStatus);
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
                info('002');

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
                info('220');

                $nationalityStatus = $religionStatus = 1;
                if ($leaveType->gender == $employee->gender) {
                    $genderStatus = 1;
                } else {
                    $genderStatus = 0;
                }
            } elseif ($leaveType->nationality == 2 && $leaveType->gender == 2 && $leaveType->religion != 2) {
                info('202');
                $nationalityStatus = $genderStatus = 1;
                if ($leaveType->religion == $employee->religion) {
                    $religionStatus = 1;
                } else {
                    $religionStatus = 0;
                }
            } elseif ($leaveType->religion == 2 && $leaveType->gender == 2 && $leaveType->nationality != 2) {
                info('022');
                $religionStatus = $genderStatus = 1;
                if ($leaveType->nationality == $employee->nationality) {
                    $nationalityStatus = 1;
                } else {
                    $nationalityStatus = 0;
                }
            } elseif ($leaveType->nationality != 2 && $leaveType->religion != 2 && $leaveType->gender != 2) {
                // dd(123);
                info('002');
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
            info([$religionStatus, $nationalityStatus, $genderStatus]);

            if ($religionStatus == 1 && $nationalityStatus == 1 && $genderStatus == 1) {

                $status = 1;
            } else {
                $status = 0;
            }
            if ($status) {
                $options[$leaveType->leave_type_id] = $leaveType->leave_type_name;
            }
        }
        return $options;
    }

    public function paidLeaveTypeList()
    {
        $results = DB::table('leave_type')->where('leave_type_id', 2)->get();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->leave_type_id] = $value->leave_type_name;
        }
        return $options;
    }

    public function workShiftList()
    {
        $results = WorkShift::get();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->work_shift_id] = $value->work_shift_name;
        }
        return $options;
    }

    public function employeeList()
    {
        $results = Employee::where('status', 1)->get();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->employee_id] = $value->first_name . ' ' . $value->last_name;
        }
        return $options;
    }
    public function employeeInfo()
    {
        if (decrypt(session('logged_session_data.role_id')) == 1 || decrypt(session('logged_session_data.role_id')) == 2) {
            $results = Employee::where('status', 1)->get();
        } else {
            $results = Employee::where('status', 1)->where('employee_id', decrypt(session('logged_session_data.employee_id')))->get();
        }

        foreach ($results as $key => $value) {
            $options[$value->employee_id] = $value->first_name . ' ' . $value->last_name;
        }
        return $options;
    }

    public function employeeFingerList()
    {
        if (decrypt(session('logged_session_data.role_id')) == 1 || decrypt(session('logged_session_data.role_id')) == 2) {
            $results = Employee::where('status', 1)->get();
        } else {
            $results = Employee::where('status', 1)->where("operation_manager_id",  decrypt(session('logged_session_data.employee_id')))->get();
        }
        // $results = Employee::where('status', 1)->get();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->finger_id] = $value->first_name . ' ' . $value->last_name;
        }
        return $options;
    }
    public function employeeFingerListOT()
    {
        // {{-- al khaleel change --}}
        if (decrypt(session('logged_session_data.role_id')) == 1 || decrypt(session('logged_session_data.role_id')) == 2) {
            $results = Employee::where('status', 1)->get();
        } else {
            $results = Employee::where('status', 1)->where("operation_manager_id",  decrypt(session('logged_session_data.employee_id')))->get();
        }

        // {{-- al khaleel change --}}
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->finger_id] = $value->first_name . ' ' . $value->last_name;
        }
        return $options;
    }

    public function employeeFingerListWithFilter($field, $value)
    {
        $results = Employee::where('status', 1)->where($field, $value)->get();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->finger_id] = $value->first_name . ' ' . $value->last_name;
        }
        return $options;
    }

    public function incentiveEmployeeFingerList()
    {
        $results = Employee::where('status', 1)->where('incentive', 1)->get();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->finger_id] = $value->first_name . ' ' . $value->last_name;
        }
        return $options;
    }

    public function compOffEmployeeFingerList()
    {
        $results = Employee::where('status', 1)->where('incentive', 0)->get();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->finger_id] = $value->first_name . ' ' . $value->last_name;
        }
        return $options;
    }

    public function getLimitedEmployeeInfo($id)
    {
        return Employee::where('user_id', $id)->first()->makeHidden(['document_title', 'document_name', 'document_expiry', 'document_title2', 'document_name2', 'document_expiry2', 'document_title3', 'document_name3', 'document_expiry3', 'document_title4', 'document_name4', 'document_expiry4', 'document_title5', 'document_name5', 'document_expiry5']);
    }

    public function performanceCategoryList()
    {
        $results = PerformanceCategory::all();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->performance_category_id] = $value->performance_category_name;
        }
        return $options;
    }

    public function getEmployeeInfo($id)
    {
        return Employee::where('user_id', $id)->first();
    }

    public function getEmployeeDetails($id)
    {
        return Employee::where('employee_id', $id)->first();
    }

    public function trainingTypeList()
    {
        $results = TrainingType::where('status', 1)->get();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->training_type_id] = $value->training_type_name;
        }
        return $options;
    }

    public function payGradeList()
    {
        $results = PayGrade::all();
        $options = ['' => '---- Please select ----'];
        foreach ($results as $key => $value) {
            $options[$value->pay_grade_id] = $value->pay_grade_name;
        }
        return $options;
    }
}
