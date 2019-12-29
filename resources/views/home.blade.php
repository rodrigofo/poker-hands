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

                        <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <input type="file" name="game">
                            <button type="submit">Upload new game</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        @if ($games->count())
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Games</div>

                        <div class="card-body">
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
