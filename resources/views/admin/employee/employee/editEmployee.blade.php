@php
    use App\Model\AccessControl;
@endphp
@extends('admin.master')

@section('content')

@section('title')
    @lang('employee.edit_employee')
@endsection

<style>
    .appendBtnColor {

        color: #fff;

        font-weight: 700;

    }
</style>



<div class="container-fluid">

    <div class="row bg-title">

        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">

            <ol class="breadcrumb">

                <li class="active breadcrumbColor"><a href="{{ url('dashboard') }}"><i class="fa fa-home"></i>
                        @lang('dashboard.dashboard')</a></li>

                <li>@yield('title')</li>



            </ol>

        </div>

        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">

            <a href="{{ route('employee.index') }}"
                class="btn btn-success pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"><i
                    class="fa fa-list-ul" aria-hidden="true"></i> @lang('employee.view_employee')</a>

        </div>

    </div>

    <div class="row">

        <div class="col-md-12">

            <div class="panel panel-info">

                <div class="panel-heading"><i class="mdi mdi-clipboard-text fa-fw"></i> @yield('title')</div>

                <div class="panel-wrapper collapse in" aria-expanded="true">

                    <div class="panel-body">

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible" role="alert">

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">x</span></button>

                                @foreach ($errors->all() as $error)
                                    <strong>{!! $error !!}</strong><br>
                                @endforeach

                            </div>
                        @endif

                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissable">

                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>

                                <i
                                    class="cr-icon glyphicon glyphicon-ok"></i>&nbsp;<strong>{{ session()->get('success') }}</strong>

                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissable">

                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>

                                <i
                                    class="glyphicon glyphicon-remove"></i>&nbsp;<strong>{{ session()->get('error') }}</strong>

                            </div>
                        @endif

                        {{ Form::model($editModeData, ['route' => ['employee.update', $editModeData->employee_id], 'method' => 'PUT', 'files' => 'true', 'id' => 'employeeForm']) }}

                        <input class="form-control  delete_education_qualifications_cid"
                            id="delete_education_qualifications_cid" name="delete_education_qualifications_cid"
                            type="hidden" value="">

                        <input class="form-control  delete_experiences_cid" id="delete_experiences_cid"
                            name="delete_experiences_cid" type="hidden" value="">

                        <div class="form-body">

                            <h3 class="box-title">@lang('employee.employee_account')</h3>

                            <hr>

                            <div class="row">

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('employee.role')<span
                                                class="validateRq">*</span></label>

                                        <select name="role_id" class="form-control user_id required select2" required>

                                            <option value="">--- @lang('common.please_select') ---</option>

                                            @foreach ($roleList as $value)
                                                <option value="{{ $value->role_id }}"
                                                    @if ($value->role_id == $employeeAccountEditModeData->role_id) {{ 'selected' }} @endif>
                                                    {{ $value->role_name }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <label for="exampleInput">@lang('employee.user_name')<span class="validateRq">*</span></label>

                                    <div class="input-group">

                                        <div class="input-group-addon"><i class="ti-user"></i></div>

                                        <input class="form-control required user_name" required id="user_name"
                                            placeholder="@lang('employee.user_name')" name="user_name" type="text"
                                            value="{{ $employeeAccountEditModeData->user_name }}">

                                    </div>

                                </div>

                            </div>

                            <h3 class="box-title">@lang('employee.personal_information')</h3>

                            <hr>

                            <div class="row">

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('employee.first_name')<span
                                                class="validateRq">*</span></label>

                                        <input class="form-control required first_name" id="first_name"
                                            placeholder="@lang('employee.first_name')" name="first_name" type="text"
                                            value="{{ $editModeData->first_name }}">

                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('employee.last_name')</label>

                                        <input class="form-control last_name" id="last_name"
                                            placeholder="@lang('employee.last_name')" name="last_name" type="text"
                                            value="{{ $editModeData->last_name }}">

                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('employee.finger_print_no')<span
                                                class="validateRq">*</span></label>

                                        <input class="form-control number finger_id" id="finger_id"
                                            placeholder="@lang('employee.finger_print_no')" name="finger_id" type="text"
                                            value="{{ $editModeData->finger_id }}">

                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('employee.supervisor')</label>

                                        <select name="supervisor_id"
                                            class="form-control supervisor_id required select2">

                                            <option value="">--- @lang('common.please_select') ---</option>

                                            @foreach ($supervisorList as $value)
                                                <option value="{{ $value->employee_id }}"
                                                    @if ($value->employee_id == $editModeData->supervisor_id) {{ 'selected' }} @endif>
                                                    {{ $value->first_name }} {{ $value->last_name }}
                                                    {{ ' (' . $value->finger_id . ')' }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                            </div>



                            <div class="row">

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('department.department_name')<span
                                                class="validateRq">*</span></label>

                                        <select name="department_id" class="form-control department_id  select2">

                                            <option value="">--- @lang('common.please_select') ---</option>

                                            @foreach ($departmentList as $value)
                                                <option value="{{ $value->department_id }}"
                                                    @if ($value->department_id == $editModeData->department_id) {{ 'selected' }} @endif>
                                                    {{ $value->department_name }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('designation.designation_name')<span
                                                class="validateRq">*</span></label>

                                        <select name="designation_id" class="form-control department_id select2">

                                            <option value="">--- @lang('common.please_select') ---</option>

                                            @foreach ($designationList as $value)
                                                <option value="{{ $value->designation_id }}"
                                                    @if ($value->designation_id == $editModeData->designation_id) {{ 'selected' }} @endif>
                                                    {{ $value->designation_name }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('branch.branch_name')<span
                                                class="validateRq">*</span></label>

                                        <select name="branch_id" class="form-control branch_id select2">

                                            <option value="">--- @lang('common.please_select') ---</option>

                                            @foreach ($branchList as $value)
                                                <option value="{{ $value->branch_id }}"
                                                    @if ($value->branch_id == $editModeData->branch_id) {{ 'selected' }} @endif>
                                                    {{ $value->branch_name }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>
                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('employee.operation_manager')</label>

                                        <select name="operation_manager_id"
                                            class="form-control operation_manager_id required select2">

                                            <option value="">--- @lang('common.please_select') ---</option>

                                            @foreach ($operationManagerList as $value)
                                                <option value="{{ $value->employee_id }}"
                                                    @if ($value->employee_id == $editModeData->operation_manager_id) {{ 'selected' }} @endif>
                                                    {{ $value->first_name . ' ' . $value->last_name }}
                                                    {{ ' (' . $value->finger_id . ')' }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>


                                <div class="col-md-3" hidden>

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('work_shift.work_shift_name')<span
                                                class="validateRq">*</span></label>

                                        <select name="work_shift_id" class="form-control work_shift_id select2">

                                            <option value="">--- @lang('common.please_select') ---</option>

                                            @foreach ($workShiftList as $value)
                                                <option value="{{ $value->work_shift_id }}"
                                                    @if ($value->work_shift_id == $editModeData->work_shift_id) {{ 'selected' }} @endif>
                                                    {{ $value->shift_name }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                                <div class="col-md-3" hidden>
                                    <div class="form-group">
                                        <label for="exampleInput">@lang('employee.work_shift')<span
                                                class="validateRq">*</span></label>
                                        {{ Form::select('work_shift', $workShift, Input::old('work_shift'), ['class' => 'form-control work_shift select2 required']) }}
                                    </div>
                                </div>

                            </div>



                            <div class="row">

                                <div class="col-md-3" hidden>

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('employee.montly_paygrade')<span
                                                class="validateRq">*</span></label>

                                        <select name="pay_grade_id" class="form-control pay_grade_id">

                                            <option value="">--- @lang('common.please_select') ---</option>

                                            @foreach ($payGradeList as $value)
                                                <option value="{{ $value->pay_grade_id }}"
                                                    @if ($value->pay_grade_id == $editModeData->pay_grade_id) {{ 'selected' }} @endif>
                                                    {{ $value->pay_grade_name }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                                <div class="col-md-3" hidden>

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('employee.hourly_paygrade')<span
                                                class="validateRq">*</span></label>

                                        <select name="hourly_salaries_id" class="form-control hourly_pay_grade_id">

                                            <option value="">--- @lang('common.please_select') ---</option>

                                            @foreach ($hourlyPayGradeList as $value)
                                                <option value="{{ $value->hourly_salaries_id }}"
                                                    @if ($value->hourly_salaries_id == $editModeData->hourly_salaries_id) {{ 'selected' }} @endif>
                                                    {{ $value->hourly_grade }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('employee.gender')<span
                                                class="validateRq">*</span></label>

                                        <select name="gender" class="form-control gender select2">
                                            <option value="">--- @lang('common.please_select') ---</option>
                                            <option value="Male"
                                                @if ($editModeData->gender == '0') {{ 'selected' }} @endif>
                                                @lang('employee.male')
                                            </option>
                                            <option value="Female"
                                                @if ($editModeData->gender == '1') {{ 'selected' }} @endif>
                                                @lang('employee.female')
                                            </option>
                                        </select>


                                    </div>

                                </div>

                                <div class="col-md-3" hidden>

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('employee.religion')</label>

                                        <input class="form-control religion" id="religion"
                                            placeholder="@lang('employee.religion')" name="religion" type="text"
                                            value="{{ $editModeData->religion }}">

                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <label for="exampleInput">@lang('employee.email')</label>

                                    <div class="input-group">

                                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>

                                        <input class="form-control email" id="email"
                                            placeholder="@lang('employee.email')" name="email" type="email"
                                            value="{{ $editModeData->email }}">

                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <label for="exampleInput">@lang('employee.phone')<span
                                            class="validateRq">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>

                                        <input class="form-control number phone" id="phone"
                                            placeholder="@lang('employee.phone')" name="phone" type="number"
                                            value="{{ $editModeData->phone }}">

                                    </div>

                                </div>
                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('employee.address')</label>

                                        <textarea class="form-control address" id="address" placeholder="@lang('employee.address')" cols="30"
                                            rows="1" name="address">{{ $editModeData->address }}</textarea>

                                    </div>

                                </div>

                            </div>



                            <div class="row">



                                <div class="col-md-3">

                                    <label for="exampleInput">@lang('employee.date_of_birth')<span
                                            class="validateRq">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>

                                        <input class="form-control date_of_birth dateField" id="date_of_birth"
                                            readonly placeholder="@lang('employee.date_of_birth')" name="date_of_birth"
                                            type="text"
                                            value="{{ dateConvertDBtoForm($editModeData->date_of_birth) }}">

                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <label for="exampleInput">@lang('employee.date_of_joining')<span
                                            class="validateRq">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>

                                        <input class="form-control date_of_joining dateField" id="date_of_joining"
                                            readonly placeholder="@lang('employee.date_of_joining')" name="date_of_joining"
                                            type="text"
                                            value="{{ dateConvertDBtoForm($editModeData->date_of_joining) }}">

                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">@lang('employee.category')<span
                                                class="validateRq">*</span></label>
                                        <select name="employee_category" id=""
                                            class="form-control nationality select2 required">
                                            <option value="">Select Category</option>
                                            <option {{ old('employee_category') == 0 ? 'selected' : '' }}
                                                value="0">
                                                Employee</option>
                                            <option {{ old('employee_category') == 1 ? 'selected' : '' }}
                                                value="1">
                                                Chiefs'</option>
                                        </select>
                                    </div>
                                </div>



                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('employee.marital_status')</label>

                                        <select name="marital_status" class="form-control status required select2">

                                            <option value="">--- Please select ---</option>

                                            <option value="Unmarried"
                                                @if ('Unmarried' == $editModeData->marital_status) {{ 'selected' }} @endif>
                                                @lang('employee.unmarried')</option>

                                            <option value="Married"
                                                @if ('Married' == $editModeData->marital_status) {{ 'selected' }} @endif>
                                                @lang('employee.married')</option>

                                        </select>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label for="exampleInput">@lang('employee.emergency_contact')</label>

                                        <textarea class="form-control emergency_contacts" id="emergency_contacts" placeholder="@lang('employee.emergency_contact')"
                                            cols="30" rows="1" name="emergency_contacts">{{ $editModeData->emergency_contacts }}</textarea>

                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">@lang('employee.nationality')<span
                                                class="validateRq">*</span></label>
                                        {{ Form::select('nationality', $nationality, old('nationality'), ['class' => 'form-control nationality select2 required']) }}
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">

                                        <label for="exampleInput">@lang('employee.country')<span
                                                class="validateRq">*</span></label>

                                        <input class="form-control required country" required id="country"
                                            placeholder="@lang('employee.country')" name="country" type="text"
                                            value="{{ $editModeData->country }}">
                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">@lang('employee.religion')<span
                                                class="validateRq">*</span></label>
                                        {{ Form::select('religion', $religion, old('religion'), ['class' => 'form-control religion select2 required']) }}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="exampleInput">@lang('employee.date_of_leaving')</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input class="form-control  date_of_leaving dateField" id="date_of_leaving"
                                            readonly placeholder="@lang('employee.date_of_leaving')" name="date_of_leaving"
                                            type="text"
                                            value="{{ dateConvertDBtoForm($editModeData->date_of_leaving) }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Status<span class="validateRq">*</span></label>
                                        <select name="status" class="form-control status select2">
                                            <option value="1"
                                                @if ('1' == $editModeData->status) {{ 'selected' }} @endif>
                                                @lang('common.active')</option>
                                            <option value="2"
                                                @if ('2' == $editModeData->status) {{ 'selected' }} @endif>
                                                @lang('common.inactive')</option>
                                            <option value="3"
                                                @if ('3' == $editModeData->status) {{ 'selected' }} @endif>
                                                @lang('common.terminated')</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Status Remark<span
                                                class="validateRq"></span></label>
                                        <textarea class="form-control status_remark" id="status_remark" placeholder="Status Remark" cols="30"
                                            rows="1" name="status_remark">{{ $editModeData->status_remark }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">IP Attendance<span
                                                class="validateRq">*</span></label>
                                        <select name="ip_attendance" class="form-control gender select2">
                                            <option value="">--- Please Select ---</option>
                                            <option value="1"
                                                @if ($editModeData->ip_attendance == '1') {{ 'selected' }} @endif>
                                                Yes
                                            </option>
                                            <option value="0"
                                                @if ($editModeData->ip_attendance == '0') {{ 'selected' }} @endif>
                                                No
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">@lang('employee.mobile_attendance')<span
                                                class="validateRq">*</span></label>
                                        <select name="mobile_attendance"
                                            class="form-control mobile_attendance select2" required>
                                            <option value="">---please select---</option>
                                            <option
                                                value="1"@if ($editModeData->mobile_attendance == '1') {{ 'selected' }} @endif>
                                                Yes </option>
                                            <option
                                                value="0"@if ($editModeData->mobile_attendance == '0') {{ 'selected' }} @endif>
                                                No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInput">@lang('employee.photo')</label>

                                    <div class="input-group">

                                        <span class="input-group-addon"><i class="	fa fa-picture-o"></i></span>

                                        <input class="form-control photo" id="photo"
                                            accept="image/png, image/jpeg, image/gif,image/jpg" name="photo"
                                            type="file">
                                    </div>
                                </div>
                            </div>
                            <br>

                            <h3> Bank Details</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">@lang('employee.account_number')</label>
                                        <input class="form-control account_number" id="account_number"
                                            placeholder="@lang('employee.account_number')" name="account_number" type="text"
                                            value="{{ $editModeData->account_number ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">@lang('employee.ifsc_number')</label>
                                        <input class="form-control ifsc_number" id="ifsc_number"
                                            placeholder="@lang('employee.ifsc_number')" name="ifsc_number" type="text"
                                            value="{{ $editModeData->ifsc_number ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">@lang('employee.name_of_the_bank')</label>
                                        <input class="form-control name_of_the_bank" id="name_of_the_bank"
                                            placeholder="@lang('employee.name_of_the_bank')" name="name_of_the_bank" type="text"
                                            value="{{ $editModeData->name_of_the_bank ?? '' }}">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">@lang('employee.account_holder')</label>
                                        <input class="form-control account_holder" id="account_holder"
                                            placeholder="@lang('employee.account_holder')" name="account_holder" type="text"
                                            value="{{ $editModeData->account_holder ?? '' }}">
                                    </div>
                                </div>

                            </div>

                            <h3 class="box-title">@lang('paygrade.salary_paygrade')</h3>
                            <hr>
                            <div class="paygrade_append_div">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInput">@lang('paygrade.basic_salary')</label>
                                            {!! Form::number(
                                                'basic_salary',
                                                Input::old('basic_salary'),
                                                $attributes = [
                                                    'class' => 'form-control required basic_salary',
                                                    'id' => 'basic_salary',
                                                    'placeholder' => __('paygrade.basic_salary'),
                                                    'min' => '0',
                                                    'step' => 'any',
                                                ],
                                            ) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInput">@lang('paygrade.increment')</label>
                                            <input class="form-control increment" id="increment"
                                                placeholder="@lang('paygrade.increment')" name="increment" type="number"
                                                step="any" value="{{ $editModeData->increment ?? '' }}">

                                        </div>
                                    </div>

                                    <div class="col-md-3">

                                        <div class="form-group">

                                            <label for="exampleInput">@lang('paygrade.housing_allowance')</label>

                                            <input class="form-control housing_allowance" id="housing_allowance"
                                                placeholder="@lang('paygrade.housing_allowance')" name="housing_allowance"
                                                step="any" type="number"
                                                value="{{ $editModeData->housing_allowance ?? '' }}">

                                        </div>

                                    </div>
                                    <div class="col-md-3">

                                        <div class="form-group">

                                            <label for="exampleInput">@lang('paygrade.utility_allowance')</label>

                                            <input class="form-control utility_allowance" id="utility_allowance"
                                                placeholder="@lang('paygrade.utility_allowance')" name="utility_allowance"
                                                step="any" type="number"
                                                value="{{ $editModeData->utility_allowance ?? '' }}">

                                        </div>

                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-3">

                                        <div class="form-group">

                                            <label for="exampleInput">@lang('paygrade.transport_allowance')</label>

                                            <input class="form-control transport_allowance" id="transport_allowance"
                                                placeholder="@lang('paygrade.transport_allowance')" name="transport_allowance"
                                                step="any" type="number"
                                                value="{{ $editModeData->transport_allowance ?? '' }}">

                                        </div>

                                    </div>
                                    <div class="col-md-3">

                                        <div class="form-group">

                                            <label for="exampleInput">@lang('paygrade.living_allowance')</label>

                                            <input class="form-control living_allowance" id="living_allowance"
                                                placeholder="@lang('paygrade.living_allowance')" name="living_allowance"
                                                step="any" type="number"
                                                value="{{ $editModeData->living_allowance ?? '' }}">

                                        </div>

                                    </div>
                                    <div class="col-md-3">

                                        <div class="form-group">

                                            <label for="exampleInput">@lang('paygrade.mobile_allowance')</label>

                                            <input class="form-control mobile_allowance" id="mobile_allowance"
                                                placeholder="@lang('paygrade.mobile_allowance')" name="mobile_allowance"
                                                step="any" type="number"
                                                value="{{ $editModeData->mobile_allowance ?? '' }}">

                                        </div>

                                    </div>
                                    <div class="col-md-3">

                                        <div class="form-group">

                                            <label for="exampleInput">@lang('paygrade.special_allowance')</label>

                                            <input class="form-control special_allowance" id="special_allowance"
                                                placeholder="@lang('paygrade.special_allowance')" name="special_allowance"
                                                step="any" type="number"
                                                value="{{ $editModeData->special_allowance ?? '' }}">

                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">

                                        <div class="form-group">

                                            <label for="exampleInput">@lang('paygrade.premium_others')</label>

                                            <input class="form-control prem_others" id="prem_others"
                                                placeholder="Premium and Others" step="any" name="prem_others"
                                                type="number" value="{{ $editModeData->prem_others ?? '' }}">

                                        </div>

                                    </div>
                                    <div class="col-md-3">

                                        <div class="form-group">

                                            <label for="exampleInput">@lang('paygrade.education_and_club_allowance')</label>

                                            <input class="form-control education_and_club_allowance"
                                                id="education_and_club_allowance" placeholder="@lang('paygrade.education_and_club_allowance')"
                                                name="education_and_club_allowance" type="number"
                                                value="{{ $editModeData->education_and_club_allowance ?? '' }}"
                                                step="any">

                                        </div>

                                    </div>
                                    <div class="col-md-3">

                                        <div class="form-group">

                                            <label for="exampleInput">@lang('paygrade.membership_allowance')</label>

                                            <input class="form-control membership_allowance" id="membership_allowance"
                                                placeholder="@lang('paygrade.membership_allowance')" name="membership_allowance"
                                                type="number"
                                                value="{{ $editModeData->membership_allowance ?? '' }}"
                                                step="any">

                                        </div>

                                    </div>
                                </div>

                            </div>
                            <h3> Reminder Document Details</h3>
                            <hr>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Passport Number</label>
                                        <input type="text" class="form-control" name="document_title8"
                                            value="{{ $editModeData->document_title8 ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Upload Document</label>
                                        <input class="form-control photo" id="document-file"
                                            accept="image/png, image/jpeg, application/pdf" name="document_file8"
                                            type="file">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Expiry Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input class="form-control dateField" readonly required id="expiry_date8"
                                                placeholder="Document Expiry" name="expiry_date8" type="text"
                                                value="{{ $editModeData->expiry_date8 ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    @if ($editModeData->document_name8)
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <div class="input-group">
                                                <a href="{{ asset('/uploads/employeeDocuments/') }}/{{ $editModeData->document_name8 }}"
                                                    download>
                                                    <p class="text-info" style="padding-top: 8px; ">Download
                                                        File</p>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <p class="text-info" style="padding-top: 8px; ">No Document</p>
                                        </div>
                                    @endif

                                </div>



                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Visa Number</label>
                                        <input type="text" class="form-control" name="document_title9"
                                            value="{{ $editModeData->document_title9 }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Upload Document</label>
                                        <input class="form-control photo" id="document-file"
                                            accept="image/png, image/jpeg, application/pdf" name="document_file9"
                                            type="file">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Expiry Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input class="form-control dateField" readonly required id="expiry_date9"
                                                placeholder="Document Expiry" name="expiry_date9" type="text"
                                                value="{{ $editModeData->expiry_date9 ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    @if ($editModeData->document_name9)
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <div class="input-group">
                                                <a href="{{ asset('/uploads/employeeDocuments/') }}/{{ $editModeData->document_name9 }}"
                                                    download>
                                                    <p class="text-info" style="padding-top: 8px; ">Download
                                                        File</p>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <p class="text-info" style="padding-top: 8px; ">No Document</p>
                                        </div>
                                    @endif

                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Driving Licence Number</label>
                                        <input type="text" class="form-control" name="document_title10"
                                            value="{{ $editModeData->document_title10 ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Upload Document</label>
                                        <input class="form-control photo" id="document-file"
                                            accept="image/png, image/jpeg, application/pdf" name="document_file10"
                                            type="file">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Expiry Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input class="form-control dateField" readonly required id="expiry_date10"
                                                placeholder="Document Expiry" name="expiry_date10" type="text"
                                                value="{{ $editModeData->expiry_date10 ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    @if ($editModeData->document_name10)
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <div class="input-group">
                                                <a href="{{ asset('/uploads/employeeDocuments/') }}/{{ $editModeData->document_name10 }}"
                                                    download>
                                                    <p class="text-info" style="padding-top: 8px; ">Download
                                                        File</p>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <p class="text-info" style="padding-top: 8px; ">No Document</p>
                                        </div>
                                    @endif

                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Civil Id Number</label>
                                        <input type="text" class="form-control" name="document_title11"
                                            value="{{ $editModeData->document_title11 ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Upload Document</label>
                                        <input class="form-control photo" id="document-file"
                                            accept="image/png, image/jpeg, application/pdf" name="document_file11"
                                            type="file">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Expiry Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input class="form-control dateField" readonly required id="expiry_date11"
                                                placeholder="Document Expiry" name="expiry_date11" type="text"
                                                value="{{ $editModeData->expiry_date11 ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    @if ($editModeData->document_name11)
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <div class="input-group">
                                                <a href="{{ asset('/uploads/employeeDocuments/') }}/{{ $editModeData->document_name11 }}"
                                                    download>
                                                    <p class="text-info" style="padding-top: 8px; ">Download
                                                        File</p>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <p class="text-info" style="padding-top: 8px; ">No Document</p>
                                        </div>
                                    @endif

                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Document 5 Title</label>
                                        <input type="text" class="form-control" name="document_title16"
                                            value="{{ $editModeData->document_title16 ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Upload Document</label>
                                        <input class="form-control photo" id="document-file"
                                            accept="image/png, image/jpeg, application/pdf" name="document_file16"
                                            type="file">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Document 5 Expiry Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input class="form-control dateField" readonly required id="expiry_date16"
                                                placeholder="Document Expiry" name="expiry_date16" type="text"
                                                value="{{ $editModeData->expiry_date16 ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    @if ($editModeData->document_name16)
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <div class="input-group">
                                                <a href="{{ asset('/uploads/employeeDocuments/') }}/{{ $editModeData->document_name16 }}"
                                                    download>
                                                    <p class="text-info" style="padding-top: 8px; ">Download
                                                        File</p>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <p class="text-info" style="padding-top: 8px; ">No Document</p>
                                        </div>
                                    @endif

                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Document 6 Title</label>
                                        <input type="text" class="form-control" name="document_title17"
                                            value="{{ $editModeData->document_title17 ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Upload Document</label>
                                        <input class="form-control photo" id="document-file"
                                            accept="image/png, image/jpeg, application/pdf" name="document_file17"
                                            type="file">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Document 6 Expiry Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input class="form-control dateField" readonly required id="expiry_date17"
                                                placeholder="Document Expiry" name="expiry_date17" type="text"
                                                value="{{ $editModeData->expiry_date17 ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    @if ($editModeData->document_name17)
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <div class="input-group">
                                                <a href="{{ asset('/uploads/employeeDocuments/') }}/{{ $editModeData->document_name17 }}"
                                                    download>
                                                    <p class="text-info" style="padding-top: 8px; ">Download
                                                        File</p>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <p class="text-info" style="padding-top: 8px; ">No Document</p>
                                        </div>
                                    @endif

                                </div>





                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Document 7 Title</label>
                                        <input type="text" class="form-control" name="document_title18"
                                            value="{{ $editModeData->document_title18 ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Upload Document</label>
                                        <input class="form-control photo" id="document-file"
                                            accept="image/png, image/jpeg, application/pdf" name="document_file18"
                                            type="file">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Document 7 Expiry Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input class="form-control dateField" readonly required id="expiry_date18"
                                                placeholder="Document Expiry" name="expiry_date18" type="text"
                                                value="{{ $editModeData->expiry_date18 ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    @if ($editModeData->document_name18)
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <div class="input-group">
                                                <a href="{{ asset('/uploads/employeeDocuments/') }}/{{ $editModeData->document_name18 }}"
                                                    download>
                                                    <p class="text-info" style="padding-top: 8px; ">Download
                                                        File</p>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <p class="text-info" style="padding-top: 8px; ">No Document</p>
                                        </div>
                                    @endif

                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Document 8 Title</label>
                                        <input type="text" class="form-control" name="document_title19"
                                            value="{{ $editModeData->document_title19 ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Upload Document</label>
                                        <input class="form-control photo" id="document-file"
                                            accept="image/png, image/jpeg, application/pdf" name="document_file19"
                                            type="file">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Document 8 Expiry Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input class="form-control dateField" readonly required id="expiry_date19"
                                                placeholder="Document Expiry" name="expiry_date19" type="text"
                                                value="{{ $editModeData->expiry_date19 ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    @if ($editModeData->document_name19)
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <div class="input-group">
                                                <a href="{{ asset('/uploads/employeeDocuments/') }}/{{ $editModeData->document_name19 }}"
                                                    download>
                                                    <p class="text-info" style="padding-top: 8px; ">Download
                                                        File</p>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <p class="text-info" style="padding-top: 8px; ">No Document</p>
                                        </div>
                                    @endif

                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Document 9 Title</label>
                                        <input type="text" class="form-control" name="document_title20"
                                            value="{{ $editModeData->document_title20 ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Upload Document</label>
                                        <input class="form-control photo" id="document-file"
                                            accept="image/png, image/jpeg, application/pdf" name="document_file20"
                                            type="file">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Document 9 Expiry Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input class="form-control dateField" readonly required id="expiry_date20"
                                                placeholder="Document Expiry" name="expiry_date20" type="text"
                                                value="{{ $editModeData->expiry_date20 ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    @if ($editModeData->document_name20)
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <div class="input-group">
                                                <a href="{{ asset('/uploads/employeeDocuments/') }}/{{ $editModeData->document_name20 }}"
                                                    download>
                                                    <p class="text-info" style="padding-top: 8px; ">Download
                                                        File</p>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <p class="text-info" style="padding-top: 8px; ">No Document</p>
                                        </div>
                                    @endif

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Document 10 Title</label>
                                        <input type="text" class="form-control" name="document_title21"
                                            value="{{ $editModeData->document_title21 ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Upload Document</label>
                                        <input class="form-control photo" id="document-file"
                                            accept="image/png, image/jpeg, application/pdf" name="document_file21"
                                            type="file">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInput">Document 10 Expiry Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input class="form-control dateField" readonly required id="expiry_date21"
                                                placeholder="Document Expiry" name="expiry_date21" type="text"
                                                value="{{ $editModeData->expiry_date21 ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    @if ($editModeData->document_name21)
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <div class="input-group">
                                                <a href="{{ asset('/uploads/employeeDocuments/') }}/{{ $editModeData->document_name21 }}"
                                                    download>
                                                    <p class="text-info" style="padding-top: 8px; ">Download
                                                        File</p>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInput">Download Document</label>
                                            <p class="text-info" style="padding-top: 8px; ">No Document</p>
                                        </div>
                                    @endif

                                </div>

                            </div>

                            <div class="row" hidden>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInput">Document Title</label>
                                        <input type="text" class="form-control" name="document_title"
                                            value="{{ $editModeData->document_title }}">
                                    </div>
                                </div>
                                <div class="col-md-4" hidden>
                                    <div class="form-group">
                                        <label for="exampleInput">Upload Document</label>
                                        <input type="hidden" name="document_oldfile"
                                            value="{{ $editModeData->document_name }}">
                                        <input class="form-control photo" id="document-file"
                                            accept="image/png, image/jpeg, application/pdf" name="document_file"
                                            type="file" value="0">
                                    </div>
                                </div>
                                <div class="col-md-4" hidden>
                                    <div class="form-group">
                                        <label for="exampleInput">Expiry Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input class="form-control dateField" readonly required
                                                id="document_expiry" placeholder="Document Expiry"
                                                name="document_expiry" type="text"
                                                value="{{ $editModeData->document_expiry }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" hidden>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInput">Document Title</label>
                                        <input type="text" class="form-control" name="document_title2"
                                            value="{{ $editModeData->document_title2 }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInput">Upload Document</label>
                                        <input type="hidden" name="document_oldfile2"
                                            value="{{ $editModeData->document_name2 }}">
                                        <input class="form-control photo" id="document-file2"
                                            accept="image/png, image/jpeg, application/pdf" name="document_file2"
                                            type="file" value="0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInput">Expiry Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input class="form-control dateField" readonly required
                                                id="document_expiry2" placeholder="Document Expiry"
                                                name="document_expiry2" type="text"
                                                value="{{ $editModeData->document_expiry2 }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" hidden>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInput">Document Title</label>
                                        <input type="text" class="form-control" name="document_title3"
                                            value="{{ $editModeData->document_title3 }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInput">Upload Document</label>
                                        <input type="hidden" name="document_oldfile3"
                                            value="{{ $editModeData->document_name3 }}">
                                        <input class="form-control photo" id="document-file3"
                                            accept="image/png, image/jpeg, application/pdf" name="document_file3"
                                            type="file" value="0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInput">Expiry Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input class="form-control dateField" readonly required
                                                id="document_expiry3" placeholder="Document Expiry"
                                                name="document_expiry3" type="text"
                                                value="{{ $editModeData->document_expiry3 }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" hidden>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInput">Document Title</label>
                                        <input type="text" class="form-control" name="document_title4"
                                            value="{{ $editModeData->document_title4 }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInput">Upload Document</label>
                                        <input type="hidden" name="document_oldfile4"
                                            value="{{ $editModeData->document_name4 }}">
                                        <input class="form-control photo" id="document-file4"
                                            accept="image/png, image/jpeg, application/pdf" name="document_file4"
                                            type="file" value="0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInput">Expiry Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input class="form-control dateField" readonly required
                                                id="document_expiry4" placeholder="Document Expiry"
                                                name="document_expiry4" type="text"
                                                value="{{ $editModeData->document_expiry4 }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" hidden>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInput">Document Title</label>
                                        <input type="text" class="form-control" name="document_title5"
                                            value="{{ $editModeData->document_title5 }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInput">Upload Document</label>
                                        <input type="hidden" name="document_oldfile5"
                                            value="{{ $editModeData->document_name5 }}">
                                        <input class="form-control photo" id="document-file5"
                                            accept="image/png, image/jpeg, application/pdf" name="document_file5"
                                            type="file" value="0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInput">Expiry Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input class="form-control dateField" readonly required
                                                id="document_expiry5" placeholder="Document Expiry"
                                                name="document_expiry5" type="text"
                                                value="{{ $editModeData->document_expiry5 }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <br hidden>

                        <h3 class="box-title" hidden>@lang('employee.educational_qualification')</h3>

                        <hr hidden>

                        <div class="education_qualification_append_div" hidden>

                            @if (isset($editModeData) && count($educationQualificationEditModeData) > 0)
                                @foreach ($educationQualificationEditModeData as $educationQualificationValue)
                                    <div class="education_qualification_row_element">

                                        <input class="educationQualification_cid" id="educationQualification_cid"
                                            name="educationQualification_cid[]" type="hidden"
                                            value="{{ $educationQualificationValue->employee_education_qualification_id }}">

                                        <div class="row">

                                            <div class="col-md-3">

                                                <div class="form-group">

                                                    <label for="exampleInput">@lang('employee.institute')<span
                                                            class="validateRq">*</span></label>

                                                    <select name="institute[]" class="form-control institute">

                                                        <option value="">--- @lang('common.please_select') ---</option>

                                                        <option value="Board"
                                                            @if ($educationQualificationValue->institute == 'Board') {{ 'selected' }} @endif>
                                                            @lang('employee.board')</option>

                                                        <option value="University"
                                                            @if ($educationQualificationValue->institute == 'University') {{ 'selected' }} @endif>
                                                            @lang('employee.university')</option>

                                                    </select>

                                                </div>

                                            </div>

                                            <div class="col-md-3">

                                                <div class="form-group">

                                                    <label for="exampleInput">@lang('employee.board') /
                                                        @lang('employee.university')<span class="validateRq">*</span></label>

                                                    <input type="text" name="board_university[]"
                                                        class="form-control board_university" id="board_university"
                                                        placeholder="@lang('employee.board') / @lang('employee.university')"
                                                        value="{{ $educationQualificationValue->board_university }}">

                                                </div>

                                            </div>

                                            <div class="col-md-3">

                                                <div class="form-group">

                                                    <label for="exampleInput">@lang('employee.degree')<span
                                                            class="validateRq">*</span></label>

                                                    <input type="text" name="degree[]"
                                                        class="form-control degree required" id="degree"
                                                        placeholder="Example: B.Sc. Engr.(Bachelor of Science in Engineering)"
                                                        value="{{ $educationQualificationValue->degree }}">

                                                </div>

                                            </div>

                                            <div class="col-md-3">

                                                <label for="exampleInput">@lang('employee.passing_year')<span
                                                        class="validateRq">*</span></label>

                                                <div class="input-group">

                                                    <span class="input-group-addon"><i
                                                            class="fa fa-calendar-o"></i></span>

                                                    <input type="text" name="passing_year[]"
                                                        class="form-control yearPicker required" id="passing_year"
                                                        placeholder="@lang('employee.passing_year')"
                                                        value="{{ $educationQualificationValue->passing_year }}">

                                                </div>

                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-md-3">

                                                <div class="form-group">

                                                    <label for="exampleInput">@lang('employee.result')</label>

                                                    <select name="result[]" class="form-control result">

                                                        <option value="">--- @lang('common.please_select') ---</option>

                                                        <option value="First class"
                                                            @if ($educationQualificationValue->result == 'First class') {{ 'selected' }} @endif>
                                                            First class</option>

                                                        <option value="Second class"
                                                            @if ($educationQualificationValue->result == 'Second class') {{ 'selected' }} @endif>
                                                            Second class</option>

                                                        <option value="Third class"
                                                            @if ($educationQualificationValue->result == 'Third class') {{ 'selected' }} @endif>
                                                            Third class</option>

                                                    </select>

                                                </div>

                                            </div>

                                            <div class="col-md-3">

                                                <div class="form-group">

                                                    <label for="exampleInput">@lang('employee.gpa') /
                                                        @lang('employee.cgpa')</label>

                                                    <input type="text" name="cgpa[]" class="form-control cgpa"
                                                        id="cgpa" placeholder="Example: 5.00,4.63"
                                                        value="{{ $educationQualificationValue->cgpa }}">

                                                </div>

                                            </div>

                                            <div class="col-md-3"></div>

                                            <div class="col-md-3">

                                                <div class="form-group">

                                                    <input type="button"
                                                        class="form-control btn btn-danger deleteEducationQualification appendBtnColor"
                                                        style="margin-top: 17px" value="@lang('common.delete')">

                                                </div>

                                            </div>

                                        </div>

                                        <hr hidden>

                                    </div>
                                @endforeach
                            @endif

                        </div>

                        <div class="row" hidden>

                            <div class="col-md-9"></div>

                            <div class="col-md-3">
                                <div class="form-group"><input id="addEducationQualification" type="button"
                                        class="form-control btn btn-success appendBtnColor"
                                        value="@lang('employee.add_educational_qualification')"></div>
                            </div>

                        </div>

                        <h3 class="box-title" hidden>@lang('employee.professional_experience')</h3>

                        <hr hidden>

                        <div class="experience_append_div" hidden>

                            @if (isset($editModeData) && count($experienceEditModeData) > 0)
                                @foreach ($experienceEditModeData as $experienceValue)
                                    <div class="experience_row_element">

                                        <input class="employee_experience_id" id="employee_experience_id"
                                            name="employeeExperience_cid[]" type="hidden"
                                            value="{{ $experienceValue->employee_experience_id }}">

                                        <div class="row">

                                            <div class="col-md-3">

                                                <div class="form-group">

                                                    <label for="exampleInput">@lang('employee.organization_name')<span
                                                            class="validateRq">*</span></label>

                                                    <input type="text" name="organization_name[]"
                                                        class="form-control organization_name"
                                                        id="organization_name" placeholder="@lang('employee.organization_name')"
                                                        value="{{ $experienceValue->organization_name }}">

                                                </div>

                                            </div>

                                            <div class="col-md-3">

                                                <div class="form-group">

                                                    <label for="exampleInput">@lang('employee.designation')<span
                                                            class="validateRq">*</span></label>

                                                    <input type="text" name="designation[]"
                                                        class="form-control designation" id="designation"
                                                        placeholder="@lang('employee.designation')"
                                                        value="{{ dateConvertDBtoForm($experienceValue->designation) }}">

                                                </div>

                                            </div>

                                            <div class="col-md-3">

                                                <label for="exampleInput">@lang('common.from_date')<span
                                                        class="validateRq">*</span></label>

                                                <div class="input-group">

                                                    <span class="input-group-addon"><i
                                                            class="fa fa-calendar"></i></span>

                                                    <input type="text" name="from_date[]"
                                                        class="form-control dateField" id="from_date"
                                                        placeholder="@lang('common.from_date')"
                                                        value="{{ dateConvertDBtoForm($experienceValue->from_date) }}">

                                                </div>

                                            </div>

                                            <div class="col-md-3">

                                                <label for="exampleInput">@lang('common.to_date')<span
                                                        class="validateRq">*</span></label>

                                                <div class="input-group">

                                                    <span class="input-group-addon"><i
                                                            class="fa fa-calendar"></i></span>

                                                    <input type="text" name="to_date[]"
                                                        class="form-control dateField" id="to_date"
                                                        placeholder="@lang('common.to_date')"
                                                        value="{{ dateConvertDBtoForm($experienceValue->to_date) }}">

                                                </div>

                                            </div>

                                        </div>



                                        <div class="row">

                                            <div class="col-md-3">

                                                <div class="form-group">

                                                    <label for="exampleInput">@lang('employee.responsibility')<span
                                                            class="validateRq">*</span></label>

                                                    <textarea name="responsibility[]" class="form-control responsibility" placeholder="@lang('employee.responsibility')"
                                                        cols="30" rows="2" required>{{ $experienceValue->responsibility }}</textarea>

                                                </div>

                                            </div>

                                            <div class="col-md-3">

                                                <div class="form-group">

                                                    <label for="exampleInput">@lang('employee.skill')<span
                                                            class="validateRq">*</span></label>

                                                    <textarea name="skill[]" class="form-control skill" placeholder="@lang('employee.skill')" cols="30"
                                                        rows="2">{{ $experienceValue->skill }}</textarea>

                                                </div>

                                            </div>

                                            <div class="col-md-3"></div>

                                            <div class="col-md-3">

                                                <div class="form-group">

                                                    <input type="button"
                                                        class="form-control btn btn-danger deleteExperience appendBtnColor"
                                                        style="margin-top: 17px" value="@lang('common.delete')">

                                                </div>

                                            </div>

                                        </div>

                                        <hr hidden>

                                    </div>
                                @endforeach
                            @endif

                        </div>

                        <div class="row" hidden>

                            <div class="col-md-9"></div>

                            <div class="col-md-3">
                                <div class="form-group"><input id="addExperience" type="button"
                                        class="form-control btn btn-success appendBtnColor"
                                        value="@lang('employee.add_professional_experience')">
                                </div>
                            </div>

                        </div>


                        <br hidden><br hidden>
                        <div class="form-actions">

                            <div class="row">

                                <div class="col-md-12">

                                    <button type="submit" class="btn btn-info btn_style"><i
                                            class="fa fa-pencil"></i>
                                        @lang('common.update')</button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{ Form::close() }}

            </div>

        </div>

    </div>

</div>

</div>

</div>



<div class="row_element1" style="display: none;">

    <input name="educationQualification_cid[]" type="hidden">

    <div class="row">

        <div class="col-md-3">

            <div class="form-group">

                <label for="exampleInput">@lang('employee.institute')<span class="validateRq">*</span></label>

                <select name="institute[]" class="form-control institute">

                    <option value="">--- @lang('common.please_select') ---</option>

                    <option value="Board">@lang('employee.board')</option>

                    <option value="University">@lang('employee.university')</option>

                </select>

            </div>

        </div>

        <div class="col-md-3">

            <div class="form-group">

                <label for="exampleInput">@lang('employee.board') / @lang('employee.university')<span
                        class="validateRq">*</span></label>

                <input type="text" name="board_university[]" class="form-control board_university"
                    id="board_university" placeholder="@lang('employee.board') / @lang('employee.university')">

            </div>

        </div>

        <div class="col-md-3">

            <div class="form-group">

                <label for="exampleInput">@lang('employee.degree')<span class="validateRq">*</span></label>

                <input type="text" name="degree[]" class="form-control degree required" id="degree"
                    placeholder="Example: B.Sc. Engr.(Bachelor of Science in Engineering)">

            </div>

        </div>

        <div class="col-md-3">

            <label for="exampleInput">@lang('employee.passing_year')<span class="validateRq">*</span></label>

            <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>

                <input type="text" name="passing_year[]" class="form-control yearPicker required"
                    id="passing_year" placeholder="@lang('employee.passing_year')">

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-md-3">

            <div class="form-group">

                <label for="exampleInput">@lang('employee.result')</label>

                <select name="result[]" class="form-control result">

                    <option value="">--- @lang('common.please_select') ---</option>

                    <option value="First class">First class</option>

                    <option value="Second class">Second class</option>

                    <option value="Third class">Third class</option>

                </select>

            </div>

        </div>

        <div class="col-md-3">

            <div class="form-group">

                <label for="exampleInput">@lang('employee.gpa') / @lang('employee.cgpa')</label>

                <input type="text" name="cgpa[]" class="form-control cgpa" id="cgpa"
                    placeholder="Example: 5.00,4.63">

            </div>

        </div>

        <div class="col-md-3"></div>

        <div class="col-md-3">

            <div class="form-group">

                <input type="button"
                    class="form-control btn btn-danger deleteEducationQualification appendBtnColor"
                    style="margin-top: 17px" value="@lang('common.delete')">

            </div>

        </div>

    </div>

    <hr>
</div>



<div class="row_element2" style="display: none;">

    <input name="employeeExperience_cid[]" type="hidden">

    <div class="row">

        <div class="col-md-3">

            <div class="form-group">

                <label for="exampleInput">@lang('employee.organization_name')<span class="validateRq">*</span></label>

                <input type="text" name="organization_name[]" class="form-control organization_name"
                    id="organization_name" placeholder="@lang('employee.organization_name')">

            </div>

        </div>

        <div class="col-md-3">

            <div class="form-group">

                <label for="exampleInput">@lang('employee.designation')<span class="validateRq">*</span></label>

                <input type="text" name="designation[]" class="form-control designation" id="designation"
                    placeholder="@lang('employee.designation')">

            </div>

        </div>

        <div class="col-md-3">

            <label for="exampleInput">@lang('common.from_date')<span class="validateRq">*</span></label>

            <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>

                <input type="text" name="from_date[]" class="form-control dateField" id="from_date"
                    placeholder="@lang('common.from_date')">

            </div>

        </div>

        <div class="col-md-3">

            <label for="exampleInput">@lang('common.to_date')<span class="validateRq">*</span></label>

            <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>

                <input type="text" name="to_date[]" class="form-control dateField" id="to_date"
                    placeholder="@lang('common.to_date')">

            </div>

        </div>

    </div>



    <div class="row">

        <div class="col-md-3">

            <div class="form-group">

                <label for="exampleInput">@lang('employee.responsibility')<span class="validateRq">*</span></label>

                <textarea name="responsibility[]" class="form-control responsibility" placeholder="@lang('employee.responsibility')"
                    cols="30" rows="2"></textarea>

            </div>

        </div>

        <div class="col-md-3">

            <div class="form-group">

                <label for="exampleInput">@lang('employee.skill')<span class="validateRq">*</span></label>

                <textarea name="skill[]" class="form-control skill" placeholder="@lang('employee.skill')" cols="30"
                    rows="2"></textarea>

            </div>

        </div>

        <div class="col-md-3"></div>

        <div class="col-md-3">

            <div class="form-group">

                <input type="button" class="form-control btn btn-danger deleteExperience appendBtnColor"
                    style="margin-top: 17px" value="@lang('common.delete')">

            </div>

        </div>

    </div>

    <hr>


</div>



@endsection

@section('page_scripts')
<script>
    $(document).ready(function() {



        $('#addEducationQualification').click(function() {

            $('.education_qualification_append_div').append(
                '<div class="education_qualification_row_element">' + $('.row_element1').html() +
                '</div>');

        });



        $('#addExperience').click(function() {

            $('.experience_append_div').append('<div class="experience_row_element">' + $(
                '.row_element2').html() + '</div>');

        });



        $(document).on("click", ".deleteEducationQualification", function() {

            $(this).parents('.education_qualification_row_element').remove();

            var deletedID = $(this).parents('.education_qualification_row_element').find(
                '.educationQualification_cid').val();

            if (deletedID) {

                var prevDelId = $('#delete_education_qualifications_cid').val();

                if (prevDelId) {

                    $('#delete_education_qualifications_cid').val(prevDelId + ',' + deletedID);

                } else {

                    $('#delete_education_qualifications_cid').val(deletedID);

                }

            }

        });



        $(document).on("click", ".deleteExperience", function() {

            $(this).parents('.experience_row_element').remove();

            var deletedID = $(this).parents('.experience_row_element').find('.employee_experience_id')
                .val();

            if (deletedID) {

                var prevDelId = $('#delete_experiences_cid').val();

                if (prevDelId) {

                    $('#delete_experiences_cid').val(prevDelId + ',' + deletedID);

                } else {

                    $('#delete_experiences_cid').val(deletedID);

                }

            }

        });



        $(document).on('change', '.pay_grade_id', function() {

            var data = $('.pay_grade_id').val();

            if (data) {

                $('.hourly_pay_grade_id').val('');

                $('.pay_grade_id').attr('required', false);

                $('.hourly_pay_grade_id').attr('required', false);

            } else {

                $('.pay_grade_id').attr('required', true);

                $('.hourly_pay_grade_id').attr('required', true);

            }

        });



        $(document).on('change', '.hourly_pay_grade_id', function() {

            var data = $('.hourly_pay_grade_id').val();

            if (data) {

                $('.pay_grade_id').val('');

                $('.pay_grade_id').attr('required', false);

                $('.hourly_pay_grade_id').attr('required', false);

            } else {

                $('.pay_grade_id').attr('required', true);

                $('.hourly_pay_grade_id').attr('required', true);

            }

        });



    });
</script>
@endsection
