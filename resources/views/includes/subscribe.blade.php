<div class="tp-cta__area mt-10" id="subscribe">
    <div class="tp-cta__bg wow tpfadeUp" data-wow-duration=".9s" data-wow-delay=".3s" data-background="assets/img/cta/cta-bg-3.jpg">
        <div class="container">
            <div class="row align-items-center">
                <h2 class="text-center"  style="color: #ad3303">Subscribe</h2>
                {{-- <p class="text-center"  style="color: #D9A409">
                    Subscribe to keep updated
                </p> --}}
                
                <div class="container">
                    <form class="form" action="{{ route('subscribe') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <div class="col">
                                <input type="text" class="form-control mb-1 mr-3" placeholder="First Name" name="fName" required="">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control mb-1 mr-3" placeholder="Last Name" name="lName">
                            </div>
                            <div class="col">
                                <input type="email" class="form-control mb-1 mr-3" placeholder="Your Email" name="email" required="">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <button class="tp-btn theme-2-bg wow tpfadeUp" type="submit" >Subscribe</button>
                            </div>
                        </div>
                    </form>
                    
                    
                    
                </div>
                
                
            </div>
        </div>
    </div>
</div>