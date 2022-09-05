@extends('layouts.admin.master')

@section('page')
    Stock
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

        .custom_disabled{
            pointer-events: none;
            background: red;
            color: #fff;
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
                    <form action="" method="post" id="stock_post">
                        @csrf

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="product_id">Product Name</label>
                                        <select name="product_id[]" id="product_id_1" class="form-control" required>
                                            <option value="">Select Product</option>
                                            @forelse ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @empty
                                                <option value="">Data Not Found</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="attribute_id_1">Attribute Name</label>
                                        <select name="attribute_id[]" id="attribute_id_1" onchange="checkStocked(this, 1)" class="form-control" required>
                                            <option value="">Select Attribute</option>
                                            @forelse ($attributes as $attribute)
                                                <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                            @empty
                                                <option value="">Data Not Found</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="quantity_1" id="quantity_tracker_1"  class="control-label">Quantity</label>
                                        <input type="number" name="quantity[]" id="quantity_1" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                        <div class="newAddMore" id="newAddMore"></div>


                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <button type="button" class="btn btn-sm btn-primary" onclick="addNewItem()" id="addRow"><i class="fa fa-plus"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(this)" id="deleteRow"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="col-6">
                                <button id="submitBtn" type="submit" class="btn btn-success float-right">Submit</button>
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
                            <th>Quantity</th>
                            <th>Product Name</th>
                            <th>Attribute Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>#Sl NO</th>
                            <th>Quantity</th>
                            <th>Product Name</th>
                            <th>Attribute Name</th>
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
        let rowCount = 2;

        // stock new item add
        function addNewItem(){

            $("#newAddMore").append(`
            <div class="addMoreAttributeSection">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="product_id_${rowCount}">Product Name</label>
                            <select name="product_id[]" id="product_id_${rowCount}" class="form-control" required>
                                <option value="">Select Product</option>
                                @forelse ($products as $product)
                                     <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @empty
                                     <option value="">Data Not Found</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="attribute_id_${rowCount}">Attribute Name</label>
                            <select name="attribute_id[]" id="attribute_id_${rowCount}" onchange="checkStocked(this, ${rowCount})" class="form-control" required>
                                <option value="">Select Attribute</option>
                                @forelse ($attributes as $attribute)
                                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                @empty
                                    <option value="">Data Not Found</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="quantity" id="quantity_tracker_${rowCount}" class="control-label">Quantity</label>
                            <input type="number" name="quantity[]" id="quantity_${rowCount}" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            `)

            rowCount++;
        }

        // stock item remove
        function removeItem(){
            $(".addMoreAttributeSection:last").remove()
        }
    </script>
    <script>
        function checkStocked(e, idName){
            var attribute_id = $(e).val();
            var product_id = $('#product_id_'+idName).val();
            $.get("{{ route('check.stocked') }}", { attribute_id: attribute_id, product_id: product_id }, function (response) {
                if (response) {
                    $("#quantity_tracker_"+idName).append(`<span  class="quantity_tracker_${idName} alert-danger p-1 ml-1">Appended text</span>`)
                    $("#submitBtn").addClass("custom_disabled")
                }else {
                    $(".quantity_tracker_"+idName).remove();
                    $("#submitBtn").removeClass("custom_disabled")
                }
            });
        }
    </script>
    <script>
        $(document).ready(function () {

            $("#stock_post").on("submit",function (e) {
                e.preventDefault();

                var formData = new FormData( $("#stock_post").get(0));
                $.ajax({
                    url : "{{ route('stock.store') }}",
                    type: "post",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {

                        if (data.message){
                            toastr.options =
                                {
                                    "closeButton" : true,
                                    "progressBar" : true
                                };
                            toastr.success(data.message);
                        }

                        $("form").trigger("reset");

                        $('.form-group').find('.valids').hide();
                        setTimeout(function() {
                            location.reload();
                        }, 2000);


                    },

                    error: function (err) {

                        if (err.status === 422) {
                            $.each(err.responseJSON.errors, function (i, error) {
                                var el = $(document).find('[name="'+i+'"]');
                                el.after($('<span class="valids" style="color: red;">'+error+'</span>'));
                            });
                        }

                        if (err.status === 500)
                        {
                            $('#error_message').html('<div class="alert alert-error">\n' +
                                '<button class="close" data-dismiss="alert">×</button>\n' +
                                '<strong>Error! '+err.responseJSON.error+'</strong>' +
                                '</div>');
                        }
                    }
                });
            })
        })
    </script>

    <script>
        $(document).ready(function(){

            $('#data-table').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                pagingType: "full_numbers",
                ajax: {
                    url: '{!!  route('stock.getData') !!}',
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'product_name', name: 'product_name'},
                    {data: 'attribute_name', name: 'attribute_name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

        });
    </script>

    <script>
        $(document).on('click','.deleteRecord', function(e){
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
                function(){
                    $.ajax({
                        type: "DELETE",
                        url: deleteFunction+'/'+id,
                        data: {id:id},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {

                            $('#data-table').DataTable().ajax.reload();

                            if (data.message){
                                toastr.options =
                                    {
                                        "closeButton" : true,
                                        "progressBar" : true
                                    };
                                toastr.success(data.message);
                            }
                        }
                    });
                });
        });
    </script>
@endpush
