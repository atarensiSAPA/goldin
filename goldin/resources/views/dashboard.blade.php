
<x-app-layout>

    {{-- MINIJUEGOS 
        <div style="display: flex; flex-direction: column; align-items: center;">
        <div style="padding: 120px 400px; display: flex; justify-content: space-between;">
            <div style="max-width: 1120px; margin: 0 auto; padding: 0 24px;">
                <div style="background-color: white; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border-radius: 1rem; width: 500px; height: 250px">
                    <div style="padding: 24px; color: #1a202c;">
                        
                    </div>
                </div>
            </div>
            <div style="max-width: 1120px; margin: 0 auto; padding: 0 24px;">
                <div style="background-color: white; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border-radius: 1rem; width: 500px; height: 250px">
                    <div style="padding: 24px; color: #1a202c;">
                        
                    </div>
                </div>
            </div>
        </div>
        <div style="max-width: 1120px; margin: -50px auto; padding: 0 24px;">
            <div style="background-color: white; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border-radius: 1rem; width: 500px; height: 250px">
                <div style="padding: 24px; color: #1a202c;">
                    
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Slider -->
    {{-- <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="https://via.placeholder.com/1200x400" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="https://via.placeholder.com/1200x400" class="d-block w-100" alt="...">
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
    </div> --}}

    <div class="container">
        <div class="row">
            @forelse ($creates as $create)
                <div class="col-md-3 mt-4">
                    <a href="{{ route('creates.show', $create->box_name) }}" class="text-decoration-none text-dark">
                        <div class="card bg-transparent text-white border-0" id="card-{{ $create->box_name }}">
                            <div class="card-body position-relative">
                                <img src="{{ asset('images/' . $create->box_img) }}" alt="" class="img-fluid radius-0">
                                <div class="overlay position-absolute top-0 start-0 pt-2 pl-2 radius-0 h-auto">
                                    <b class="coinText">{{ $create->cost }} 💸</b>
                                </div>
                                <div style="color: white; text-align: center;">
                                    <b>{{ $create->box_name }}</b>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <p>No creates found.</p>
            @endforelse
        </div>
    </div>

</x-app-layout>
