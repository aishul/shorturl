<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    ShortUrl
                </div>
                @if (Session::has('noCode'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ Session::get('noCode') }}
                        <button type="button" class="close" id="btn" data-clipboard-text="{{ Session::get('noCode') }}" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('link.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                            <div class="input-group mb-3">
                                <input id="url" type="text" class="form-control" name="url" value="{{ old('url') }}" placeholder="type your url here" aria-label="type your url here" aria-describedby="basic-addon2" required autofocus>
                                @if ($errors->has('url'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('url') }}</strong>
                                    </span>
                                @endif
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Short The URL</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <a class="btn btn-outline-primary btn-block" href="{{ route('link.create') }}" role="button">Get Custom Code</a>
                </div>
                @if (Session::has('url'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Url Shortened
                        <button type="button" class="close" id="btn" data-clipboard-text="{{ (string)URL::current() }}/{{ Session::get('url') }}" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    Your Shortened Url <a href="{{ (string)URL::current() }}/{{ Session::get('url') }}"> {{ (string)URL::current() }}/{{ Session::get('url') }}</a>
                @endif
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>
