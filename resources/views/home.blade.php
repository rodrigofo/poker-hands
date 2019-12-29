@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (session('upload_status'))
                            <div class="alert alert-{{ session('upload_status') }}" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif

                        You are logged in!

                        <form action="{{ URL::route('upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <input type="file" name="game">
                            <button type="submit">Upload new game</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if ($games->count())
            <div class="row justify-content-center pb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Games</div>

                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Game</th>
                                    <th>Player 1 Wins</th>
                                    <th>Player 2 Wins</th>
                                    <th>Ties</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($games as $game)
                                    <tr>
                                        <td>
                                            <a href="{{ URL::route('game', ['game' => $game]) }}">Game #{{ $game->id }}</a>
                                        </td>

                                        <td>{{ $game->player1Wins()->count() }}</td>
                                        <td>{{ $game->player2Wins()->count() }}</td>
                                        <td>{{ $game->ties()->count() }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
