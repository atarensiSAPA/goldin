@extends('layouts.dailyCreates-layout')

@section('content')
    <div class="container">
        <div class="row text-white">
            @forelse ($creates as $create)
                <div class="col-md-3 mt-4">
                    <a href="{{ route('dailyCreates.show', str_replace(' ', '_', $create->box_name)) }}" class="text-decoration-none text-dark">
                        <div class="card bg-transparent text-white border-0" id="card-{{ $create->box_name }}">
                            <div class="card-body position-relative">
                                <img src="{{ asset('images/creates/' . $create->box_img) }}" alt="" class="img-fluid radius-0">
                                <div class="overlay position-absolute top-0 start-0 pt-2 pl-2 radius-0 h-auto d-flex align-items-center">
                                    <b class="fs-5 fw-bold">Needs level {{ $create->level }}</b>
                                </div>                                
                                <div class="text-center text-white">
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
@endsection