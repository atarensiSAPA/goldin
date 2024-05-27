@extends('layouts.minigames-selector')

@section('content')

    <div class="bigContainer">
        <div class="miniContainer">
            <div class="game-link">
                <a href="{{ route('black-jack') }}" class="text-decoration-none text-dark">
                    <div class="game-card" style="background-image: url('{{ asset('images/black_jack.png') }}');">
                        <div class="game-title">
                            Black Jack
                        </div>
                    </div>
                </a>
            </div>
            <div class="game-link">
                <a href="{{ route('3cups-1ball') }}" class="text-decoration-none text-dark">
                    <div class="game-card" style="background-image: url('{{ asset('images/3cups_1ball.jpg') }}');">
                        <div class="game-title">
                            3 cups 1 ball
                        </div>
                    </div>
                </a>
            </div>
        </div>
        {{-- <div class="game-link">
            <a href="{{ route('plinko') }}" class="text-decoration-none text-dark">
                <div class="game-card" style="background-image: url('{{ asset('images/plinko.jpg') }}');">
                    <div class="game-title">
                        Plinko
                    </div>
                </div>
            </a>
        </div> --}}
    </div>

@endsection