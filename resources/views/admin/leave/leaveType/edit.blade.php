@extends('admin.master')
@section('content')

@section('title')
    @if (isset($editModeData))
        @lang('leave.edit_leave_type');
    @else
        @lang('leave.add_leave_type');
    @endif
@endsection


<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <ol class="breadcrumb">
                <li class="active breadcrumbColor"><a href="{{ url('dashboard') }}"><i class="fa fa-home"></i>
                        @lang('dashboard.dashboard')</a></li>
                <li>@yield('title')</li>

            </ol>
        </div>
        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
            <a href="{{ route('leaveType.index') }}"
                class="btn btn-success pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"><i
                    class="fa fa-list-ul" aria-hidden="true"></i> @lang('leave.view_leave_type')</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading"><i class="mdi mdi-clipboard-text fa-fw"></i>@yield('title')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        @if (isset($editModeData))
                            {{ Form::model($editModeData, ['route' => ['leaveType.update', $editModeData->leave_type_id], 'method' => 'PUT', 'files' => 'true', 'id' => 'leaveTypeForm', 'class' => 'form-horizontal']) }}
                        @else
                            {{ Form::open(['route' => 'leaveType.store', 'enctype' => 'multipart/form-data', 'id' => 'leaveTypeForm', 'class' => 'form-horizontal']) }}
                        @endif

                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-offset-2 col-md-6">
                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close"><span aria-hidden="true">×</span></button>
                                            @foreach ($errors->all() as $error)
                                                <strong>{!! $error !!}</strong><br>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if (session()->has('success'))
                                        <div class="alert alert-success alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">×</button>
                                            <i
                                                class="cr-icon glyphicon glyphicon-ok"></i>&nbsp;<strong>{{ session()->get('success') }}</strong>
                                        </div>
                                    @endif
                                    @if (session()->has('error'))
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">×</button>
                                            <i
                                                class="glyphicon glyphicon-remove"></i>&nbsp;<strong>{{ session()->get('error') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">@lang('leave.leave_type_name')<span
                                                class="validateRq">*</span></label>
                                        <div class="col-md-8">
                                            {!! Form::text(
                                                'leave_type_name',
                                                old('leave_type_name'),
                                                $attributes = [
                                                    'class' => 'form-control required leave_type_name',
                                                    'id' => 'leave_type_name',
                                                    'placeholder' => __('leave.leave_type_name'),
                                                   
                                                ],
                                            ) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">@lang('leave.number_of_day')<span
                                                class="validateRq">*</span></label>
                                        <div class="col-md-8">
                                            {!! Form::text(
                                                'num_of_day',
                                                old('num_of_day'),
                                                $attributes = [
                                                    'class' => 'form-control required num_of_day',
                                                    'id' => 'num_of_day',
                                                    'placeholder' => __('leave.number_of_day'),
                                                ],
                                            ) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">@lang('leave.nationality')<span
                                                class="validateRq">*</span></label>
                                        <div class="col-md-8">
                                            {{ Form::select('nationality', $nationality, old('nationality'), ['class' => 'form-control nationality select2 required']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">@lang('leave.religion')<span
                                                class="validateRq">*</span></label>
                                        <div class="col-md-8">
                                            {{ Form::select('religion', $religion, old('religion'), ['class' => 'form-control religion select2 required']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">@lang('employee.gender')<span
                                                class="validateRq">*</span></label>
                                        <div class="col-md-8">
                                            {{ Form::select('gender', $gender, old('gender'), ['class' => 'form-control gender select2 required']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Status<span
                                                class="validateRq">*</span></label>
                                        <div class="col-md-8">
                                            <select name="status" class="form-control status">

                                                <option value="">--- @lang('common.please_select') ---</option>
                        
                                                <option value="1" {{$editModeData->status == 1 ?'selected':''}}>Active</option>
                        
                                                <option value="0" {{$editModeData->status == 0 ?'selected':''}}>In Active</option>
                        
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            


                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="col-md-offset-4 col-md-8">
                                        @if (isset($editModeData))
                                            <button type="submit" class="btn btn-info btn_style"><i
                                                    class="fa fa-pencil"></i> @lang('common.update')</button>
                                        @else
                                            <button type="submit" class="btn btn-info btn_style"><i
                                                    class="fa fa-check"></i> @lang('common.save')</button>
                                        @endif
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
@endsection
