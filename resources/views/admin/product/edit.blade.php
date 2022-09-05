@extends('layouts.admin.master')

@section('page')
    Products Edit
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

        <div class="card card-default">
            <div class="card-header">@yield('page')</div>

            <div class="card-body">
                <form action="" method="post" id="product_edit">
                    @method('PUT')
                    @csrf

                    <input type="hidden" name="" id="product_id" value="{{ $product->id }}">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group row">
                                <label for="category_id" class="control-label">Catgeory</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @forelse ($category as $cat)
                                        <option value="{{ $cat->id }}" @if ($product->category_id == $cat->id)
                                            selected
                                        @endif>{{ $cat->name }}</option>
                                    @empty
                                        <option value="">No Data Found</option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" value="{{ $product->name }}" name="name" id="name" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">Title</label>
                                <input type="text" value="{{ $product->title }}" name="title" id="title" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">Brand</label>
                                <input type="text" value="{{ $product->brand }}" name="brand" id="brand" class="form-control">
                            </div>

{{--                            <div class="form-group">--}}
{{--                                <label for="Description" class="control-label">Product Description</label>--}}
{{--                                <textarea name="description" id="description" class="textarea" placeholder="Place some text here" style="width: 100%; height: 400px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ $product->description }}</textarea>--}}
{{--                            </div>--}}

{{--                            <div class="form-group">--}}
{{--                                <label for="specification" class="control-label">Product specification</label>--}}
{{--                                <textarea name="specification" id="specification" class="textarea" placeholder="Place some text here" style="width: 100%; height: 400px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ $product->specification }}</textarea>--}}
{{--                            </div>--}}
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Date</label>
                                <input type="date" value="{{ $product->date }}" name="date" id="date" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="vat_sc_oh">VAT+SC+OH</label>
                                <input type="number" value="{{ $product->vat_sc_oh }}" name="vat_sc_oh" id="vat_sc_oh" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">Unit Price</label>
                                <input type="text" value="{{ $product->unit_price }}" name="unit_price" id="unit_price" class="form-control">
                            </div>

{{--                            <div class="form-group">--}}
{{--                                <label for="">Image</label>--}}
{{--                                <input type="file" name="image" id="image" class="form-control">--}}
{{--                                <br><br>--}}
{{--                                @if (!empty($product->image))--}}
{{--                                    <div>--}}
{{--                                        <img src="{{ asset('assets/admin/uploads/products/small/'.$product->image) }}" alt="">--}}
{{--                                    </div>--}}
{{--                                @else--}}
{{--                                    <div id="image-holder"></div>--}}
{{--                                @endif--}}
{{--                            </div>--}}

{{--                            <div class="form-group" style="margin-top: 50px">--}}

{{--                                <label for="publish" style="margin-right: 30px">--}}
{{--                                    <input type="checkbox" name="publish" id="publish" @if($product->publish == 1) checked @endif> Publish--}}
{{--                                </label>--}}

{{--                                <label for="feature">--}}
{{--                                    <input type="checkbox" name="feature" id="feature" @if($product->feature == 1) checked @endif> Feature--}}
{{--                                </label>--}}

{{--                            </div>--}}
                        </div>
                    </div>

                    <div class="form-group">
                        <a href="{{ route('product') }}" class="btn btn-warning">Back</a>
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
    $("#image").on('change', function () {

        if (typeof (FileReader) != "undefined") {

            var image_holder = $("#image-holder");
            image_holder.empty();

            var reader = new FileReader();
            reader.onload = function (e) {
                $("<img />", {
                    "src": e.target.result,
                    "class": "thumb-image",
                    "width": "100px",
                    "height": "100px"
                }).appendTo(image_holder);

            }
            image_holder.show();
            reader.readAsDataURL($(this)[0].files[0]);
        } else {
            alert("This browser does not support FileReader.");
        }
    });

    $(document).ready(function () {

        $("#product_edit").on("submit",function (e) {
            e.preventDefault();
            $("#product_edit").addClass('custom_disabled')

            var id = $("#product_id").val();

            var formData = new FormData( $("#product_edit").get(0));

            $.ajax({
                url : "{{ route('product.update','') }}/"+id,
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
