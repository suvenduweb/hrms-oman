<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api', 'prefix' => 'mobile'], function () {
    Route::post('login', 'Api\AuthController@login');
    Route::post('register', 'Api\AuthController@register');
    Route::post('logout', 'Api\AuthController@logout');
    Route::get('refresh', 'Api\AuthController@refresh');

    Route::group(['prefix' => 'attendance'], function () {
        Route::post('employee_attendance_list', 'Api\AttendanceController@apiattendancelist');
        Route::get('my_attendance_report', 'Api\AttendanceReportController@myAttendanceReport');
        Route::get('download_my_attendance', 'Api\AttendanceReportController@downloadMyAttendance');
    });

    Route::group(['prefix' => 'leave'], function () {
        Route::get('index', 'Api\ApplyForLeaveController@index');
        Route::get('create', 'Api\ApplyForLeaveController@create');
        Route::post('store', 'Api\ApplyForLeaveController@store');
        Route::get('dateChanged', 'Api\ApplyForLeaveController@dateChanged');
        Route::post('update', 'Api\ApplyForLeaveController@update');
    });
    Route::group(['prefix' => 'permission'], function () {
        Route::get('index', 'Api\ApplyForPermissionController@index');
        Route::get('create', 'Api\ApplyForPermissionController@create');
        Route::post('store', 'Api\ApplyForPermissionController@store');
    });

    Route::group(['prefix' => 'onduty'], function () {
        Route::get('index', 'Api\OnDutyController@index');
        Route::get('create', 'Api\OnDutyController@create');
        Route::post('store', 'Api\OnDutyController@store');
    });
    //  Route::group(['prefix' => 'payroll'], function () { 
    //     Route::get('payroll_list', 'Api\PayslipController@myPayslipList');
    //     Route::get('download_my_payslip', 'Api\PayslipController@downloadMyPayslip');
    // });

    // Route::group(['middleware' => ['jwt.verify'], 'prefix' => 'payroll'], function () {
    Route::group(['prefix' => 'payroll'], function () {

        Route::get('download_my_payslip', 'Api\PayslipController@downloadPayslip');
        Route::get('getOtp', 'Api\PayslipController@getOtp');
        Route::get('verifyOtp', 'Api\PayslipController@verifyOtp');
        Route::get('payroll_list', 'Api\PayslipController@PayrollList');
    });


    Route::post('change_password', 'Api\AuthController@changePassword');
    Route::post('forgetpassword', 'Api\AuthController@forgetPassword');
});
