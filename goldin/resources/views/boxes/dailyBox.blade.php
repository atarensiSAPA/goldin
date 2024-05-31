@extends('layouts.dailyboxes-layout')

@section('content')
    <div class="container">
        <div class="row text-white">
            @forelse ($boxes as $box)
                <div class="col-md-3 mt-4">
                    <a href="{{ route('dailyboxes.show', str_replace(' ', '_', $box->box_name)) }}" class="text-decoration-none text-dark">
                        <div class="card bg-transparent text-white border-0" id="card-{{ $box->box_name }}">
                            <div class="card-body position-relative">
                                <img src="{{ asset('images/boxes/' . $box->box_img) }}" alt="" class="img-fluid radius-0 img-fixed-size-daily">
                                <div class="overlay position-absolute top-0 start-0 pt-2 pl-2 radius-0 h-auto d-flex align-items-center">
                                    <b class="fs-5 fw-bold">Needs level {{ $box->level }}</b>
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
@endsection