@if($sponsoredBooks->count())

<section class="pb-2 pb-lg-5">

    <div class="container">

        <!-- Slider START -->
        <div class="tiny-slider arrow-round arrow-blur arrow-hover">

            <div class="tiny-slider-inner"
                data-autoplay="true"
                data-arrow="true"
                data-edge="2"
                data-dots="false"
                data-items-xl="3"
                data-items-lg="2"
                data-items-md="1">


                @foreach($sponsoredBooks as $sponsored)

                    <!-- Slider item -->
                    <div>

                        <div class="card sponsored-book-card border-0">

                            <span class="ad-badge">
                                Sponsorisé
                            </span>


                            <div class="row g-0 align-items-center">


                                <!-- Image -->
                                <div class="col-sm-6">

                                    <img 
                                    src="{{ asset('storage/'.$sponsored->book->cover_image) }}"
                                    class="card-img rounded-0"
                                    alt="{{ $sponsored->book->title }}">

                                </div>



                                <!-- Title and content -->
                                <div class="col-sm-6">

                                    <div class="card-body px-3">


                                        <h6 class="card-title">

                                            <a href="{{ route('books.show',$sponsored->book) }}"
                                            class="stretched-link">

                                                {{ $sponsored->book->title }}

                                            </a>

                                        </h6>



                                        <p class="mb-0 author">

                                            Par 
                                            {{ $sponsored->book->author->firstname }}
                                            {{ $sponsored->book->author->lastname }}

                                        </p>


                                    </div>

                                </div>


                            </div>

                        </div>

                    </div>

                @endforeach


            </div>

        </div>
        <!-- Slider END -->


    </div>

</section>

@endif