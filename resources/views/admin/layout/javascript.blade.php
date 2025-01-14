<!-- App js -->
{{-- <script src="{!! asset('js/app.js') !!}"></script> --}}

<!-- Jquery Core JavaScript -->
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> --}}
<script src="{!! asset('admin_assets/js/jquery.min.js') !!}"></script>
{{-- <script src="{!! asset('admin_assets/plugins/bower_components/jquery/dist/jquery.min.js') !!}"></script> --}}

<!-- moment JavaScript -->
<script src="{!! asset('admin_assets/js/moment.js') !!}"></script>

<!-- datetimepicker JavaScript -->
<script src="{!! asset('admin_assets/js/bootstrap-datetimepicker.min.js') !!}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{!! asset('admin_assets/bootstrap/dist/js/bootstrap.min.js') !!}"></script>

<!-- Switch js -->
<script src="{!! asset('js/bootstrap-toggle.min.js') !!}"></script>

<!-- Menu Plugin JavaScript -->
<script src="{!! asset('admin_assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') !!}"></script>

<!--slimscroll JavaScript -->
<script src="{!! asset('admin_assets/js/jquery.slimscroll.js') !!}"></script>

<!--Wave Effects -->
<script src="{!! asset('admin_assets/js/waves.js') !!}"></script>

<!--Counter js -->
<script src="{!! asset('admin_assets/plugins/bower_components/waypoints/lib/jquery.waypoints.js') !!}"></script>

<!-- Sparkline chart JavaScript -->
<script src="{!! asset('admin_assets/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js') !!}"></script>

<!-- Custom Theme JavaScript -->
<script src="{!! asset('admin_assets/js/custom.js') !!}"></script>

<!--Jquery Toast  Script -->
<script src="{!! asset('admin_assets/plugins/bower_components/toast-master/js/jquery.toast.js') !!}"></script>

<!-- DataTable Script -->
<script src="{!! asset('admin_assets/latest/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('admin_assets/js/datatables/dataTables.buttons.min.js') !!}"></script>
<script src="{!! asset('admin_assets/js/datatables/dataTables.searchBuilder.min.js') !!}"></script>
<script src="{!! asset('admin_assets/js/datatables/dataTables.colReorder.min.js') !!}"></script>
<script src="{!! asset('admin_assets/js/datatables/dataTables.keyTable.min.js') !!}"></script>
<script src="{!! asset('admin_assets/js/datatables/moment.min.js') !!}"></script>
<script src="{!! asset('admin_assets/js/datatables/dataTables.dateTime.min.js') !!}"></script>
<script src="{!! asset('admin_assets/js/datatables/dataTables.select.min.js') !!}"></script>
<script src="{!! asset('admin_assets/js/datatables/jszip.min.js') !!}"></script>
<script src="{!! asset('admin_assets/js/datatables/pdfmake.min.js') !!}"></script>
<script src="{!! asset('admin_assets/js/datatables/vfs_fonts.js') !!}"></script>
<script src="{!! asset('admin_assets/js/datatables/buttons.html5.min.js') !!}"></script>
<script src="{!! asset('admin_assets/js/datatables/buttons.print.min.js') !!}"></script>

<!-- sweetalert -->
<script src="{!! asset('admin_assets/plugins/bower_components/sweetalert/sweetalert-dev.js') !!}"></script>

<!-- bootstrap-datepicker -->
<script src="{!! asset('admin_assets/plugins/bower_components/datepicker/bootstrap-datepicker.js') !!}"></script>

<!--bootstrap Datetime Picker -->
<script src="{!! asset('admin_assets/js/moment.js') !!}"></script>

<!--TIme picker js-->
<script src="{!! asset('admin_assets/plugins/bower_components/timepicker/bootstrap-timepicker.min.js') !!}"></script>

<!-- select2 -->
<script src="{!! asset('admin_assets/plugins/bower_components/select2/select2.full.min.js') !!}"></script>

{{-- <script src="{!! asset('admin_assets/plugins/bower_components/toast-master/js/jquery.toast.js') !!}"></script> --}}

{{-- <script src="{!! asset('admin_assets/js/toastr.js') !!}"></script> --}}

<!-- jquery-validator -->
<script type="text/javascript" src="{!! asset('admin_assets/plugins/bower_components/jquery-validator/jquery-validator.1.15.0.js') !!}"></script>
<script type="text/javascript" src="{!! asset('admin_assets/plugins/bower_components/jquery-validator/jquery-additional-method.1.15.0.min.js') !!}"></script>

<!-- Star Ratings -->
<script src="{!! asset('admin_assets/plugins/bower_components/rateyo/jquery.rateyo.js') !!}"></script>

<!-- Switch js -->
<script src="{!! asset('js/bootstrap-toggle.min.js') !!}"></script>

{{-- <script>
    $.noConflict();
    // Code that uses other library's $ can follow here.
</script> --}}

<script>
    $(function() {

        $(".select2").select2();

        $('#myTable').DataTable({
            ordering: false,
            processing: false,
            // aLengthMenu: [
            //     [10, 25, 50, 100, 200, -1],
            //     [10, 25, 50, 100, 200, "All"]
            // ],
            // dom: 'lBfrtip',

        });

        $('#myDataTable').DataTable({
            ordering: false,
            processing: true,
            // aLengthMenu: [
            //     [10, 25, 50, 100, 200, -1],
            //     [10, 25, 50, 100, 200, "All"]
            // ],
            // dom: "<'row'<'col-sm-6'l><'col-sm-5'f><'col-sm-1'B>>" + "<'row'<'col-sm-12'tr>>" +
            //     "<'row'<'col-sm-6'i><'col-sm-6'p>>",
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'csvHtml5',
                    text: 'CSV',
                    filename: new Date().toUTCString()
                },
                // {
                //     extend: 'pdfHtml5',
                //     orientation: 'portrait',
                //     pageSize: 'A4',
                //     text: 'PDF',
                //     title: 'Advance Deduction Logs',
                //     filename: new Date().toUTCString()
                // }
            ]
        });

        $('#myDataTableAlter').DataTable({
            ordering: false,
            processing: true,
            // aLengthMenu: [
            //     [10, 25, 50, 100, 200, -1],
            //     [10, 25, 50, 100, 200, "All"]
            // ],
            initComplete: function(settings, json) {
                $("#dailyAttendance").wrap(
                    "<div style='overflow:auto; width:100%;position:relative;'></div>");
            },

        });
        $('#myDataTableAlter1').DataTable({
            ordering: false,
            processing: true,
            // aLengthMenu: [
            //     [10, 25, 50, 100, 200, -1],
            //     [10, 25, 50, 100, 200, "All"]
            // ],
            autoFill: true,
            colReorder: false,
            keys: true,
            select: true,
            select: {
                style: 'multi'
            },
            dom: 'lBfrtip',
            autoWidth: true,
            columnDefs: [{
                targets: '_all',
                className: 'dt-left'
            }],
            buttons: [{
                extend: 'pdfHtml5',
                title: 'Detailed Report',
                text: 'PDF',
                orientation: 'landscape', // Set orientation to landscape
                customize: function(doc) {
                    var numColumns = doc.content[1].table.body[0].length;

                    var pageSize;
                    if (numColumns > 18) {
                        pageSize = 'A1';
                    } else if (numColumns > 12) {
                        pageSize = 'A2';
                    } else if (numColumns > 10) {
                        pageSize = 'A3';
                    } else {
                        pageSize = 'A4';
                    }
                    doc.pageSize = pageSize;

                    var fontSize;
                    if (numColumns > 30) {
                        fontSize = 7;
                    } else if (numColumns > 15) {
                        fontSize = 8;
                    } else {
                        fontSize = 8;
                    }



                }
            }]
        });

        var table = $('#myCustomDataTable').DataTable({
            autoFill: true,
            ordering: false,
            processing: true,
            colReorder: true,
            keys: true,
            select: true,
            select: {
                style: 'multi'
            },
            // aLengthMenu: [
            //     [10, 25, 50, 100, 200, -1],
            //     [10, 25, 50, 100, 200, "All"]
            // ],
            scrollY: 200,
            dom: 'lBfrtip',
            // dom: 'QlBfrtip',
            columnDefs: [{
                // searchBuilderTitle: 'DataTable',
                targets: [1]
            }],

            buttons: ['csv', 'excel', {
                text: 'Reload',
                action: function(e, dt, node, config) {
                    location.reload();
                }
            }, ],

        });

        var table = $('#myReportDataTable').DataTable({
            autoFill: true,
            ordering: false,
            processing: false,
            colReorder: false,
            keys: true,
            select: true,
            select: {
                style: 'multi'
            },
            // aLengthMenu: [
            //     [10, 25, 50, 100, 200, -1],
            //     [10, 25, 50, 100, 200, "All"]
            // ],
            dom: 'lBfrtip',
            // dom: 'QlBfrtip',
            columnDefs: [{
                // searchBuilderTitle: 'DataTable',
                targets: [1]
            }],

            buttons: ['csv', {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Muscat Insurance',
                text: 'PDF'
            }],

        });


    });

    function zoom() {
        document.body.style.zoom = "100%"
    }

    function addMenuClass() {
        var segment3 = '{{ Request::segment(1) }}';
        var url = "{{ url('/') }}" + segment3;
        // var navItem = $(this).find("[href='" + url + "']");

        $('a[href="' + url + '"]').parents('.treeview-menu').addClass('collapse in');
        $('a[href="' + url + '"]').parents('.treeview-menu').parent().children('.module').addClass('active');
    }

    $(".alert-success").delay(2000).fadeOut("slow");
    //   $(".alert-danger").delay(2000).fadeOut("slow");
    $(document).on("focus", ".yearPicker", function() {
        $(this).datepicker({
            format: 'yyyy',
            minViewMode: 2
        }).on('changeDate', function(e) {
            $(this).datepicker('hide');
        });
    });

    $(document).on("focus", ".datetimepicker", function() {
        $(this).datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
        }).on('changeDate', function(e) {
            $(this).datetimepicker('hide');
        });
    });

    $(document).on("focus", ".dateField", function() {
        $(this).datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            clearBtn: true
        }).on('changeDate', function(e) {
            $(this).datepicker('hide');
        });
    });

    $(document).on("focus", ".timePicker", function() {
        $(this).timepicker({
            showInputs: false,
            showMeridian: false,
            scrollDefaultNow: 'true',
            closeOnWindowScroll: 'true',
            showDuration: false,
            ignoreReadonly: true,
            minuteStep: 1,
        });
    });

    $(document).on("focus", ".timePicker24Hr", function() {
        $(this).timepicker({
            showInputs: false,
            showMeridian: false,
            timeFormat: 'H:i',
            scrollDefaultNow: 'true',
            closeOnWindowScroll: 'true',
            showDuration: false,
            ignoreReadonly: true,
            minuteStep: 1,
            defaultTime: '00:00'
        });
    });

    $(".monthField").datepicker({
        format: "yyyy-mm",
        viewMode: "months",
        minViewMode: "months"
    }).on('changeDate', function(e) {
        $(this).datepicker('hide');
    });

    $(document).on('click', '.delete', function() {
        var actionTo = $(this).attr('href');
        var token = $(this).attr('data-token');
        var id = $(this).attr('data-id');
        swal({
                title: "Are you sure?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: actionTo,
                        type: 'post',
                        data: {
                            _method: 'delete',
                            _token: token
                        },
                        success: function(data) {
                            data = data.trim();
                            console.log(data);
                            if (data == 'hasForeignKey') {
                                swal({
                                    title: "Oops!",
                                    text: "This data is used anywhere",
                                    type: "error"
                                });
                            } else if (data == 'success') {
                                swal({
                                        title: "Deleted!",
                                        text: "Your information delete successfully.",
                                        type: "success"
                                    },
                                    function(isConfirm) {
                                        if (isConfirm) {
                                            // $('.' + id).fadeOut(function() {
                                            // After fading out, reload the page
                                            location.reload();
                                            // });
                                        }
                                    });
                            } else {
                                swal({
                                    title: "Error!",
                                    text: "Something Error Found !, Please try again.",
                                    type: "error"
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

    $(document).on('click', '.logout', function() {
        var actionTo = "{{ url('ajaxlogout') }}";
        var token = $(this).attr('data-token');
        var id = $(this).attr('data-id');
        swal({
                title: "Are you sure?",
                text: "Please select Yes to confirm!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, logout!",
                closeOnConfirm: false
            },
            function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: actionTo,
                        type: 'get',
                        data: {
                            _method: 'get',
                            _token: token
                        },
                        success: function(data) {
                            var formsg = data.split("|||");
                            data = data.split("|||");
                            data = data[0];

                            if (data == 'success') {
                                swal({
                                        title: "Log out!",
                                        text: "Your account logout successfully.",
                                        type: "success"
                                    },
                                    function(isConfirm) {
                                        if (isConfirm) {
                                            $('.' + id).fadeOut();
                                        }
                                    });

                                setInterval(() => {
                                    window.location.href = "login";
                                }, 1000);

                            } else {
                                swal({
                                    title: "Error!",
                                    text: "Something Error Found !, Please try again.",
                                    type: "error"
                                });
                            }
                        }
                    });
                } else {
                    swal("Cancelled", "You are still logged in .", "error");
                }
            });
        return false;
    });

    function onlyNumberKey(evt) {
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57) && ASCIICode != 46)
            return false;
        return true;
    }

    function numberInputPayroll(evt) {
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57) && ASCIICode != 45 && ASCIICode != 46)
            return false;
        return true;
    }

    const showLoading = function() {
        swal({
            title: 'Now loading',
            allowEscapeKey: false,
            allowOutsideClick: false,
            timer: 2000,
            onOpen: () => {
                swal.showLoading();
            }
        }).then(
            () => {},
            (dismiss) => {
                if (dismiss == 'timer') {
                    console.log('closed by timer!!!!');
                    swal({
                        title: 'Finished!',
                        type: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    })
                }
            }
        )
    };
</script>
