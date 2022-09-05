@extends('layouts.admin.master')

@section('page')
     DashBoard
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
        <h2 class="text-center py-1">Today All Sale Status</h2>
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

    {{--    <!-- Main content -->--}}
    {{--    <section class="content">--}}
    {{--        <div class="container-fluid">--}}
    {{--            <!-- Small boxes (Stat box) -->--}}
    {{--            <div class="row">--}}
    {{--                <div class="col-12 col-sm-6 col-md-3">--}}
    {{--                    <div class="info-box">--}}
    {{--                        <span class="info-box-icon bg-info elevation-1"><i class="fa fa-shopping-cart"></i></span>--}}

    {{--                        <div class="info-box-content">--}}
    {{--                            <span class="info-box-text">Products</span>--}}
    {{--                            <span class="info-box-number">--}}
    {{--                              {{ $products }}--}}
    {{--                            </span>--}}
    {{--                        </div>--}}
    {{--                        <!-- /.info-box-content -->--}}
    {{--                    </div>--}}
    {{--                    <!-- /.info-box -->--}}
    {{--                </div>--}}
    {{--                <!-- /.col -->--}}
    {{--                <div class="col-12 col-sm-6 col-md-3">--}}
    {{--                    <div class="info-box mb-3">--}}
    {{--                        <span class="info-box-icon bg-danger elevation-1">--}}
    {{--                            <i class="fas fa-list-alt"></i>--}}
    {{--                        </span>--}}

    {{--                        <div class="info-box-content">--}}
    {{--                            <span class="info-box-text">Categories</span>--}}
    {{--                            <span class="info-box-number">{{ $categories }}</span>--}}
    {{--                        </div>--}}
    {{--                        <!-- /.info-box-content -->--}}
    {{--                    </div>--}}
    {{--                    <!-- /.info-box -->--}}
    {{--                </div>--}}
    {{--                <!-- /.col -->--}}

    {{--                <!-- fix for small devices only -->--}}
    {{--                <div class="clearfix hidden-md-up"></div>--}}

    {{--                <div class="col-12 col-sm-6 col-md-3">--}}
    {{--                    <div class="info-box mb-3">--}}
    {{--                        <span class="info-box-icon bg-success elevation-1">--}}
    {{--                            <i class="fas fa-users"></i>--}}
    {{--                        </span>--}}

    {{--                        <div class="info-box-content">--}}
    {{--                            <span class="info-box-text">Sales</span>--}}
    {{--                            <span class="info-box-number">{{ $users }}</span>--}}
    {{--                        </div>--}}
    {{--                        <!-- /.info-box-content -->--}}
    {{--                    </div>--}}
    {{--                    <!-- /.info-box -->--}}
    {{--                </div>--}}
    {{--                <!-- /.col -->--}}
    {{--                <div class="col-12 col-sm-6 col-md-3">--}}
    {{--                    <div class="info-box mb-3">--}}
    {{--                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>--}}

    {{--                        <div class="info-box-content">--}}
    {{--                            <span class="info-box-text">New Members</span>--}}
    {{--                            <span class="info-box-number">2,000</span>--}}
    {{--                        </div>--}}
    {{--                        <!-- /.info-box-content -->--}}
    {{--                    </div>--}}
    {{--                    <!-- /.info-box -->--}}
    {{--                </div>--}}
    {{--                <!-- /.col -->--}}
    {{--            </div>--}}
    {{--            <!-- /.row -->--}}

    {{--            <div class="row">--}}
    {{--                <div class="col-md-12">--}}
    {{--                    <div class="card">--}}
    {{--                        <div class="card-header">--}}
    {{--                            <h5 class="card-title">Monthly Recap Report</h5>--}}

    {{--                            <div class="card-tools">--}}
    {{--                                <button type="button" class="btn btn-tool" data-card-widget="collapse">--}}
    {{--                                    <i class="fas fa-minus"></i>--}}
    {{--                                </button>--}}
    {{--                                <div class="btn-group">--}}
    {{--                                    <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">--}}
    {{--                                        <i class="fas fa-wrench"></i>--}}
    {{--                                    </button>--}}
    {{--                                    <div class="dropdown-menu dropdown-menu-right" role="menu">--}}
    {{--                                        <a href="#" class="dropdown-item">Action</a>--}}
    {{--                                        <a href="#" class="dropdown-item">Another action</a>--}}
    {{--                                        <a href="#" class="dropdown-item">Something else here</a>--}}
    {{--                                        <a class="dropdown-divider"></a>--}}
    {{--                                        <a href="#" class="dropdown-item">Separated link</a>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                                <button type="button" class="btn btn-tool" data-card-widget="remove">--}}
    {{--                                    <i class="fas fa-times"></i>--}}
    {{--                                </button>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <!-- /.card-header -->--}}
    {{--                        <div class="card-body">--}}
    {{--                            <div class="row">--}}
    {{--                                <div class="col-md-8">--}}
    {{--                                    <p class="text-center">--}}
    {{--                                        <strong>Sales: 1 Jan, 2014 - 30 Jul, 2014</strong>--}}
    {{--                                    </p>--}}

    {{--                                    <div class="chart">--}}
    {{--                                        <!-- Sales Chart Canvas -->--}}
    {{--                                        <canvas id="salesChart" height="180" style="height: 180px;"></canvas>--}}
    {{--                                    </div>--}}
    {{--                                    <!-- /.chart-responsive -->--}}
    {{--                                </div>--}}
    {{--                                <!-- /.col -->--}}
    {{--                                <div class="col-md-4">--}}
    {{--                                    <p class="text-center">--}}
    {{--                                        <strong>Goal Completion</strong>--}}
    {{--                                    </p>--}}

    {{--                                    <div class="progress-group">--}}
    {{--                                        Add Products to Cart--}}
    {{--                                        <span class="float-right"><b>160</b>/200</span>--}}
    {{--                                        <div class="progress progress-sm">--}}
    {{--                                            <div class="progress-bar bg-primary" style="width: 80%"></div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                    <!-- /.progress-group -->--}}

    {{--                                    <div class="progress-group">--}}
    {{--                                        Complete Purchase--}}
    {{--                                        <span class="float-right"><b>310</b>/400</span>--}}
    {{--                                        <div class="progress progress-sm">--}}
    {{--                                            <div class="progress-bar bg-danger" style="width: 75%"></div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}

    {{--                                    <!-- /.progress-group -->--}}
    {{--                                    <div class="progress-group">--}}
    {{--                                        <span class="progress-text">Visit Premium Page</span>--}}
    {{--                                        <span class="float-right"><b>480</b>/800</span>--}}
    {{--                                        <div class="progress progress-sm">--}}
    {{--                                            <div class="progress-bar bg-success" style="width: 60%"></div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}

    {{--                                    <!-- /.progress-group -->--}}
    {{--                                    <div class="progress-group">--}}
    {{--                                        Send Inquiries--}}
    {{--                                        <span class="float-right"><b>250</b>/500</span>--}}
    {{--                                        <div class="progress progress-sm">--}}
    {{--                                            <div class="progress-bar bg-warning" style="width: 50%"></div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                    <!-- /.progress-group -->--}}
    {{--                                </div>--}}
    {{--                                <!-- /.col -->--}}
    {{--                            </div>--}}
    {{--                            <!-- /.row -->--}}
    {{--                        </div>--}}
    {{--                        <!-- ./card-body -->--}}
    {{--                        <div class="card-footer">--}}
    {{--                            <div class="row">--}}
    {{--                                <div class="col-sm-3 col-6">--}}
    {{--                                    <div class="description-block border-right">--}}
    {{--                                        <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>--}}
    {{--                                        <h5 class="description-header">$35,210.43</h5>--}}
    {{--                                        <span class="description-text">TOTAL REVENUE</span>--}}
    {{--                                    </div>--}}
    {{--                                    <!-- /.description-block -->--}}
    {{--                                </div>--}}
    {{--                                <!-- /.col -->--}}
    {{--                                <div class="col-sm-3 col-6">--}}
    {{--                                    <div class="description-block border-right">--}}
    {{--                                        <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>--}}
    {{--                                        <h5 class="description-header">$10,390.90</h5>--}}
    {{--                                        <span class="description-text">TOTAL COST</span>--}}
    {{--                                    </div>--}}
    {{--                                    <!-- /.description-block -->--}}
    {{--                                </div>--}}
    {{--                                <!-- /.col -->--}}
    {{--                                <div class="col-sm-3 col-6">--}}
    {{--                                    <div class="description-block border-right">--}}
    {{--                                        <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>--}}
    {{--                                        <h5 class="description-header">$24,813.53</h5>--}}
    {{--                                        <span class="description-text">TOTAL PROFIT</span>--}}
    {{--                                    </div>--}}
    {{--                                    <!-- /.description-block -->--}}
    {{--                                </div>--}}
    {{--                                <!-- /.col -->--}}
    {{--                                <div class="col-sm-3 col-6">--}}
    {{--                                    <div class="description-block">--}}
    {{--                                        <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>--}}
    {{--                                        <h5 class="description-header">1200</h5>--}}
    {{--                                        <span class="description-text">GOAL COMPLETIONS</span>--}}
    {{--                                    </div>--}}
    {{--                                    <!-- /.description-block -->--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <!-- /.row -->--}}
    {{--                        </div>--}}
    {{--                        <!-- /.card-footer -->--}}
    {{--                    </div>--}}
    {{--                    <!-- /.card -->--}}
    {{--                </div>--}}
    {{--                <!-- /.col -->--}}
    {{--            </div>--}}
    {{--            <!-- /.row -->--}}
    {{--            <div class="row">--}}


    {{--                <div class="col-md-8">--}}
    {{--                    <!-- TABLE: LATEST ORDERS -->--}}
    {{--                    <div class="card">--}}
    {{--                        <div class="card-header border-transparent">--}}
    {{--                            <h3 class="card-title">Latest Orders</h3>--}}

    {{--                            <div class="card-tools">--}}
    {{--                                <button type="button" class="btn btn-tool" data-card-widget="collapse">--}}
    {{--                                    <i class="fas fa-minus"></i>--}}
    {{--                                </button>--}}
    {{--                                <button type="button" class="btn btn-tool" data-card-widget="remove">--}}
    {{--                                    <i class="fas fa-times"></i>--}}
    {{--                                </button>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <!-- /.card-header -->--}}
    {{--                        <div class="card-body p-0">--}}
    {{--                            <div class="table-responsive">--}}
    {{--                                <table class="table m-0">--}}
    {{--                                    <thead>--}}
    {{--                                    <tr>--}}
    {{--                                        <th>Order ID</th>--}}
    {{--                                        <th>Item</th>--}}
    {{--                                        <th>Status</th>--}}
    {{--                                        <th>Popularity</th>--}}
    {{--                                    </tr>--}}
    {{--                                    </thead>--}}
    {{--                                    <tbody>--}}
    {{--                                    <tr>--}}
    {{--                                        <td><a href="pages/examples/invoice.html">OR9842</a></td>--}}
    {{--                                        <td>Call of Duty IV</td>--}}
    {{--                                        <td><span class="badge badge-success">Shipped</span></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}
    {{--                                    <tr>--}}
    {{--                                        <td><a href="pages/examples/invoice.html">OR1848</a></td>--}}
    {{--                                        <td>Samsung Smart TV</td>--}}
    {{--                                        <td><span class="badge badge-warning">Pending</span></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}
    {{--                                    <tr>--}}
    {{--                                        <td><a href="pages/examples/invoice.html">OR7429</a></td>--}}
    {{--                                        <td>iPhone 6 Plus</td>--}}
    {{--                                        <td><span class="badge badge-danger">Delivered</span></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}
    {{--                                    <tr>--}}
    {{--                                        <td><a href="pages/examples/invoice.html">OR7429</a></td>--}}
    {{--                                        <td>Samsung Smart TV</td>--}}
    {{--                                        <td><span class="badge badge-info">Processing</span></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="sparkbar" data-color="#00c0ef" data-height="20">90,80,-90,70,-61,83,63</div>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}
    {{--                                    <tr>--}}
    {{--                                        <td><a href="pages/examples/invoice.html">OR1848</a></td>--}}
    {{--                                        <td>Samsung Smart TV</td>--}}
    {{--                                        <td><span class="badge badge-warning">Pending</span></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}
    {{--                                    <tr>--}}
    {{--                                        <td><a href="pages/examples/invoice.html">OR7429</a></td>--}}
    {{--                                        <td>iPhone 6 Plus</td>--}}
    {{--                                        <td><span class="badge badge-danger">Delivered</span></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}
    {{--                                    <tr>--}}
    {{--                                        <td><a href="pages/examples/invoice.html">OR9842</a></td>--}}
    {{--                                        <td>Call of Duty IV</td>--}}
    {{--                                        <td><span class="badge badge-success">Shipped</span></td>--}}
    {{--                                        <td>--}}
    {{--                                            <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>--}}
    {{--                                        </td>--}}
    {{--                                    </tr>--}}
    {{--                                    </tbody>--}}
    {{--                                </table>--}}
    {{--                            </div>--}}
    {{--                            <!-- /.table-responsive -->--}}
    {{--                        </div>--}}
    {{--                        <!-- /.card-body -->--}}
    {{--                        <div class="card-footer clearfix">--}}
    {{--                            <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a>--}}
    {{--                            <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All Orders</a>--}}
    {{--                        </div>--}}
    {{--                        <!-- /.card-footer -->--}}
    {{--                    </div>--}}
    {{--                    <!-- /.card -->--}}
    {{--                </div>--}}
    {{--                <div class="col-md-4">--}}
    {{--                    <!-- PRODUCT LIST -->--}}
    {{--                    <div class="card">--}}
    {{--                        <div class="card-header">--}}
    {{--                            <h3 class="card-title">Recently Added Products</h3>--}}

    {{--                            <div class="card-tools">--}}
    {{--                                <button type="button" class="btn btn-tool" data-card-widget="collapse">--}}
    {{--                                    <i class="fas fa-minus"></i>--}}
    {{--                                </button>--}}
    {{--                                <button type="button" class="btn btn-tool" data-card-widget="remove">--}}
    {{--                                    <i class="fas fa-times"></i>--}}
    {{--                                </button>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <!-- /.card-header -->--}}
    {{--                        <div class="card-body p-0">--}}
    {{--                            <ul class="products-list product-list-in-card pl-2 pr-2">--}}
    {{--                                <li class="item">--}}
    {{--                                    <div class="product-img">--}}
    {{--                                        <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">--}}
    {{--                                    </div>--}}
    {{--                                    <div class="product-info">--}}
    {{--                                        <a href="javascript:void(0)" class="product-title">Samsung TV--}}
    {{--                                            <span class="badge badge-warning float-right">$1800</span></a>--}}
    {{--                                        <span class="product-description">--}}
    {{--                        Samsung 32" 1080p 60Hz LED Smart HDTV.--}}
    {{--                      </span>--}}
    {{--                                    </div>--}}
    {{--                                </li>--}}
    {{--                                <!-- /.item -->--}}
    {{--                                <li class="item">--}}
    {{--                                    <div class="product-img">--}}
    {{--                                        <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">--}}
    {{--                                    </div>--}}
    {{--                                    <div class="product-info">--}}
    {{--                                        <a href="javascript:void(0)" class="product-title">Bicycle--}}
    {{--                                            <span class="badge badge-info float-right">$700</span></a>--}}
    {{--                                        <span class="product-description">--}}
    {{--                        26" Mongoose Dolomite Men's 7-speed, Navy Blue.--}}
    {{--                      </span>--}}
    {{--                                    </div>--}}
    {{--                                </li>--}}
    {{--                                <!-- /.item -->--}}
    {{--                                <li class="item">--}}
    {{--                                    <div class="product-img">--}}
    {{--                                        <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">--}}
    {{--                                    </div>--}}
    {{--                                    <div class="product-info">--}}
    {{--                                        <a href="javascript:void(0)" class="product-title">--}}
    {{--                                            Xbox One <span class="badge badge-danger float-right">--}}
    {{--                        $350--}}
    {{--                      </span>--}}
    {{--                                        </a>--}}
    {{--                                        <span class="product-description">--}}
    {{--                        Xbox One Console Bundle with Halo Master Chief Collection.--}}
    {{--                      </span>--}}
    {{--                                    </div>--}}
    {{--                                </li>--}}
    {{--                                <!-- /.item -->--}}
    {{--                                <li class="item">--}}
    {{--                                    <div class="product-img">--}}
    {{--                                        <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">--}}
    {{--                                    </div>--}}
    {{--                                    <div class="product-info">--}}
    {{--                                        <a href="javascript:void(0)" class="product-title">PlayStation 4--}}
    {{--                                            <span class="badge badge-success float-right">$399</span></a>--}}
    {{--                                        <span class="product-description">--}}
    {{--                        PlayStation 4 500GB Console (PS4)--}}
    {{--                      </span>--}}
    {{--                                    </div>--}}
    {{--                                </li>--}}
    {{--                                <!-- /.item -->--}}
    {{--                            </ul>--}}
    {{--                        </div>--}}
    {{--                        <!-- /.card-body -->--}}
    {{--                        <div class="card-footer text-center">--}}
    {{--                            <a href="javascript:void(0)" class="uppercase">View All Products</a>--}}
    {{--                        </div>--}}
    {{--                        <!-- /.card-footer -->--}}
    {{--                    </div>--}}
    {{--                    <!-- /.card -->--}}
    {{--                </div>--}}
    {{--            </div>--}}

    {{--        </div><!-- /.container-fluid -->--}}
    {{--    </section>--}}
    {{--    <!-- /.content -->--}}
    {{--    <!-- Main row -->--}}
    {{--    <div class="row">--}}

    {{--    </div>--}}
    {{--    <!-- /.row (main row) -->--}}

@endsection

@push('js')
@endpush
