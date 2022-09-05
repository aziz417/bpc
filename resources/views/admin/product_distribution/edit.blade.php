@extends('layouts.admin.master')

@section('page')
    Product Distribution Edit
@endsection

@push('css')
    <style>
        .custom_disabled{
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
                <div class="card-header">@yield('page')</div>

                <div class="card-body">
                    <form action="" method="post" id="product_distribution_edit">
                        @method('PUT')
                        @csrf

                        <input type="hidden" name="" id="product_distribution_id"
                               value="{{ $product_distribution->product_distribution_id }}">
                        <div class="row">
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label for="branch_type_id">B.T Name</label>
                                    <select name="branch_type_id" onchange="getBranch(this)" id="branch_type_id"
                                            class="form-control" required>
                                        <option value="">Select Branch Type Name</option>
                                        @forelse ($branch_type_names as $branch_type_name)
                                            <option
                                                {{ $product_distribution->branch_type_id == $branch_type_name->id ? 'selected' : ''}} value="{{ $branch_type_name->id }}">{{ $branch_type_name->branch_type_name }}</option>
                                        @empty
                                            <option value="">Data Not Found</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="branch_id">Branch Name</label>
                                    <select name="branch_id" onchange="sellerGet(this)" id="branch_id" class="form-control branch_id" required>
                                        <option value="">Select Branch Name</option>
                                        @forelse ($branches as $branch)
                                            <option
                                                {{ $product_distribution->branch_id == $branch->id ? 'selected' : ''}} value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @empty
                                            <option value="">Data Not Found</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="seller_id">Seller Name</label>
                                    <select name="seller_id" id="seller_id" onchange="sellerProducts(this)" class="form-control" required>
                                        <option value="">Select Seller</option>
                                        @forelse ($sellers as $seller)
                                            <option
                                                {{ $product_distribution->seller_id == $seller->id ? 'selected' : ''}} value="{{ $seller->id }}">{{ $seller->name }}</option>
                                        @empty
                                            <option value="">Data Not Found</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="product_id">Product Name</label>
                                    <select name="product_id" id="product_id" onchange="getStock(this)"
                                            class="form-control" required>
                                        <option value="">Select Product</option>
                                        @forelse ($products as $product)
                                            <option
                                                {{ $product_distribution->product_id == $product->id ? 'selected' : ''}} value="{{ $product->id }}">{{ $product->name }}</option>
                                        @empty
                                            <option value="">Data Not Found</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-1">

                                <div class="form-group">
                                    <label for="stock_id">Attribute</label>
                                    <select name="stock_id" id="stock_id" onchange="stockGet(this)"
                                            class="form-control stock_id" required>
                                        <option value="">Select Attribute</option>
                                        @forelse ($stocks as $stock)
                                            <option
                                                {{ $product_distribution->stock_id == $stock->stock_id ? 'selected' : ''}} value="{{ $stock->stock_id }}">{{ $stock->attribute_name }}</option>
                                        @empty
                                            <option value="">Data Not Found</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label for="stock" class="control-label">Stock</label>
                                    <input type="number" value="{{ $stock->quantity }}" name="stock" id="stock"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label for="quantity" class="control-label">Quantity</label>
                                    <input type="number" value="{{ $product_distribution->quantity }}" name="quantity"
                                           id="quantity" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="date" class="control-label">Date</label>
                                    <input type="date" value="{{ $product_distribution->date }}" name="date" id="date"
                                           class="form-control" required>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <a href="{{ route('product_distribution') }}" class="btn btn-warning">Back</a>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // seller wise products
        function sellerProducts(event) {
            var options = `<option selected disabled value="">Select Product</option>`;
            $("#product_id").html(options)
            var attributeOption = `<option selected disabled value="">Select Attribute</option>`;

            $("#stock").val('')
            $("#stock_id").html(attributeOption)

            let seller_id = $(event).val()

            $.get("{{ route('seller.products') }}", {id: seller_id}, function (response) {
                if (response) {
                    for (var i = 0; i < response.length; i++) {
                        options += `<option style="text-transform: capitalize;" value="${response[i]['id']}">${response[i]['name']}</option>`
                    }
                    $("#product_id").html(options)
                }
            });
        }

        //get branch wise seller
        function sellerGet(event) {
            var options = `<option selected disabled value="">Select Seller</option>`;
            $("#seller_id").html(options)

            let branch_id = $(event).val()

            $.get("{{ route('branch.seller') }}", {id: branch_id}, function (response) {
                if (response) {
                    for (var i = 0; i < response.length; i++) {
                        options += `<option style="text-transform: capitalize;" value="${response[i]['id']}">${response[i]['name']}</option>`
                    }
                    $("#seller_id").html(options)
                }
            });
        }

        //get product attribute wise stock
        function stockGet(event) {
            let stock_id = $(event).val()
            $.get("{{ route('get.product.stock') }}", {id: stock_id}, function (response) {
                if (response) {
                    $("#stock").val(response)
                }
            });
        }

        // get branch type wise branch
        function getBranch(event) {
            var options = `<option selected disabled value="">Select Branch</option>`;
            var sellerOptions = `<option selected disabled value="">Select Seller</option>`;
            var productsOption = `<option selected disabled value="">Select Product</option>`;
            var attributeOption = `<option selected disabled value="">Select Attribute</option>`;

            $("#stock").val('')
            $(".branch").html(options)
            $("#seller_id").html(sellerOptions)
            $("#product_id").html(productsOption)
            $("#stock_id").html(attributeOption)
            $(".branch_id").html(options)
            let branch_type_id = $(event).val()

            $.get("{{ route('branch.type.branches') }}", {id: branch_type_id}, function (response) {
                if (response) {
                    for (var i = 0; i < response.length; i++) {
                        options += `<option style="text-transform: capitalize;" value="${response[i].id}">${response[i].name}</option>`
                    }
                    $(".branch_id").html(options)
                }
            });
        }

        //get product attribute wise stock
        function getStock(event, className) {
            var options = `<option selected disabled value="">Select Attribute</option>`;
            $(".stock_id").html(options)

            let product_id = $(event).val()

            $.get("{{ route('product.attribute.stock') }}", {id: product_id}, function (response) {
                if (response) {
                    for (var i = 0; i < response.length; i++) {
                        options += `<option style="text-transform: capitalize;" value="${response[i]['stock_id']}">${response[i]['attribute_name'] + '-' + response[i]['quantity']}</option>`
                    }
                    $(".stock_id").html(options)
                }
            });
        }
    </script>
    <script>
        $(document).ready(function () {

            $("#product_distribution_edit").on("submit", function (e) {
                e.preventDefault();
                $("#product_distribution_edit").addClass('custom_disabled')

                var id = $("#product_distribution_id").val();

                var formData = new FormData($("#product_distribution_edit").get(0));

                $.ajax({
                    url: "{{ route('product_distribution.update','') }}/" + id,
                    type: "post",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {

                        if (data.message) {
                            toastr.options =
                                {
                                    "closeButton": true,
                                    "progressBar": true
                                };
                            toastr.success(data.message);
                        }

                        $("form").trigger("reset");

                        setTimeout(function (){
                            location.reload();
                        }, 2000)

                        $('.form-group').find('.valids').hide();
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
