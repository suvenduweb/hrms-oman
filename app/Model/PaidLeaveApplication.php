<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaidLeaveApplication extends Model
{

    protected $table      = 'paid_leave_applications';
    protected $primaryKey = 'paid_leave_application_id';

    protected $fillable = [
        'paid_leave_application_id', 'leave_type_id',  'employee_id', 'application_from_date', 'application_to_date', 'application_date',
        'number_of_day', 'approve_date', 'approve_by', 'reject_date', 'reject_by', 'purpose', 'remarks', 'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id')->withDefault(
            [
                'employee_id'   => 0,
                'user_id'       => 0,
                'department_id' => 0,
                'email'         => 'unknown email',
                'first_name'    => 'unknown',
                'last_name'     => 'unknown last name',

            ]
        );
    }

    public function approveBy()
    {
        return $this->belongsTo(Employee::class, 'approve_by', 'employee_id')->withDefault(
            [
                'employee_id'   => 0,
                'user_id'       => 0,
                'department_id' => 0,
                'email'         => 'unknown email',
                'first_name'    => 'unknown',
                'last_name'     => 'unknown last name',

            ]
        );
    }

    public function rejectBy()
    {
        return $this->belongsTo(Employee::class, 'reject_by', 'employee_id')->withDefault(
            [
                'employee_id'   => 0,
                'user_id'       => 0,
                'department_id' => 0,
                'email'         => 'unknown email',
                'first_name'    => 'unknown',
                'last_name'     => 'unknown last name',

            ]
        );
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id')->withDefault(
            [
                'employee_id'   => 0,
                'user_id'       => 0,
                'department_id' => 0,
                'email'         => 'unknown email',
                'first_name'    => 'unknown',
                'last_name'     => 'unknown last name',

            ]
        );
    }

}
