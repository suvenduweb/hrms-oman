<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LeavePermission extends Model
{
  protected $table = 'leave_permission';
  protected $primaryKey = 'leave_permission_id';

  protected $fillable = [
    'leave_permission_id', 'employee_id', 'permission_duration', 'leave_permission_date', 'leave_permission_purpose', 'status', 'manager_status',
    'from_time', 'to_time', 'remarks', 'manager_remarks', 'approved_by', 'approve_date', 'reject_by', 'reject_date'
  ];

  public function employee()
  {
    return $this->belongsTo(Employee::class, 'employee_id')->withDefault(
      [
        'employee_id' => 0,
        'finger_id' => 0,
        'user_id' => 0,
        'department_id' => 0,
        'email' => 'unknown email',
        'first_name' => 'unknown',
        'last_name' => 'unknown last name'

      ]
    );
  }

  public function approveBy()
  {
    return $this->belongsTo(Employee::class, 'approved_by', 'employee_id')->withDefault(
      [
        'employee_id' => 0,
        'finger_id' => 0,
        'user_id' => 0,
        'department_id' => 0,
        'email' => 'unknown email',
        'first_name' => 'unknown',
        'last_name' => 'unknown last name'

      ]
    );
  }

  public function rejectBy()
  {
    return $this->belongsTo(Employee::class, 'reject_by', 'employee_id')->withDefault(
      [
        'employee_id' => 0,
        'finger_id' => 0,
        'user_id' => 0,
        'department_id' => 0,
        'email' => 'unknown email',
        'first_name' => 'unknown',
        'last_name' => 'unknown last name'
      ]
    );
  }
}
