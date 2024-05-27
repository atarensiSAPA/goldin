
<x-app-layout>
    
    {{-- <!-- Slider -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('images/boxes/the_villain.png') }}" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('images/boxes/the_heroe.png') }}" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="https://via.placeholder.com/1200x400" class="d-block w-100" alt="...">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.carousel').carousel();
        });
    </script> --}}

    <div class="container">
        <div class="row text-white">
            @forelse ($boxes as $box)
                <div class="col-md-3 mt-4">
                    <a href="{{ route('boxes.show', str_replace(' ', '_', $box->box_name)) }}" class="text-decoration-none text-dark">
                        <div class="card bg-transparent text-white border-0" id="card-{{ $box->box_name }}">
                            <div class="card-body position-relative">
                                <img src="{{ asset('images/boxes/' . $box->box_img) }}" alt="" class="img-fluid radius-0">
                                <div class="overlay position-absolute top-0 start-0 pt-2 pl-2 radius-0 h-auto d-flex align-items-center">
                                    <b class="fs-5 fw-bold">{{ $box->cost }}</b>
                                    <img src="{{ asset('images/user_coin.png') }}" alt="coin" width="30" height="30" class="ms-2">
                                </div>                                
                                <div class="text-center text-white">
                                    <b>{{ $box->box_name }}</b>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <p>No boxes found.</p>
            @endforelse
        </div>
    </div>

</x-app-layout>
