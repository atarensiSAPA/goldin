
<x-app-layout>

    <div class="container">
        <div class="row text-white">
            @forelse ($boxes as $box)
                <div class="col-md-3 mt-4">
                    <a href="{{ route('boxes.show', str_replace(' ', '_', $box->box_name)) }}" class="text-decoration-none text-dark">
                        <div class="card bg-transparent text-white border-0" id="card-{{ $box->box_name }}">
                            <div class="card-body position-relative">
                                <img src="{{ asset('images/boxes/' . $box->box_img) }}" alt="" class="img-fluid radius-0 img-fixed-size">
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
