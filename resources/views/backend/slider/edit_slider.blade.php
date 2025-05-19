@extends('admin.master')

@section('admin')

<div class="content">

    <!-- Start Content-->
    <div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Edit Slider</h4>
            </div>

            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">Edit Slider</li>
                </ol>
            </div>
        </div>

        <!-- Form Validation -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Edit Slider</h5>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <form action="{{route('update.slider', ['id' => $slider->id])}}" method="post" class="row g-3" enctype="multipart/form-data">
                         @csrf   
                        
                         <input type="hidden" name="id" value="{{$slider->id}}">

                        <div class="col-md-6">
                                <label for="heading" class="form-label">Slider Heading</label>
                                <input name="heading" type="text" class="form-control" id="heading" value="{{$slider->heading}}" required="">
                            </div>
                        
                            
                            <div class="col-md-6">
                                <label for="link" class="form-label">Slider Link</label>
                                <input name="link" type="text" class="form-control" id="link" value="{{$slider->link}}" required="">
                            </div>
                            <div class="col-md-6">
                                <label for="description" class="form-label">Slider Description</label>
                                <input name="description" type="text" class="form-control" id="{{$slider->description}}" value="Mark" required="">
                            </div>
                            <div class="col-md-6">
                                <label for="image" class="form-label">Slider Image</label>
                                <input name="image" type="file" class="form-control" id="image" value="Mark" required="">
                            </div>
                            <div class="col-md-6">
                                <img src="{{ asset($slider->image)}}"
                                    class="rounded-circle avatar-xxl img-thumbnail float-start"
                                    alt="image profile">
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Save Changes</button>
                            </div>
                        </form>
                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col -->



        </div> <!-- container-fluid -->

    </div>

    @endsection