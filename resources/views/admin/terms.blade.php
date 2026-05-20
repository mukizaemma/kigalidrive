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

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="btn btn-primary">Terms and Policies</h2>
                                    @if (session()->has('success'))
                                        <div class="arlert alert-success">
                                            <button class="close" type="button" data-dismiss="alert">X</button>
                                            {{ session()->get('success') }}
                                        </div>
                                    @endif

                                </div>
                                <!-- ./card-header -->
                                <div class="card-body">
                                    <form class="form" action="{{ route('saveTerms', $data->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-body">

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label for="summernote" class="form-label">Our Privacy Polocy</label>
                                                    <textarea id="privacy" rows="5" class="form-control" name="privacy" >{!! $data->privacy !!}</textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label for="summernote" class="form-label">Privacy Details</label>
                                                    <textarea id="privacy_details" rows="5" class="form-control" name="privacy_details" >{!! $data->privacy_details ?? '' !!}</textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label for="summernote" class="form-label">Cookies Policy</label>
                                                    <textarea id="cookies" rows="5" class="form-control" name="cookies" >{!! $data->cookies ?? '' !!}</textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label for="summernote" class="form-label">Refunds Policy</label>
                                                    <textarea id="refunds" rows="5" class="form-control" name="refunds" >{!! $data->refunds ?? '' !!}</textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label for="summernote" class="form-label">Booking Cancellation</label>
                                                    <textarea id="booking_cancellation" rows="5" class="form-control" name="booking_cancellation" >{!! $data->booking_cancellation ?? '' !!}</textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label for="summernote" class="form-label">Listing Commission</label>
                                                    <textarea id="listing_commission" rows="5" class="form-control" name="listing_commission" >{!! $data->listing_commission ?? '' !!}</textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label for="summernote" class="form-label">Payment Methods</label>
                                                    <textarea id="payment_methods" rows="5" class="form-control" name="payment_methods" >{!! $data->payment_methods ?? '' !!}</textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label for="summernote" class="form-label">Our Return Polocy</label>
                                                    <textarea id="return" rows="5" class="form-control" name="return" >{!! $data->return !!}</textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label for="summernote" class="form-label">Our Terms and conditions</label>
                                                    <textarea id="terms" rows="5" class="form-control" name="terms" >{!! $data->terms !!}</textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label for="summernote" class="form-label">Our Support Policies</label>
                                                    <textarea id="support" rows="5" class="form-control" name="support" >{!! $data->support !!}</textarea>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-actions mt-5">
                                            <button type="submit" class="btn btn-primary text-black">
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
            <!-- /.content -->

        </div>
        <!-- Content End -->




 @endsection