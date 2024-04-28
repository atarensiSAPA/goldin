
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

    @forelse ($creates as $create)
        <!-- I want you list all the fields of creats -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $create->title }}</h4>
                        </div>
                        <div class="card-body">
                            <p>{{ $create->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
    @endforelse
</x-app-layout>
