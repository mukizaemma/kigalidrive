@extends('layouts.frontbase')

@section('content')


<!--==============================
Gallery Area  
==============================-->
    <div class="overflow-hidden space bg-smoke" id="gallery-sec">
        <div class="container-fuild">
            <div class="title-area mb-30 text-center">
                <h2 class="sec-title">A truly exceptional experience</h2>
            </div>
                <div class="row gy-4 gallery-row4">
                    @foreach($gallery as $item)
                        <div class="col-auto">
                            <div class="gallery-box style5">
                                <div class="gallery-img global-img">
                                    @php
                                        $folder = $item->type === 'facility' ? 'facilities' : 'rooms';
                                    @endphp
                                    <img src="{{ asset('storage/images/' . $folder . '/' . $item->image) }}" alt="gallery image">
                                    <a href="{{ asset('storage/images/' . $folder . '/' . $item->image) }}" class="icon-btn popup-image">
                                        <i class="fal fa-magnifying-glass-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Pagination Links -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $gallery->links() }}
                </div>

        </div>
    </div>

@endsection