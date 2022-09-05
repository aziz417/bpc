@extends('layouts.admin.master')

@section('page')
    Branch Edit
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
                <form action="" method="post" id="branch_edit">
                    @method('PUT')
                    @csrf

                    <input type="hidden" name="" id="branch_id" value="{{ $branch->id }}">

                    <div class="form-group row">
                        <label for="">Branch Type</label>
                        <select name="branch_type_id" id="branch_type_id" class="form-control">
                            <option value="">Select Branch Type</option>
                            @forelse ($branch_type as $bt)
                                <option value="{{ $bt->id }}" @if ($branch->branch_type_id == $bt->id)
                                    selected
                                @endif>{{ $bt->branch_type_name }}</option>
                            @empty
                                <option value="">Data Not Found</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="control-label">Branch Name</label>
                        <input type="text" value="{{ $branch->name }}" name="name" id="name" class="form-control">
                    </div>

                    <div class="form-group row">
                        <label for="address" class="control-label">Address</label>
                        <input type="text" value="{{ $branch->address }}" name="address" id="address" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <a href="{{ route('branch') }}" class="btn btn-warning">Back</a>
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

        $("#branch_edit").on("submit",function (e) {
            e.preventDefault();

            var id = $("#branch_id").val();
            $("#branch_edit").addClass('custom_disabled')

            var formData = new FormData( $("#branch_edit").get(0));

            $.ajax({
                url : "{{ route('branch.update','') }}/"+id,
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
