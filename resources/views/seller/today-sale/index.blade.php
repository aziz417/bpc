@extends('layouts.admin.master')

@section('page')
    Sale
@endsection

@push('css')
    <style type="text/css">
        /* Basic Rules */
        .switch input {
            display:none;
        }
        .switch {
            display:inline-block;
            width:55px;
            height:25px;
            margin:8px;
            transform:translateY(50%);
            position:relative;
        }
        /* Style Wired */
        .slider {
            position:absolute;
            top:0;
            bottom:0;
            left:0;
            right:0;
            border-radius:30px;
            box-shadow:0 0 0 2px #777, 0 0 4px #777;
            cursor:pointer;
            border:4px solid transparent;
            overflow:hidden;
            transition:.4s;
        }
        .slider:before {
            position:absolute;
            content:"";
            width:100%;
            height:100%;
            background:#777;
            border-radius:30px;
            transform:translateX(-30px);
            transition:.4s;
        }

        input:checked + .slider:before {
            transform:translateX(30px);
            background:limeGreen;
        }
        input:checked + .slider {
            box-shadow:0 0 0 2px limeGreen,0 0 2px limeGreen;
        }

        /* Style Flat */
        .switch.flat .slider {
            box-shadow:none;
        }
        .switch.flat .slider:before {
            background:#FFF;
        }
        .switch.flat input:checked + .slider:before {
            background:white;
        }
        .switch.flat input:checked + .slider {
            background:limeGreen;
        }
        .patch{
            margin-top: -25px;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="success_message"></div>

            <div id="error_message"></div>

            <div class="card">
                <div class="card-header">
                    <a href="{{ route('seller.sale.create') }}" class="btn btn-sm btn-primary  float-right"><i class="fas fa-plus"></i> Create @yield('page')</a>
                    <h3 class="card-title">@yield('page')</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body  table-responsive">
                    <table id="data-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#Sl NO</th>
                            <th>Sale Code</th>
                            <th>Bill</th>
                            <th>VAT+SC+OH</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>#Sl NO</th>
                            <th>Sale Code</th>
                            <th>Bill</th>
                            <th>VAT+SC+OH</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function(){

            $('#data-table').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                pagingType: "full_numbers",
                ajax: {
                    url: '{!!  route('seller.sale.getData') !!}',
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'sale_code', name: 'sale_code'},
                    {data: 'total_bill', name: 'total_bill'},
                    {data: 'total_vat_sc_oh_amount', name: 'total_vat_sc_oh_amount'},
                    {data: 'in_total_bill', name: 'in_total_bill'},
                    {data: 'action', name: 'action', orderable: true, searchable: true}
                ]
            });

        });
    </script>


@endpush
