@extends('layouts.plinko-layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col text-center">
                <canvas id="plinkoCanvas" width="800" height="600"></canvas>
                <button id="dropButton" class="btn btn-primary mt-3">Drop</button>
            </div>
        </div>
    </div>
@endsection