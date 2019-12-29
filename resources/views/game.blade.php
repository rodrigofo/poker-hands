@extends('layouts.app')

@inject('poker', 'App\Services\PokerService')

@section('content')
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Game #{{ $game->id }}

                        <a href="{{ URL::route('home') }}" class="float-right">
                            &lt; back
                        </a>
                    </div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Player 1 Hand</th>
                                <th>Player 2 Hand</th>
                                <th>Winner Move</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($game->moves as $move)
                                <tr>
                                    <td>
                                        <code @if($move->winner === '1')class="font-weight-bold"@endif>
                                            {{ $move->hand_1 }}
                                        </code>
                                    </td>

                                    <td>
                                        <code @if($move->winner === '-1')class="font-weight-bold"@endif>
                                            {{ $move->hand_2 }}
                                        </code>
                                    </td>

                                    <td>
                                        {{ $move->score === '0' ? 'TIE' : $poker::HANDS[$move->score] }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
