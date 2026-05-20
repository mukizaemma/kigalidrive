<section class="room padding-top padding-bottom">
    <div class="container">
      <div class="common-header">
        <h2 style="font-family: 'Helvetica'" class="text-center" >Our Facilities</h2>
      </div>
      <div class="room__wrapper">
        <div class="row g-4">
          @foreach ($facilities as $facility )
          <div class="col-xl-4 col-md-6">
            <div class="room__item room__item--style3 bg--section-color h-100">
              <div class="room__item-inner">
                <div class="room__item-thumb">
                 <a href="{{ route('facility',['slug'=>$facility->slug]) }}"><img src="{{ asset('storage/images/facilities/' .$facility->image) }}" alt="Single Room with Balcony"></a>
                </div>
                <div class="room__item-content" style="text-align:center">
                      <h3 style="text-align:center"><a href="{{ route('room',['slug'=>$facility->slug]) }}">{{ $facility->title }}</a></h3>
                  <div class="room__item-body">
                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($facility->description), 100) }}</p>

                    <a href="{{ route('facility',['slug'=>$facility->slug]) }}" class="custom-btn custom-btn--bordered"><span>View More</span></a>
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