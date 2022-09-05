@extends('layouts.admin.master')

@section('page')
    Branch Type Edit
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
                <form action="" method="post" id="branch_type_edit">
                    @method('PUT')
                    @csrf

                    <input type="hidden" id="branch_type_id" value="{{ $branch_type->id }}">

                    <div class="form-group row">
                        <label for="branch_type_name" class="control-label">Branch Type Name</label>
                        <input type="text" value="{{ $branch_type->branch_type_name }}" name="branch_type_name" id="branch_type_name" class="form-control">
                    </div>

                    <div class="form-group">
                        <a href="{{ route('branch_type') }}" class="btn btn-warning">Back</a>
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

        $("#branch_type_edit").on("submit",function (e) {
            e.preventDefault();

            var id = $("#branch_type_id").val();
            $("#branch_type_edit").addClass('custom_disabled')

            var formData = new FormData( $("#branch_type_edit").get(0));

            $.ajax({
                url : "{{ route('branch_type.update','') }}/"+id,
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
