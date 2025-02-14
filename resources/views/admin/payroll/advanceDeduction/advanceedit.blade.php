@extends('admin.master')
@section('content')
@section('title')
    @lang('advancededuction.edit_advancededuction')
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
            <a href="{{ route('advanceDeduction.advance') }}"
                class="btn btn-success pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"><i
                    class="fa fa-list-ul" aria-hidden="true"></i> View Advance</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading"><i class="mdi mdi-clipboard-text fa-fw"></i>@yield('title')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {{ Form::model($editModeData, ['route' => ['advance.update', $editModeData->advance_deduction_transaction_id], 'method' => 'PUT', 'files' => 'true', 'class' => 'form-horizontal', 'id' => 'advanceDeductionForm']) }}

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
                                <div class="col-md-10">
                                    <label class="control-label col-sm-4" for="date">@lang('common.date') : <span
                                            class="validateRq">*</span></label>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" class="form-control col-md-4 dateField" required
                                                {{ isset($editModeData) ? 'disabled' : '' }} readonly
                                                placeholder="@lang('common.date_field')" id="date_of_advance_given"
                                                name="date_of_advance_given"
                                                value="@if (isset($editModeData->date_of_advance_given)) {{ dateConvertFormtoDB($editModeData->date_of_advance_given) }} @else {{ dateConvertDBToForm(date('Y-m-d')) }} @endif">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="control-label col-md-4" for="number">@lang('common.fullname')
                                        : <span class="validateRq">*</span></label>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <div>
                                                <select class="form-control employee_id select2 required" required
                                                    {{ isset($editModeData) ? 'disabled' : '' }} name="employee_id">
                                                    <option value="">----
                                                        @lang('common.please_select') ----</option>
                                                    @foreach ($results as $value)
                                                        @foreach ($value as $v)
                                                            <option value="{{ $v['employee_id'] }}"
                                                                @if (isset($editModeData) && $v['employee_id'] == $editModeData->employee_id) {{ 'selected' }} @else {{ $v['employee_id'] }} @endif>
                                                                {{ $v->displayNameWithCode() }}
                                                            </option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="control-label col-sm-4" for="date">Advance Name : <span
                                            class="validateRq">*</span></label>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            {!! Form::text(
                                                'advance_name',
                                                $advance->advancededuction_name,
                                                $attributes = [
                                                    'class' => 'form-control required advance_name',
                                                    'id' => 'advance_name',
                                                    'placeholder' => __('advancededuction_name'),
                                                    'readonly' => isset($editModeData) ? 'readonly' : '',
                                                    'step' => 'any',
                                                ],
                                            ) !!}
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">@lang('advancededuction.advance_amount')
                                                : <span class="validateRq">*</span></label>
                                            <div class="col-md-8">
                                                {!! Form::number(
                                                    'advance_amount',
                                                    $advance->advance_amount,
                                                    $attributes = [
                                                        'class' => 'form-control required advance_amount',
                                                        'id' => 'advance_amount',
                                                        'placeholder' => __('advancededuction.advance_amount'),
                                                        'readonly' => isset($editModeData) ? 'readonly' : '',
                                                        'step' => 'any',
                                                    ],
                                                ) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Amount
                                                : <span class="validateRq">*</span></label>
                                            <div class="col-md-8">
                                                {!! Form::number(
                                                    'deduction_amouth_per_month',
                                                
                                                    $editModeData->cash_received,
                                                    $attributes = [
                                                        'class' => 'form-control required deduction_amouth_per_month',
                                                        'id' => 'deduction_amouth_per_month',
                                                        'placeholder' => __('advancededuction.deduction_amouth_per_month'),
                                                        'min' => '0',
                                                        'step' => 'any',
                                                        // 'readonly' => 'readonly',
                                                    ],
                                                ) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-offset-4 col-md-8">

                                                <button type="submit" class="btn btn-info btn_style"><i
                                                        class="fa fa-pencil"></i> @lang('common.update')</button>

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
@endsection
@section('page_scripts')
@endsection
