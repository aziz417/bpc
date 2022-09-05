@extends('layouts.admin.master')

@section('page')
    Product Distribution
@endsection

@push('css')
    <style type="text/css">
        /* Basic Rules */
        .switch input {
            display: none;
        }

        .switch {
            display: inline-block;
            width: 55px;
            height: 25px;
            margin: 8px;
            transform: translateY(50%);
            position: relative;
        }

        /* Style Wired */
        .slider {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            border-radius: 30px;
            box-shadow: 0 0 0 2px #777, 0 0 4px #777;
            cursor: pointer;
            border: 4px solid transparent;
            overflow: hidden;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            width: 100%;
            height: 100%;
            background: #777;
            border-radius: 30px;
            transform: translateX(-30px);
            transition: .4s;
        }

        input:checked + .slider:before {
            transform: translateX(30px);
            background: limeGreen;
        }

        input:checked + .slider {
            box-shadow: 0 0 0 2px limeGreen, 0 0 2px limeGreen;
        }

        /* Style Flat */
        .switch.flat .slider {
            box-shadow: none;
        }

        .switch.flat .slider:before {
            background: #FFF;
        }

        .switch.flat input:checked + .slider:before {
            background: white;
        }

        .switch.flat input:checked + .slider {
            background: limeGreen;
        }

        .patch {
            margin-top: -25px;
        }
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
                <div class="card-header">@yield('page') Create</div>

                <div class="card-body">
                    <form action="" method="post" id="product_distribution_post">
                        @csrf

                        <div class="row">
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label for="branch_type_id_1">B.T Name</label>
                                    <select name="branch_type_id[]" onchange="getBranch(this, 1)" id="branch_type_id_1"
                                            class="form-control" required>
                                        <option value="">Select Branch Type</option>
                                        @forelse ($branch_type_names as $branch_type_name)
                                            <option
                                                value="{{ $branch_type_name->id }}">{{ $branch_type_name->branch_type_name }}</option>
                                        @empty
                                            <option value="">Data Not Found</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="branch_id_1">Branch Name</label>
                                    <select onchange="sellerGet(this, 1)" name="branch_id[]" id="branch_id_1" class="form-control branch_id_1"
                                            required>
                                        <option selected disabled value="">Select Branch</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="seller_id_1">Seller Name</label>
                                    <select name="seller_id[]" id="seller_id_1" onchange="sellerProducts(this, 1)" class="form-control" required>
                                        <option value="">Select Seller</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="product_id_1">Product Name</label>
                                    <select name="product_id[]" id="product_id_1" onchange="attributeGet(this, 1)"
                                            class="form-control" required>
                                        <option value="">Select Product</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label for="stock_id_1">Attribute</label>
                                    <select name="stock_id[]" id="stock_id_1" onchange="stockGet(this, 1)"
                                            class="form-control stock_id_1" required>
                                        <option value="">Select Attribute</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label for="stock_1" class="control-label">Stock</label>
                                    <input type="number" name="stock[]" id="stock_1" class="form-control custom_disabled" required>
                                </div>
                            </div>

                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label for="quantity_1" class="control-label">Quantity</label>
                                    <input type="number" onchange="stockCalculate(this, 1)" min="1" name="quantity[]" id="quantity_1" class="form-control"
                                           required>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="date_1" class="control-label">Date:</label>
                                    <input type="date" value="{{ date('Y-m-d') }}" name="date[]" id="date_1"
                                           class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="newAddMore" id="newAddMore"></div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <button type="button" class="btn btn-sm btn-primary" onclick="addNewItem()"
                                            id="addRow"><i class="fa fa-plus"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(this)"
                                            id="deleteRow"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-success float-right">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('page')</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body  table-responsive">
                    <table id="data-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#Sl NO</th>
                            <th>Branch Type Name</th>
                            <th>Branch Name</th>
                            <th>Seller Name</th>
                            <th>Product Name</th>
                            <th>Attribute</th>
                            <th>Quantity</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>#Sl NO</th>
                            <th>Branch Type Name</th>
                            <th>Branch Name</th>
                            <th>Seller Name</th>
                            <th>Product Name</th>
                            <th>Attribute</th>
                            <th>Quantity</th>
                            <th>Date</th>
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
        // seller wise products
        function sellerProducts(event, className) {
            var options = `<option selected disabled value="">Select Product</option>`;
            $("#product_id_" + className).html(options)
            var attributeOption = `<option selected disabled value="">Select Attribute</option>`;

            $("#stock_" + className).val('')
            $("#stock_id_" + className).html(attributeOption)

            let seller_id = $(event).val()

            $.get("{{ route('seller.products') }}", {id: seller_id}, function (response) {
                if (response) {
                    for (var i = 0; i < response.length; i++) {
                        options += `<option style="text-transform: capitalize;" value="${response[i]['id']}">${response[i]['name']}</option>`
                    }
                    $("#product_id_" + className).html(options)
                }
            });
        }

        //get branch wise seller
        function sellerGet(event, className) {
            var options = `<option selected disabled value="">Select Seller</option>`;
            $("#seller_id_" + className).html(options)

            let branch_id = $(event).val()

            $.get("{{ route('branch.seller') }}", {id: branch_id}, function (response) {
                if (response) {
                    for (var i = 0; i < response.length; i++) {
                        options += `<option style="text-transform: capitalize;" value="${response[i]['id']}">${response[i]['name']}</option>`
                    }
                    $("#seller_id_" + className).html(options)
                }
            });
        }

        // get branch type wise branch
        function getBranch(event, className) {
            var options = `<option selected disabled value="">Select Branch</option>`;
            var sellerOptions = `<option selected disabled value="">Select Seller</option>`;
            var productsOption = `<option selected disabled value="">Select Product</option>`;
            var attributeOption = `<option selected disabled value="">Select Attribute</option>`;

            $("#stock_" + className).val('')
            $(".branch_id_" + className).html(options)
            $("#seller_id_" + className).html(sellerOptions)
            $("#product_id_" + className).html(productsOption)
            $("#stock_id_" + className).html(attributeOption)

            let branch_type_id = $(event).val()

            $.get("{{ route('branch.type.branches') }}", {id: branch_type_id}, function (response) {
                if (response) {
                    for (var i = 0; i < response.length; i++) {
                        options += `<option style="text-transform: capitalize;" value="${response[i].id}">${response[i].name}</option>`
                    }
                    $(".branch_id_" + className).html(options)
                }
            });
        }

        //get product attribute wise stock
        function attributeGet(event, className) {
            var options = `<option selected disabled value="">Select Attribute</option>`;
            $(".stock_id_" + className).html(options)
            $("#stock_" + className).val(' ')
            let product_id = $(event).val()

            $.get("{{ route('product.attribute.stock') }}", {id: product_id}, function (response) {
                if (response) {
                    for (var i = 0; i < response.length; i++) {
                        options += `<option style="text-transform: capitalize;" value="${response[i]['stock_id']}">${response[i]['attribute_name']}</option>`
                    }
                    $(".stock_id_" + className).html(options)
                }
            });
        }

        //get product attribute wise stock
        function stockGet(event, className) {
            let stock_id = $(event).val()
            let product_id = $("#product_id_"+className).val();

            let stock_product_id = product_id+'-'+stock_id;

            $.get("{{ route('get.product.stock') }}", {id: stock_id}, function (response) {
                if (response) {

                    localStorage.setItem(stock_product_id, response);
                    let mainStock = localStorage.getItem(stock_product_id);
                    $("#stock_" + className).val(mainStock)
                }
            });
        }

        function stockCalculate(e, className){
            // let quantity = $(e).val()
            // let stock_id = $("#stock_"+className).val()
            //
            // let product_id = $("#product_id_"+className).val();
            //
            // let stock_product_id = product_id+'-'+stock_id;
            //
            // let mainStock = localStorage.getItem(stock_product_id);
            // console.log(mainStock)

            // let perQuantity = mainStock - quantity;
            // $("#stock_" + className).val(perQuantity)
        }

    </script>
    <script>
        // product distribution new item add
        let rowCount = 2;

        function addNewItem() {
            $("#newAddMore").append(`
            <div class="row perItem addMoreAttributeSection">
            <div class="col-sm-1">
        <div class="form-group">
            <label for="branch_type_id_${rowCount}">B.T Name</label>
            <select name="branch_type_id[]" onchange="getBranch(this, '${rowCount}')" id="branch_type_id_${rowCount}" class="form-control" required>
                <option value="">Select Branch Type</option>
@forelse ($branch_type_names as $branch_type_name)
            <option value="{{ $branch_type_name->id }}">{{ $branch_type_name->branch_type_name }}</option>
                                        @empty
            <option value="">Data Not Found</option>
@endforelse
            </select>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-group">
            <label for="branch_id_${rowCount}">Branch Name</label>
            <select onchange="sellerGet(this, ${rowCount})" name="branch_id[]" id="branch_id_${rowCount}" class="form-control branch_id_${rowCount}" required>
                <option selected disabled value="">Select Branch</option>
            </select>
        </div>
    </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="seller_id_${rowCount}">Seller Name</label>
                                    <select onchange="sellerProducts(this, ${rowCount})" name="seller_id[]" id="seller_id_${rowCount}" class="form-control" required>
                                        <option value="">Select Seller</option>


            </select>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-group">
            <label for="product_id_${rowCount}">Product Name</label>
            <select name="product_id[]" id="product_id_${rowCount}" onchange="attributeGet(this, '${rowCount}')" class="form-control" required>
                <option value="">Select Product</option>

            </select>
        </div>
    </div>

    <div class="col-sm-1">
        <div class="form-group">
            <label for="stock_id_${rowCount}">Attribute</label>
            <select name="stock_id[]" id="stock_id_${rowCount}" onchange="stockGet(this, '${rowCount}')" class="form-control stock_id_${rowCount}" required>
                <option value="">Select Attribute</option>

            </select>
        </div>
    </div>

    <div class="col-sm-1">
        <div class="form-group">
            <label for="stock_${rowCount}" class="control-label">Stock</label>
            <input type="number" name="stock[]" id="stock_${rowCount}" class="form-control stock_${rowCount} custom_disabled" required>
        </div>
    </div>


    <div class="col-sm-1">
        <div class="form-group">
            <label for="quantity_${rowCount}" class="control-label">Quantity</label>
            <input type="number" name="quantity[]" min="1" id="quantity_${rowCount}" class="form-control" required>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-group">
            <label for="date_${rowCount}" class="control-label">Date:</label>
            <input type="date" id="date_${rowCount}" name="date[]" value="{{ date('Y-m-d') }}" class="form-control" required>
        </div>
    </div>
</div>
`)
            rowCount++;
        }

        // product distribution item remove
        function removeItem() {
            $(".addMoreAttributeSection:last").remove()
        }
    </script>
    <script>
        $(document).ready(function () {

            $("#product_distribution_post").on("submit", function (e) {
                e.preventDefault();

                var formData = new FormData($("#product_distribution_post").get(0));
                $.ajax({
                    url: "{{ route('product_distribution.store') }}",
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

                        $('.form-group').find('.valids').hide();
                        setTimeout(function () {
                            location.reload();
                        }, 1000);


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

    <script>
        $(document).ready(function () {
            $('#data-table').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                pagingType: "full_numbers",
                ajax: {
                    url: '{!!  route('product_distribution.getData') !!}',
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'branch_type_name', name: 'branch_type_name'},
                    {data: 'branch_name', name: 'branch_name'},
                    {data: 'seller_name', name: 'seller_name'},
                    {data: 'product_name', name: 'product_name'},
                    {data: 'attribute_name_with_quantity', name: 'attribute_name_with_quantity'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'date', name: 'date'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    </script>

    <script>
        $(document).on('click', '.deleteRecord', function (e) {
            e.preventDefault();
            var id = $(this).attr('rel');
            var deleteFunction = $(this).attr('rel1');
            swal({
                    title: "Are You Sure?",
                    text: "You will not be able to recover this record again",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, Delete It"
                },
                function () {
                    $.ajax({
                        type: "DELETE",
                        url: deleteFunction + '/' + id,
                        data: {id: id},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {

                            $('#data-table').DataTable().ajax.reload();

                            if (data.message) {
                                toastr.options =
                                    {
                                        "closeButton": true,
                                        "progressBar": true
                                    };
                                toastr.success(data.message);
                            }
                        }
                    });
                });
        });
    </script>
@endpush
