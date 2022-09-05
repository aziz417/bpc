@extends('layouts.admin.master')

@section('page')
    {{ ucfirst(Auth::user()->name) }} Seller DashBoard
@endsection

@push('css')
    <style>
        .sub-total, .in_total_bill, .total_vat_sc_oh_amount, .total_bill, .stock {
            width: 120px;
            pointer-events: none;
            text-align: center;
            border: 0;
        }
        .badge{
            font-size: 18px !important;
        }
    </style>
@endpush

@section('content')

    <div class="card">
        <div class="row p-3">
            <div class="col-sm-6"></div>

            <div class="col-sm-6">
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td><strong>Total Bill </strong></td>
                            <td>
                                <span class="badge bg-danger">
                                   <input class="in_total_bill"
                                          value="{{ $todaySaleTotalAmount->total_bill }}" type="text">
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Total (VAT+SC+OH) </strong></td>
                            <td>
                                <span class="badge bg-warning">
                                    <input class="in_total_bill"
                                           value="{{ $todaySaleTotalAmount->total_vat_sc_oh_amount }}"
                                           type="text">
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>In Total Bill </strong></td>
                            <td>
                                <span class="badge bg-primary">
                                    <input class="in_total_bill"
                                           value="{{ $todaySaleTotalAmount->in_total_bill }}" type="text">
                                </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Product Name</th>
                    <th>Distribute</th>
                    <th>Sell</th>
                    <th>Total Sales Amount</th>

                    {{--                    <th>Progress</th>--}}
                    {{--                    <th style="width: 40px">Label</th>--}}
                </tr>
                </thead>
                <tbody>
                @forelse($products as $key => $product)
                    <tr>
                        <td>{{ $key+1 }}.</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->distribution_quantity }}</td>
                        <td>{{ $product->product_quantity }}</td>
                        <td>{{ $product->product_sub_total }}</td>
                        {{--                    <td>--}}
                        {{--                        <div class="progress progress-xs">--}}
                        {{--                            <div class="progress-bar progress-bar-danger" style="width: 55%"></div>--}}
                        {{--                        </div>--}}
                        {{--                    </td>--}}
                        {{--                    <td><span class="badge bg-danger">55%</span></td>--}}
                    </tr>
                @empty



                @endforelse
                </tbody>
            </table>
            @if($products->count() < 1)
                <h2 class="text-center">Today No Sell</h2>
            @endif
        </div>
        <!-- /.card-body -->
    </div>


@endsection

@push('js')
@endpush
