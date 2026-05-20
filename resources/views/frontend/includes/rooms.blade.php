<section class="room padding-top padding-bottom">
    <div class="container">
      <div class="common-header">
        <h2 style="font-family: 'Helvetica'" class="text-center" >Stay with us</h2>
      </div>
      <div class="room__wrapper">
        <div class="row g-4">
          @foreach ($rooms as $room )
          <div class="col-xl-4 col-md-6">
            <div class="room__item room__item--style3 bg--section-color h-100">
              <div class="room__item-inner">
                <div class="room__item-thumb">
                 <a href="{{ route('room',['slug'=>$room->slug]) }}"><img src="{{ asset('storage/images/rooms/' .$room->image) }}" alt="Single Room with Balcony"></a>
                </div>
                <div class="room__item-content" style="text-align:center">
                      <h3 style="text-align:center"><a href="{{ route('room',['slug'=>$room->slug]) }}">{{ $room->title }}</a></h3>
                  <div class="room__item-body">
                    <p>{!! $room->description !!}</p>
                    <a href="{{ route('room',['slug'=>$room->slug]) }}" class="custom-btn custom-btn--bordered"><span>View Details</span></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        
        </div>
      </div>
    </div>
  </section>