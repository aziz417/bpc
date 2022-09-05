@extends('layouts.admin.master')

@section('page')
    Sale Details
@endsection

@push('css')
    <style type="text/css">
        .calculation-section p {
            margin-top: 0;
            margin-bottom: 0;
        }

        .sub-total, .in_total_bill, .total_vat_sc_oh_amount, .total_bill, .stock {
            width: 120px;
            pointer-events: none;
            text-align: center;
            border: 0;
        }
        .receive_amount{
            width: 120px;
            text-align: center;
            border: 0;
        }
        .badge{
            font-size: 18px !important;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- /.card-header -->
            <div class="card-body  table-responsive">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="justify-content-between d-flex d-block align-items-center p-3">
                        <div class="">
                            <p>Sale Code: {{ $total_calculation->sale_code }}</p>
                        </div>
                        <div class="">
                            <a href="{{ route('seller.sale.create') }}" class="btn btn-sm btn-primary  float-right"><i
                                    class="fas fa-plus"></i> Create Sale</a>
                            <a href="{{ route('seller.sale.index') }}"
                               class="btn btn-sm btn-warning  float-right  mr-2"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th style="width: 10px">SI</th>
                                <th>Product Name</th>
                                <th>VAT+SC+OH</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Sub-Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($products as $key => $product)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $product->name.'-'.$product->attribute_name }}</td>
                                    <td>{{ $product->vat_sc_oh }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>{{ $product->unit_price }}</td>
                                    <td>{{ $product->unit_price*$product->quantity }}</td>
                                </tr>
                            @empty
                                <p class="text-center">Product Not Found</p>
                            @endforelse
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6">
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td><strong>Total Bill </strong></td>
                                            <td>
                                                <span class="badge bg-danger">
                                                   <input class="in_total_bill" value="{{ $total_calculation->total_bill }}" type="text">
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total (VAT+SC+OH) </strong></td>
                                            <td>
                                                <span class="badge bg-warning">
                                                    <input class="in_total_bill" value="{{ $total_calculation->total_vat_sc_oh_amount }}" type="text">
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>In Total Bill </strong></td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    <input class="in_total_bill" value="{{ $total_calculation->in_total_bill }}" type="text">
                                                </span>
                                            </td>
                                        </tr>
                                        @hasrole('admin')
                                        <tr>
                                            <td><strong>Receive Amount </strong></td>
                                            <td><span class="badge bg-success">
                                                                <input id="receive_amount" class="receive_amount" value="{{ $total_calculation->receive_amount }}" placeholder="00" type="text"
                                                                       name="receive_amount"></span></td>
                                        </tr>
                                        @endhasrole
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
@endsection

@push('js')


@endpush
