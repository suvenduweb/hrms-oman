@extends('admin.master')
@section('content')
@section('title')
@lang('leave.requested_application')
@endsection

<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
            <ol class="breadcrumb">
                <li class="active breadcrumbColor"><a href="#"><i class="fa fa-home"></i> @lang('dashboard.dashboard')</a></li>
                <li>@yield('title')</li>
            </ol>
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
                            <i class="cr-icon glyphicon glyphicon-ok"></i>&nbsp;<strong>{{ session()->get('success') }}</strong>
                        </div>
                        @endif
                        @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="glyphicon glyphicon-remove"></i>&nbsp;<strong>{{ session()->get('error') }}</strong>
                        </div>
                        @endif

                        @if (count($adminresults) > 0)
                        <div class="table-responsive">
                            <table id="myTable" class="table table-hover table-bordered manage-u-table">
                                <thead class="tr_header">
                                    <tr>
                                        <th>@lang('common.serial')</th>
                                        <th>@lang('common.employee_name')</th>
                                        <th>@lang('leave.leave_type')</th>
                                        <th>@lang('leave.request_duration')</th>
                                        <th>@lang('leave.request_date')</th>
                                        <th>@lang('leave.number_of_day')</th>
                                        <th style="width: 200px;">@lang('leave.purpose')</th>
                                        <th>@lang('leave.document')</th>
                                        <th>Manager Status</th>
                                        <th>HR Status</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {!! $sl = null !!}
                                    @foreach ($adminresults as $value)
                                    <tr>
                                        <td style="width: 50px;">{!! ++$sl !!}</td>
                                        <td>
                                            @if (isset($value->employee->first_name))
                                            {!! $value->employee->first_name !!}
                                            @endif
                                            @if (isset($value->employee->last_name))
                                            {!! $value->employee->last_name !!}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($value->leaveType->leave_type_name))
                                            {!! $value->leaveType->leave_type_name !!}
                                            @endif
                                        </td>
                                        <td>{!! dateConvertDBtoForm($value->application_from_date) !!} <b>to</b> {!! dateConvertDBtoForm($value->application_to_date) !!}</td>
                                        <td>{!! dateConvertDBtoForm($value->application_date) !!}</td>
                                        <td>{!! $value->number_of_day !!}</td>
                                        <td>
                                            <p style="width: 200px;overflow:hidden;text-overflow:ellipsis">
                                                {!! $value->purpose !!}
                                            </p>
                                        </td>
                                        @if ($value->document)
                                        <td>
                                            <a class="btn btn-default btn-xs" href="{{ asset('/uploads/leave_document/' . $value->document) }}" download>
                                                <i class="fa fa-download" style="margin: 0 6px;"></i>
                                            </a>

                                            <a class="btn btn-default btn-xs" href="{{ url('viewLeaveApplication', $value->leave_application_id) }}" target="_blank">
                                                <i class="fa fa-eye" style="margin: 0 6px;">
                                                </i>
                                            </a>

                                        </td>
                                        @else
                                        <td> <span class="text-warning">No document</span></td>
                                        @endif
                                        @if ($value->manager_status == 1)
                                        <td style="width: 100px;">
                                            <span class="label label-warning">@lang('common.pending')</span>
                                        </td>
                                        @elseif($value->manager_status == 2)
                                        <td style="width: 100px;">
                                            <span class="label label-success">@lang('common.approved')</span>
                                        </td>
                                        @else
                                        <td style="width: 100px;">
                                            <span class="label label-danger">@lang('common.rejected')</span>
                                        </td>
                                        @endif
                                        @if ($value->status == 1)
                                        @if ($value->manager_status == 2)
                                        <td style="width: 100px;">
                                            <span class="label label-warning">@lang('common.pending')</span>
                                        </td>
                                        @elseif($value->manager_status == 3)
                                        <td style="width: 100px;">
                                            <span class="label label-danger">Manager Rejected</span>
                                        </td>
                                        @else
                                        <td style="width: 100px;">
                                            <span class="label label-warning">@lang('common.pending')</span>
                                        </td>
                                        @endif
                                        @elseif($value->status == 2)
                                        <td style="width: 100px;">
                                            <span class="label label-success">@lang('common.approved')</span>
                                        </td>
                                        @else
                                        <td style="width: 100px;">
                                            <span class="label label-danger">@lang('common.rejected')</span>
                                        </td>
                                        @endif


                                        <td style="width: 100px;">
                                            @if ($value->action && $value->manager_status == 1)
                                            <a href="{!! route('requestedApplication.viewManagerDetails', $value->leave_application_id) !!}" title="View leave details!" class="btn btn-info btn-sm btnColor">
                                                <i class="fa fa-arrow-circle-right"></i>
                                            </a>
                                            @elseif($value->action && $value->status == 1 && $value->manager_status == 2)
                                            <a href="{!! route('requestedApplication.viewDetails', $value->leave_application_id) !!}" title="View leave details!" class="btn btn-info btn-sm btnColor">
                                                <i class="fa fa-arrow-circle-right"></i>
                                            </a>
                                            @else
                                            #
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page_scripts')
<link href="{!! asset('admin_assets/plugins/bower_components/news-Ticker-Plugin/css/site.css') !!}" rel="stylesheet" type="text/css" />
<script src="{!! asset('admin_assets/plugins/bower_components/news-Ticker-Plugin/scripts/jquery.bootstrap.newsbox.min.js') !!}"></script>

<script type="text/javascript">
    $(document).on('click', '.remarksForLeave', function() {

        var actionTo = "{{ URL::to('approveOrRejectLeaveApplication') }}";
        var leave_application_id = $(this).attr('data-leave_application_id');
        var status = $(this).attr('data-status');
        if (status == 2) {
            var statusText = "Are you want to approve Leave application?";
            var btnColor = "#2cabe3";
        } else {
            var statusText = "Are you want to reject Leave application?";
            var btnColor = "red";
        }

        swal({
                title: "",
                text: statusText,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: btnColor,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            },
            function(isConfirm) {
                var token = '{{ csrf_token() }}';
                if (isConfirm) {
                    $.ajax({
                        type: 'POST',
                        url: actionTo,
                        data: {
                            leave_application_id: leave_application_id,
                            status: status,
                            _token: token
                        },
                        success: function(data) {
                            if (data == 'approve') {
                                swal({
                                        title: "Approved!",
                                        text: "Leave application approved.",
                                        type: "success"
                                    },
                                    function(isConfirm) {
                                        if (isConfirm) {
                                            $('.' + leave_application_id).fadeOut();
                                            location.reload();
                                        }
                                    });

                            } else {
                                swal({
                                        title: "Rejected!",
                                        text: "Leave application rejected.",
                                        type: "success"
                                    },
                                    function(isConfirm) {
                                        if (isConfirm) {
                                            $('.' + leave_application_id).fadeOut();
                                            location.reload();
                                        }
                                    });
                            }
                        }

                    });
                } else {
                    swal("Cancelled", "Your data is safe .", "error");
                }
            });
        return false;

    });
    $(document).on('click', '.managerRemarksForLeave', function() {

        var actionTo = "{{ URL::to('approveOrRejectManagerLeaveApplication') }}";
        var leave_application_id = $(this).attr('data-leave_application_id');
        var status = $(this).attr('data-status');
        if (status == 2) {
            var statusText = "Are you want to approve Leave application?";
            var btnColor = "#2cabe3";
        } else {
            var statusText = "Are you want to reject Leave application?";
            var btnColor = "red";
        }

        swal({
                title: "",
                text: statusText,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: btnColor,
                confirmButtonText: "Yes",
                closeOnConfirm: false
            },
            function(isConfirm) {
                var token = '{{ csrf_token() }}';
                if (isConfirm) {
                    $.ajax({
                        type: 'POST',
                        url: actionTo,
                        data: {
                            leave_application_id: leave_application_id,
                            status: status,
                            _token: token
                        },
                        success: function(data) {
                            if (data == 'approve') {
                                swal({
                                        title: "Approved!",
                                        text: "Leave application approved.",
                                        type: "success"
                                    },
                                    function(isConfirm) {
                                        if (isConfirm) {
                                            $('.' + leave_application_id).fadeOut();
                                            location.reload();
                                        }
                                    });

                            } else {
                                swal({
                                        title: "Rejected!",
                                        text: "Leave application rejected.",
                                        type: "success"
                                    },
                                    function(isConfirm) {
                                        if (isConfirm) {
                                            $('.' + leave_application_id).fadeOut();
                                            location.reload();
                                        }
                                    });
                            }
                        }

                    });
                } else {
                    swal("Cancelled", "Your data is safe .", "error");
                }
            });
        return false;

    });
</script>
@endsection