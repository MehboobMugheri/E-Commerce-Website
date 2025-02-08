@extends('Admin.layouts.app')


@section('content')

    <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">					
                    <div class="container-fluid my-2">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Update Sub Category</h1>
                            </div>
                            <div class="col-sm-6 text-right">
                                <a href="{{ route('sub-category.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.container-fluid -->
                </section>
                <!-- Main content -->
                <section class="content">
                    <!-- Default box -->
                    <div class="container-fluid">
                            <form action="" name="SubCategoryForm" id="SubCategoryForm">
                            <div class="card">
                                <div class="card-body">								
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="category">Category</label>
                                                <select name="category" id="category" class="form-control">
                                                    @if ($categories->isNotEmpty())
                                                    <option value="">select a category</option>
                                                        @foreach ($categories as $category)
                                                        <option {{ ($subCategories->category_id == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">
                                                            {{ $category->name }}
                                                        </option>
                                                        @endforeach 
                                                    @endif
                                                </select>
                                                <p></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" id="name" value="{{ $subCategories->name }}" class="form-control" placeholder="Name">
                                                <p></p>	
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="slug">Slug</label>
                                                <input type="text" name="slug" id="slug" value="{{ $subCategories->slug }}" class="form-control" placeholder="Slug">
                                                <p></p>	
                                            </div>
                                        </div>									
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="status">Status</label>
                                                <select name="status" id="status" class="form-control">
                                                <option {{ ($subCategories->status == 1) ? 'selected' : '' }} value="1">Active</option>    
                                                <option {{ ($subCategories->status == 0) ? 'selected' : '' }} value="0">Block</option>    
                                                </select>	
                                            </div>
                                        </div>									
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="showHome">Show On Sub Category</label>
                                                <select name="showHome" id="showHome" class="form-control">
                                                <option {{ ($subCategories->showHome == 'Yes') ? 'selected' : '' }} value="Yes">Yes</option>    
                                                <option {{ ($subCategories->showHome == 'No') ? 'selected' : '' }} value="No">No</option>    
                                                </select>	
                                            </div>
                                        </div>									
                                    </div>
                                </div>							
                            </div>
                            <div class="pb-5 pt-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('sub-category.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                            </div>
                    </form>
                    </div>
                    <!-- /.card -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

@endsection

@section('customJs')
    <script>

        $("#SubCategoryForm").submit(function(event){
            event.preventDefault();

            var element = $("#SubCategoryForm");
            $.ajax({
                url: '{{ route("sub-category.update",$subCategories->id) }}',
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){
                    if (response["status"] == true) {

                        window.location.href="{{ route('sub-category.index') }}";
                        
                        $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                        $("#slug").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    
                    }

                    // headers: {
                    // 		'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    // 	}
                    else{

                        if (response["notFound"] == true) {
                            window.location.href="{{ route('sub-category.index') }}";
                            return false;
                        }

                    var errors = response['errors'];
                    
                    if (errors['name']) {
                        $("#name").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['name']);
                    }else{
                        $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html("");
                    }

                    if (errors['slug']) {
                        $("#slug").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['slug']);
                    }else{
                        $("#slug").removeClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html("");
                    }

                    if (errors['category']) {
                        $("#category").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['category']);
                    }else{
                        $("#category").removeClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html("");
                    }
                }
                }, error:function(jqXHR, exception){
                    console.log('Somethig went wrong');
                    
                }
            })
        });

        $("#name").change(function(){
            element = $(this);
            $.ajax({
                url: '{{ route("getSlug") }}',
                type: 'get',
                data: {title: element.val()},
                dataType: 'json',
                success: function(response){
                    if (response["status"]  == true) {
                        $("#slug").val(response["slug"]);
                    }
                }
            });
        });
    </script>
@endsection