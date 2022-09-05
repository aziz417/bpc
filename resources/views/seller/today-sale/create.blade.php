@extends('layouts.admin.master')

@section('page')
    Sale
@endsection

@push('css')
    <style type="text/css">
        .calculation-section p {
            margin-top: 0;
            margin-bottom: 0;
        }

        .sub-total, .in_total_bill, .total_vat_sc_oh_amount, .total_bill, .stock{
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
        .custom_disabled{
            pointer-events: none;
            background: red;
            color: white;
        }
        .badge{
            font-size: 18px !important;
        }
        .user_name_border{
            border-bottom: 3px solid #e89f9f;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="card">

                <!-- /.card-header -->
                <div class="card-body  table-responsive">
                    <h2 class="text-center">Seller Name: <span class="user_name_border">{{ ucfirst(Auth::user()->name) }}</span></h2>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Products</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <form method="post" action=""
                                  id="sell_from_submit">
                                @csrf
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">SI</th>
                                        <th>Product Name</th>
                                        <th>VAT+SC+OH</th>
                                        <th>Stock</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Sub-Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($products as $key => $product)
                                        <input type="hidden" value="{{ $product->branch_id }}" name="branch_id">
                                        <input type="hidden" value="{{ $product->seller_id }}" name="seller_id">
                                        <input type="hidden" value="{{ $product->product_distribution_id }}"
                                               name="product_distribution_id[]">
                                        <input type="hidden" value="{{ $product->product_id }}" name="product_id[]">
                                        <tr data-row-id="{{ $key+1 }}">
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $product->product_name.'-'.$product->attribute_name }}</td>
                                            <td id="vat_sc_oh_{{ $key+1 }}">{{ $product->vat_sc_oh }}</td>
                                            <td>
                                                <input style="background: none" id="stock_{{ $key+1 }}" type="number"
                                                       class="stock" value="{{ $product->stock }}" name="stock[]">
                                                <input id="main_stock_{{ $key+1 }}" type="hidden"
                                                       value="{{ $product->stock }}">
                                            </td>
                                            <td><span class="badge bg-warning"><input min="0" name="quantity[]" type="number" style="width: 60px"></span></td>
                                            <td id="product_price_{{ $key+1 }}">{{ $product->product_price }}</td>
                                            <td>
                                                <input id="sub_vat_sc_total_{{ $key+1 }}" type="hidden"
                                                       class="sub_vat_sc_total" value="">
                                                <input style="background: none" id="sub_total_{{ $key+1 }}"
                                                       type="text" class="sub-total" value="00" name="sub_total[]">
                                            </td>
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
                                                    <td><span class="badge bg-danger">
                                                            <input id="total_bill" required class="total_bill" placeholder="00" type="text"
                                                                                             name="total_bill"></span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Total (VAT+SC+OH) </strong></td>
                                                    <td><span class="badge bg-warning"><input id="total_vat_sc_oh_amount" class="total_vat_sc_oh_amount"
                                                                                              value="00" type="text" name="total_vat_sc_oh_amount"></span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>In Total Bill </strong></td>
                                                    <td><span class="badge bg-primary">
                                                            <input required id="in_total_bill" class="in_total_bill" placeholder="00" type="text"
                                                                                              name="in_total_bill"></span></td>
                                                </tr>
                                                @hasrole('admin')
                                                    <tr>
                                                        <td><strong>Receive Amount </strong></td>
                                                        <td><span class="badge bg-success">
                                                                <input id="receive_amount" class="receive_amount" placeholder="00" type="text"
                                                                                                  name="receive_amount"></span></td>
                                                    </tr>
                                                @endhasrole
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mr-5 mb-4">
                                    <div style="width: 180px">
                                        <button type="submit" id="submit_btn" class="btn btn-block btn-sm btn-success">
                                            <strong
                                                style="font-size: 18px">Sell</strong></button>
                                    </div>
                                </div>

                            </form>

                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
    <div id="salePrint" style="display: none"></div>
@endsection



@push('js')

    <script>
        function salePrint(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        $(":input").bind('keyup mouseup', function () {
            let total_bill = 0;
            let sub_total_vat_sc_oh_amount = 0;
            let total_vat_sc_oh_amount = 0;

            let quantity = $(this).val();
            let row_id = $(this).closest("tr").data('row-id');
            let product_price = $("#product_price_" + row_id).html();
            let main_stock = $("#main_stock_" + row_id).val();

            $("#stock_" + row_id).val(main_stock - quantity);

            if($("#stock_" + row_id).val() < 0){
                alert('Out Of Stock')
                $("#submit_btn").addClass("custom_disabled")
            }else{
                $("#submit_btn").removeClass("custom_disabled")
            }

            let vat_sc_oh_amount = $("#vat_sc_oh_" + row_id).html();

            let sub_total = product_price * quantity;
            $("#sub_total_" + row_id).val(sub_total);


            sub_total_vat_sc_oh_amount = vat_sc_oh_amount * quantity;

            $("#sub_vat_sc_total_" + row_id).val(sub_total_vat_sc_oh_amount);

            // on every keyup, loop all the elements and add all the results
            $('.sub-total').each(function (index, element) {
                var val = parseFloat($(element).val());
                if (!isNaN(val)) {
                    total_bill += val;
                }
            });

            $('.sub_vat_sc_total').each(function (index, element) {
                var val = parseFloat($(element).val());
                if (!isNaN(val)) {
                    total_vat_sc_oh_amount += val;
                }
            });

            $('#total_bill').val(total_bill);
            $('#total_vat_sc_oh_amount').val(total_vat_sc_oh_amount);
            $('#in_total_bill').val(total_bill + total_vat_sc_oh_amount);
        });
    </script>

    <script>
        $(document).ready(function () {

            $("#sell_from_submit").on("submit", function (e) {
                e.preventDefault();
                var total_bill = $('#total_bill').val();
                var in_total_bill = $('#in_total_bill').val();
                if (total_bill <= 0 && in_total_bill <= 0){
                    alert('Please Entry Quantity')
                    return
                }

                var formData = new FormData($("#sell_from_submit").get(0));
                $.ajax({
                    url: "{{ route('product.sell.calculation.store') }}",
                    type: "post",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {

                        let sale_details_id = data.sell_details;

                        $.get("{{ route('sale.print') }}", {id: sale_details_id}, function (response) {
                            if (response) {

                                $("#salePrint").html(response)

                                var divToPrint = document.getElementById("salePrint");

                                var newWin = window.open('','Print-Window');

                                newWin.document.open();

                                newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

                                newWin.document.close();

                                setTimeout(function(){
                                    newWin.close();
                                    location.reload();
                                    },1000);
                            }
                        });

                        if (data.message) {
                            toastr.options =
                                {
                                    "closeButton": true,
                                    "progressBar": true
                                };
                            toastr.success(data.message);
                        }

                        $("form").trigger("reset");

                        // $('.form-group').find('.valids').hide();
                        // setTimeout(function () {
                        //     location.reload();
                        // }, 1000);
                    },

                    error: function (err) {

                        if (err.status === 422) {
                            $.each(err.responseJSON.errors, function (i, error) {
                                var el = $(document).find('[name="' + i + '"]');
                                el.after($('<span class="valids" style="color: red;">' + error + '</span>'));
                            });
                        }

                        if (err.status === 500) {
                            $('#error_message').html('<div class="alert alert-error">\n' +
                                '<button class="close" data-dismiss="alert">×</button>\n' +
                                '<strong>Error! ' + err.responseJSON.error + '</strong>' +
                                '</div>');
                        }
                    }
                });
            })
        })
    </script>

@endpush
