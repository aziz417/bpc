@extends('layouts.admin.master')

@section('page')
    Report
@endsection

@push('css')
    <style>
        .custom_btn_style {
            height: 30px !important;
            display: flex !important;
            align-items: center !important;
        }

        .disable {
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="success_message"></div>

            <div id="error_message"></div>

            <div class="card card-primary">
                <div class="p-3">
                    <h2>Report:</h2>
                    <div class="row">
                        <div class="col-sm-3 d-flex justify-content-between" id="date_type_select">
                            @hasrole('seller')
                            <div>
                                <label>Today</label>
                                <input type="radio" name="date_type" value="today_date">
                            </div>
                            @endhasrole
                            @hasrole('admin|supper-admin')
                            <div>
                                <label>Today And Other Day</label>
                                <input type="radio" name="date_type" value="today_date">
                            </div>
                            <div>
                                <label>Date To Date</label>
                                <input type="radio" name="date_type" value="date_to_date" class="">
                            </div>
                            @endhasrole

                        </div>
                        @hasrole('seller')
                        <div class="col-sm-3">
                            <div class="today d-none">
                                <label>Today</label>
                                <input type="date" value="{{ date('Y-m-d') }}" name="today" id="today_date"
                                       class="form-control disable">
                            </div>
                        </div>
                        @endhasrole

                        @hasrole('admin|supper-admin')
                        <div class="col-sm-3">
                            <div class="today d-none">
                                <label>Today Date And Other Day</label>
                                <input type="date" value="{{ date('Y-m-d') }}" name="today" id="today_date"
                                       class="form-control">
                            </div>
                        </div>
                        @endhasrole

                        <div class="col-sm-3">
                            <div class="date_to_date d-none">
                                <label>Form Date</label>
                                <input type="date" name="date_form" class="form-control" id="f_date">
                            </div>

                        </div>
                        <div class="col-sm-3">
                            <div class="date_to_date d-none">
                                <label>To Date</label>
                                <input type="date" name="date_to" class="form-control" id="to_date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-2">
                            @hasrole('admin|supper-admin')
                            <div class="d-flex justify-content-between">
                                <p><span class="date_type_text"></span> Admin Sale</p>
                                <button onclick="report('admin')"
                                        class="btn btn-success disable custom_btn_style float-right">
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                </button>
                            </div>
                            @endhasrole
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        @hasrole('admin|supper-admin')
                        <div class="col-sm-2">
                            <div class="d-flex justify-content-between">
                                <p><span class="date_type_text"></span> All Sale</p>
                                <button onclick="report('all')"
                                        class="btn btn-success disable custom_btn_style float-right">
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        @endhasrole
                        @hasrole('admin|supper-admin')
                        <div class="col-sm-3">
                            <div class="form-group">
                                <div class="d-flex justify-content-between">
                                    <p><span class="date_type_text"></span> Branch Based All Sale </p>
                                    <button onclick="report('branch')"
                                            class="btn btn-success disable custom_btn_style float-right">
                                        <i class="fa fa-print" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <label for="branch">Branch Name</label>
                                    <select name="branch_base" id="just_branch"
                                            class="form-control">
                                        <option value="">Select Branch</option>
                                        @forelse($branches as $branch)
                                            <option
                                                value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @empty
                                            <h2>No branch</h2>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endhasrole

                        <div class="col-sm-7">
                            <div class="d-flex justify-content-between">
                                <p><span class="date_type_text"></span> Seller Based All Sale Report</p>
                                <button onclick="report('seller')"
                                        class="btn btn-success custom_btn_style disable float-right">
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                </button>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="branch">Branch Name</label>
                                        <select onchange="branchBasedSeller(this)" name="seller_base" id="seller_base"
                                                class="form-control">
                                            <option value="">Select Branch</option>
                                            @forelse($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @empty
                                                <h2>No branch</h2>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="seller">Seller Name</label>
                                        <select name="seller"
                                                id="seller"
                                                class="form-control">
                                            <option value="">Select Seller</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="salePrint" style="display: none"></div>
@endsection

@push('js')
    <script>
        function report(type) {
            var date_type = $('input[name=date_type]:checked', '#date_type_select').val();
            var today_date = $("#today_date").val();
            var f_date = $("#f_date").val();
            var to_date = $("#to_date").val();
            var just_branch = $("#just_branch").val();
            var seller = $("#seller").val();
            var seller_base_branch = $("#seller_base").val();

            if (date_type == 'today_date') {
                f_date = null;
                to_date = null;
            } else if (date_type == 'date_to_date') {
                today_date = null;
            }

            if (type == 'all') {
                just_branch = null;
                seller = null;
                seller_base_branch = null;
            } else if (type == 'branch') {
                seller = null;
                seller_base_branch = null;
            } else if (type == 'seller') {
                just_branch = null;
            } else if (type == 'admin') {
                just_branch = null;
                seller = null;
                seller_base_branch = null;
            }

            $.get("{{ route('all.report') }}", {
                type: type,
                date_type: date_type,
                today_date: today_date,
                f_date: f_date,
                to_date: to_date,
                just_branch: just_branch,
                seller: seller,
                seller_base_branch: seller_base_branch,
            }, function (response) {
                if (response) {

                    $("#salePrint").html(response)

                    var divToPrint = document.getElementById("salePrint");

                    var newWin = window.open('', 'Print-Window');

                    newWin.document.open();

                    newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');

                    newWin.document.close();

                    setTimeout(function () {
                        newWin.close();
                    }, 1000);
                }
            });
        }
    </script>
    <script>
        $('#date_type_select input').on('change', function () {
            $(".custom_btn_style").removeClass('disable')
            var date_type = $('input[name=date_type]:checked', '#date_type_select').val();
            if (date_type == 'today_date') {
                $(".today").removeClass('d-none')
                $(".date_to_date").addClass('d-none')
                $(".date_type_text").html('Today ')
            } else {
                $(".date_to_date").removeClass('d-none')
                $(".today").addClass('d-none')
                $(".date_type_text").html('Date To Date ')
            }
        });
    </script>

    <script>

        var loginUserId = {{ Auth::user()->id }};

        function branchBasedSeller(event) {

            var options = `<option selected disabled value="">Select Seller</option>`;

            $("#seller").html(options)

            let branch_id = $(event).val()

            $.get("{{ route('branch.seller.report') }}", {id: branch_id}, function (response) {
                if (response) {
                    for (var i = 0; i < response['sellers'].length; i++) {
                        if (response['userRole'] == 'seller') {
                            if (loginUserId == response['sellers'][i]['id']) {
                                options += `<option style="text-transform: capitalize;" value="${response['sellers'][i]['id']}">${response['sellers'][i]['name']}</option>`
                            }
                        } else {
                            options += `<option style="text-transform: capitalize;" value="${response['sellers'][i]['id']}">${response['sellers'][i]['name']}</option>`
                        }
                    }
                    $("#seller").html(options)
                }
            });
        }
    </script>
@endpush
