@extends('admin.master')
@section('content')
@section('title')
    @lang('leave.permission_list')
@endsection
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
            <ol class="breadcrumb">
                <li class="active breadcrumbColor"><a href="#"><i class="fa fa-home"></i> @lang('dashboard.dashboard')</a></li>
                <li>@yield('title')</li>
            </ol>
        </div>
        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
            <a href="{{ route('applyForPermission.create') }}"
                class="btn btn-success pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i
                    class="fa fa-plus-circle" aria-hidden="true"></i> @lang('leave.apply_for_permission')</a>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading"><i class="mdi mdi-table fa-fw"></i> @yield('title')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <i
                                    class="cr-icon glyphicon glyphicon-ok"></i>&nbsp;<strong>{{ session()->get('success') }}</strong>
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <i
                                    class="glyphicon glyphicon-remove"></i>&nbsp;<strong>{{ session()->get('error') }}</strong>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover manage-u-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('common.employee_name')</th>
                                        <th>Permission Duration</th>
                                        <th>@lang('leave.date')</th>
                                        <th>From Time</th>
                                        <th>To Time</th>
                                        <th>@lang('leave.approved_permission_count')</th>
                                        <th>@lang('leave.remarks')</th>
                                        <th>@lang('common.headdepartmentstatus')</th>
                                        <th>@lang('common.managerstatus')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {!! $sl = null !!}
                                    @foreach ($results as $value)
                                        <tr>
                                            <td style="width: 100px;">{!! ++$sl !!}</td>
                                            <td>
                                                @if (isset($value->employee->first_name))
                                                    {!! $value->employee->first_name !!}
                                                @endif
                                                @if (isset($value->employee->last_name))
                                                    {!! $value->employee->last_name !!}
                                                @endif
                                            </td>

                                            <td>{!! $value->permission_duration !!}</td>

                                            <td> {!! date('d-m-Y', strtotime($value->leave_permission_date)) !!} </td>
                                            <td>
                                                @if (isset($value->from_time))
                                                    {!! $value->from_time !!}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($value->to_time))
                                                    {!! $value->to_time !!}
                                                @endif
                                            </td>

                                            <td>
                                                @php
                                                    $permission_data = App\Model\LeavePermission::where(
                                                        'employee_id',
                                                        $value->employee_id,
                                                    )
                                                        ->whereMonth(
                                                            'leave_permission_date',
                                                            date('m', strtotime($value->leave_permission_date)),
                                                        )
                                                        ->whereYear(
                                                            'leave_permission_date',
                                                            date('Y', strtotime($value->leave_permission_date)),
                                                        )
                                                        ->where(
                                                            'leave_permission_date',
                                                            '<=',
                                                            date('Y-m-d', strtotime($value->leave_permission_date)),
                                                        )
                                                        ->where('status', 2)
                                                        ->count();
                                                    $permission_count = 0;
                                                    if ($permission_data) {
                                                        $permission_count = $permission_data;
                                                    } else {
                                                        $permission_count = 0;
                                                    }
                                                @endphp

                                                {!! $permission_count !!}


                                            </td>
                                            <td>
                                                <span class="text-muted">Manager Remarks :
                                                    @if (isset($value->manager_remarks))
                                                        {!! $value->manager_remarks !!}
                                                    @else
                                                        {{ '-' }}
                                                    @endif
                                                </span>
                                                <br />
                                                <span class="text-muted">HR Remarks :
                                                    @if (isset($value->remarks))
                                                        {!! $value->remarks !!}
                                                    @else
                                                        {{ '-' }}
                                                    @endif
                                                </span>
                                            </td>


                                            <td style="width: 100px;">
                                                @if ($value->status == 1)
                                                    <span class="label label-warning">@lang('common.pending')</span>
                                                @elseif ($value->status == 2)
                                                    <span class="label label-success">@lang('common.approved')</span>
                                                @else
                                                    <span class="label label-danger">@lang('common.rejected')</span>
                                                @endif
                                            </td>

                                            <td style="width: 100px;">
                                                @if ($value->manager_status == 1)
                                                    <span class="label label-warning">@lang('common.pending')</span>
                                                @elseif ($value->manager_status == 2)
                                                    <span class="label label-success">@lang('common.approved')</span>
                                                @else
                                                    <span class="label label-danger">@lang('common.rejected')</span>
                                                @endif
                                            </td>




                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-center">
                                {{ $results->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
