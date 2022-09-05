@extends('layouts.admin.master')

@section('page')
    Stock Edit
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
                    <form action="" method="post" id="stock_edit">
                        @method('PUT')
                        @csrf

                        <input type="hidden" name="" id="stock_id" value="{{ $stock->id }}">

                        <div class="form-group row">
                            <label for="product_id">Product Name</label>
                            <select name="product_id" id="product_id" class="form-control" required>
                                <option value="">Select Product</option>
                                @forelse ($products as $product)
                                    <option {{ $stock->product_id == $product->id ? 'selected' : ''}} value="{{ $product->id }}">{{ $product->name }}</option>
                                @empty
                                    <option value="">Data Not Found</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="form-group row">
                            <label for="attribute_id">Attribute Name</label>
                            <select name="attribute_id" id="attribute_id" class="form-control" required>
                                <option value="">Select Attribute</option>
                                @forelse ($attributes as $attribute)
                                    <option {{ $stock->attribute_id == $attribute->id ? 'selected' : ''}} value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                @empty
                                    <option value="">Data Not Found</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="form-group row">
                            <label for="quantity" class="control-label">Quantity</label>
                            <input type="number" value="{{ $stock->quantity }}" name="quantity" id="quantity" class="form-control">
                        </div>

                        <div class="form-group">
                            <a href="{{ route('stock') }}" class="btn btn-warning">Back</a>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {

            $("#stock_edit").on("submit",function (e) {
                e.preventDefault();
                $("#stock_edit").addClass('custom_disabled')

                var id = $("#stock_id").val();

                var formData = new FormData( $("#stock_edit").get(0));

                $.ajax({
                    url : "{{ route('stock.update','') }}/"+id,
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
                        setTimeout(function (){
                            location.reload();
                        }, 2000)

                        $('.form-group').find('.valids').hide();
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
@endpush
