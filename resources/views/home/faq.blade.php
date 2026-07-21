@extends('layouts.app')

@section('content')

<div class="kama-faq-page">

    <!-- HERO -->
    <section class="kama-faq-hero">

        <div class="container">

            <span>
                <i class="bi bi-question-circle"></i>
                Centre d'aide KaMa
            </span>

            <h1>
                Questions fréquentes
            </h1>

            <p>
                Retrouvez les réponses aux questions les plus courantes concernant
                l'utilisation de KaMa.
            </p>

        </div>

    </section>



    <!-- FAQ -->
    <section class="kama-faq-section">

        <div class="container">

            <div class="kama-faq-layout">

                <!-- =========================
                     CATEGORIES
                ========================== -->

                <div class="kama-faq-categories">

                    <h3>
                        Catégories
                    </h3>

                    @foreach($categories as $category)

                        <button
                            class="faq-category {{ $loop->first ? 'active' : '' }}"
                            data-category="category-{{ $category->id }}">

                            <i class="bi {{ $category->icon }}"></i>

                            {{ $category->name }}

                        </button>

                    @endforeach

                </div>





                <!-- =========================
                     FAQ CONTENT
                ========================== -->

                <div class="kama-faq-content">

                    @foreach($categories as $category)

                        <div
                            class="faq-group {{ $loop->first ? 'active' : '' }}"
                            id="category-{{ $category->id }}">


                            <div class="accordion"
                                 id="accordion{{ $category->id }}">


                                @foreach($category->faqs as $faq)

                                    <div class="accordion-item">

                                        <h2 class="accordion-header"
                                            id="heading{{ $faq->id }}">

                                            <button
                                                class="accordion-button {{ !$loop->first ? 'collapsed' : '' }}"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $faq->id }}"
                                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                                aria-controls="collapse{{ $faq->id }}">

                                                {{ $faq->question }}

                                            </button>

                                        </h2>



                                        <div
                                            id="collapse{{ $faq->id }}"
                                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                            data-bs-parent="#accordion{{ $category->id }}">


                                            <div class="accordion-body">

                                                {!! nl2br(e($faq->answer)) !!}

                                            </div>

                                        </div>

                                    </div>

                                @endforeach


                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    </section>

</div>

<script>
    document.querySelectorAll('.faq-category').forEach(button => {

    button.addEventListener('click', function () {

        document.querySelectorAll('.faq-category').forEach(btn => {
            btn.classList.remove('active');
        });

        this.classList.add('active');

        document.querySelectorAll('.faq-group').forEach(group => {
            group.classList.remove('active');
        });

        document
            .getElementById(this.dataset.category)
            .classList.add('active');

    });

});
</script>

@endsection