@extends('layouts.admin.master')

@section('page')
    User Create
@endsection

@push('css')

@endpush

@section('content')
<div class="row">
    <div class="col-md-12">

        <div id="success_message"></div>

        <div id="error_message"></div>

        <div class="card card-primary">
            <div class="card-header">@yield('page')</div>

            <div class="card-body">
                <form action="" method="post" id="user_post">
                    @csrf

                    <div class="form-group row">
                        <label for="name" class="control-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>

                    <div class="form-group row">
                        <label for="email" class="control-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>

                    <div class="form-group row">
                        <label for="phone" class="control-label">Phone</label>
                        <input type="number" name="phone" id="phone" class="form-control">
                    </div>

                    <div class="form-group row">
                        <label for="password" class="control-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>

                    <div class="form-group row">
                        <label for="role" class="control-label">Role</label>
                        <select name="role" id="role" class="form-control">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group row">
                        <label for="branch_type_id" class="control-label">Branch Type</label>
                        <select name="branch_type_id" id="branch_type_id" class="form-control" onchange="getBranch(this)">
                            <option value="">Select Branch Type</option>
                            @foreach($branch_types as $branch_type)
                                <option value="{{ $branch_type->id }}">{{ $branch_type->branch_type_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group row">
                        <label for="branch_id" class="control-label">Branch</label>
                        <select name="branch_id" id="branch_id" class="form-control">

                        </select>
                    </div>

                    <div class="form-group row">
                        <label for="category" class="control-label">Category</label>
                        <select name="category_id" id="category" class="form-control">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <a href="{{ route('user') }}" class="btn btn-warning">Back</a>
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
        // get branch type wise branch
        function getBranch(event){
            var options = `<option selected disabled value="">Select Branch</option>`;
            $("#branch_id").html(options)
            let branch_type_id = $(event).val()

            $.get("{{ route('branch.type.branches') }}", { id: branch_type_id }, function(response){
                if(response){
                    for(var i = 0;  i<response.length; i++){
                        options+= `<option style="text-transform: capitalize;" value="${response[i].id}">${response[i].name}</option>`
                    }
                    $("#branch_id").html(options)
                }
            });
        }
    </script>
<script>
    $(document).ready(function () {
        $("#user_post").on("submit", function (e) {
            e.preventDefault();

            var formData = $("#user_post").serializeArray();

            $.ajax({
                url: "{{ route('user.store') }}",
                type: "POST",
                data: $.param(formData),
                dataType: "json",
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
