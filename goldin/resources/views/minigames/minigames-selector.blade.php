@extends('layouts.minigames-selector')

@section('content')

    <div style="display: flex; flex-direction: column; align-items: center;">
        <div style="padding: 120px 400px; display: flex; justify-content: space-between;">
            <div style="max-width: 1120px; margin: 0 auto; padding: 0 24px;">
                <a href="{{ route('black-jack') }}" class="text-decoration-none text-dark">
                    <div style="background-image: url('{{ asset('images/black-jack.png') }}'); overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border-radius: 1rem; width: 500px; height: 250px; display: flex; justify-content: center; align-items: center;">
                        <div style="background-color: rgba(0, 0, 0, 0.8); padding: 10px; color: white; text-align: center; border-radius: 1rem; font-size: 24px;">
                            Black Jack
                        </div>
                    </div>
                </a>
            </div>
            <div style="max-width: 1120px; margin: 0 auto; padding: 0 24px;">
                <a href="{{ route('3cups-1ball') }}" class="text-decoration-none text-dark">
                    <div style="background-image: url('{{ asset('images/3cups-1ball.jpg') }}'); overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border-radius: 1rem; width: 500px; height: 250px; display: flex; justify-content: center; align-items: center;">
                        <div style="background-color: rgba(0, 0, 0, 0.8); padding: 10px; color: white; text-align: center; border-radius: 1rem; font-size: 24px;">
                            3 cups 1 ball
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div style="max-width: 1120px; margin: -50px auto; padding: 0 24px;">
            <a href="{{ route('plinko') }}" class="text-decoration-none text-dark">º
                <div style="background-image: url('{{ asset('images/plinko.jpg') }}'); overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border-radius: 1rem; width: 500px; height: 250px; display: flex; justify-content: center; align-items: center;">
                    <div style="background-color: rgba(0, 0, 0, 0.8); padding: 10px; color: white; text-align: center; border-radius: 1rem; font-size: 24px;">
                        Plinko
                    </div>
                </div>
            </a>
        </div>
    </div>

@endsection