@extends('Admin.layouts.app')

@section('content')
   <!-- Content Wrapper. Contains page content -->
   <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
       <form action="" method="POST" name="productForm" id="productForm">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">								
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" value="{{ $products->title }}" class="form-control" placeholder="Title">
                                            <p class="error"></p>	
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="slug">Slug</label>
                                            <input type="text" readonly name="slug" id="slug" value="{{ $products->slug }}" class="form-control" placeholder="Slug">	
                                    <p class="error"></p>	
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description"  cols="30" rows="10" class="summernote" placeholder="Description">{{ $products->description }}</textarea>
                                        </div>
                                    </div>                                            
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>								
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">    
                                        <br>Drop files here or click to upload.<br><br>                                            
                                    </div>
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>								
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" name="price" id="price" value="{{ $products->price }}" class="form-control" placeholder="Price">
                                            <p class="error"></p>	
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="text" name="compare_price" id="compare_price" value="{{ $products->compare_price }}" class="form-control" placeholder="Compare Price">
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                            </p>	
                                        </div>
                                    </div>                                            
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>								
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" name="sku" id="sku" value="{{ $products->sku }}" class="form-control" placeholder="sku">	
                                    <p class="error"></p>	
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" value="{{ $products->barcode }}" class="form-control" placeholder="Barcode">	
                                        </div>
                                    </div>   
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="track_qty" value="No">
                                                <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" {{ ($products->track_qty == 'Yes') ? 'checked' : '' }}>
                                            <p class="error"></p>	
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty" value="{{ $products->qty }}" class="form-control" placeholder="Qty">	
                                        </div>
                                    </div>                                         
                                </div>
                            </div>	                                                                      
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Product status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option {{ ($products->status == 1) ? 'selected' : '' }} value="1">Active</option>
                                        <option {{ ($products->status == 0) ? 'selected' : '' }} value="0">Block</option>
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="card">
                            <div class="card-body">	
                                <h2 class="h4  mb-3">Product category</h2>
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a category</option>
                                        @if ($categories->isNotEmpty())
                                        @foreach ($categories as $category )                                        
                                            <option {{ ($products->category_id == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{$category->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="error"></p>	
                                </div>
                                <div class="mb-3">
                                    <label for="sub_category">Sub category</label>
                                    <select name="sub_category" id="sub_category" class="form-control">
                                        <option value="">select a Sub Category</option>
                                        @if ($subCategories->isNotEmpty())
                                        @foreach ($subCategories as $subCategory )                                        
                                            <option {{ ($products->subCategory_id == $subCategory->id) ? 'selected' : '' }} value="{{ $subCategory->id }}">{{$subCategory->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="brand" id="brand" class="form-control">
                                        <option value="">Select a Brand</option>
                                        @if ($brands->isNotEmpty())
                                        @foreach ($brands as $brand)
                                            <option {{ ($products->brand_id == $brand->id) ? 'selected' : '' }} value="{{ $brand->id }}">{{$brand->name}}</option>
                                                
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Featured product</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option {{ ($products->is_featured == 'No') ? 'selected' : '' }} value="No">No</option>
                                        <option {{ ($products->is_featured == 'Yes') ? 'selected' : '' }} value="Yes">Yes</option>                                                
                                    </select>
                                    <p class="error"></p>	
                                </div>
                            </div>
                        </div>                                 
                    </div>
                </div>
                
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
       </form>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('customJs')
    <script>
        $("#title").change(function(){
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

        $("#productForm").submit(function(event){
            event.preventDefault();
        
            var formArray = $(this).serializeArray();
            $("button[type = submit]").prop('disabled',true);
            
            $.ajax({
                url: '{{ route("products.update",$products->id) }}',
                type: 'put',
                data: formArray ,
                dataType: 'json',
                success: function(response) {
                    $("button[type = submit]").prop('disabled',false);
                    if (response['status'] == true) {

                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type = 'text'], select").removeClass('is-invalid');
                        window.location.href="{{ route('products.index') }}";
                        
                    }else{
                        // var errors = response['errors'];
                        // if (errors['title']) {
                        //     $("#title").addClass('is-invalid')
                        //     .siblings('p')
                        //     .addClass('invalid-feedback')
                        //     .html(errors['title']);
                        // }else{
                        //     $("#title").addClass('is-invalid')
                        //     .siblings('p')
                        //     .removeClass('invalid-feedback')
                        //     .html("");
                        // }

                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type = 'text'], select").removeClass('is-invalid');

                            $.each(errors,function(key,value){
                                
                                $(`#{key}`).addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(value);
                                });

                    }
                },
                error: function(){
                    console.log('Something Went Wrong');
                    
                }

            });
        });

        $("#category").change(function(){
            var category_id = $(this).val();
            $.ajax({
                url: '{{ route("productSubCategotries.index") }}',
                type: 'get',
                data: {category_id:category_id},
                dataType: 'json',
                success: function(response) {
                    // console.log(response);
                    $("#sub_category").find("option").not(":first").remove();
                    $.each(response["subCategories"],function(key,item){
                        $("#sub_category").append(`<option value= '${item.id}'>${item.name}</option>`)
                    })
                },
                error: function(){
                    console.log('Something Went Wrong');
                    
                }

            });
        });

        Dropzone.autoDiscover = false;    
            const dropzone = $("#image").dropzone({ 
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url:  "{{ route('temp-images.create') }}",
            maxFiles: 1,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, success: function(file, response){
                $("#image_id").val(response.image_id);
                //console.log(response)
            }
        });
    </script>
@endsection