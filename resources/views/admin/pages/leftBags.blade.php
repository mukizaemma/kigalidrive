@extends('layouts.adminBase')


@section('content')


        <!-- Sidebar Start -->
@include('admin.includes.sidebar')
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            @include('admin.includes.navbar')
            <!-- Navbar End -->


                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="btn btn-primary">Left Bags Page</h2>

                                    <a href="" class="btn btn-secondary">Left Bags Requests</a>
                                </div>
                                <!-- ./card-header -->
                                <div class="card-body">

                                    <form action="{{ route('updateBags', $data->id) }}"
                                        method="POST"
                                        enctype="multipart/form-data"
                                        style="padding:20px;background:#f9f9f9;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,0.1);">

                                        @csrf

                                        {{-- Heading --}}
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">About Heading</label>
                                            <input type="text"
                                                name="heading"
                                                class="form-control"
                                                value="{{ old('heading', $data->heading) }}"
                                                required>
                                        </div>

                                        {{-- Description --}}
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">About Description</label>
                                            <textarea name="description"
                                                    rows="6"
                                                    class="form-control"
                                                    id="leftBags"
                                                    required>{{ old('description', $data->description) }}</textarea>
                                        </div>

                                        {{-- Image --}}
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">About Image</label><br>

                                            @if($data->image)
                                                <img src="{{ asset('storage/images/leftbags/' . $data->image) }}"
                                                    width="160"
                                                    class="mb-3 rounded shadow-sm">
                                            @endif

                                            <input type="file" name="image" class="form-control">
                                            <small class="text-muted">Leave empty to keep current image</small>
                                        </div>

                                        {{-- Submit --}}
                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-primary px-4">
                                                <i class="fa fa-save"></i> Save Changes
                                            </button>
                                        </div>

                                    </form>


                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->


                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->


        </div>
        <!-- Content End -->


        @include('admin.includes.footer')

 @endsection