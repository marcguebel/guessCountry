<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Guess Country</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @vite(['resources/css/app.css', 'resources/js/jquery.js', 'resources/js/app.js'])
    </head>
    <body>
        <!-- Begin page content -->
        <main>
            <form data-action="{{ route('try') }}" method="POST" id="form">
                @csrf
                <h1>Devine le pays :</h1>
                <div>
                    <input type="text" id="country" placeholder="Pays" name="country">
                    <div class="countrys">
                        @foreach ($countrys as $country)
                            <p class="country" data-name="{{$country->name}}" style="display:none;">
                                {{ $country->flag }} {{ $country->name }}
                            </p>
                        @endforeach
                    </div>
                </div>
                <button type="submit">Valider</button>
            </form>
            <table id="line-container">
                
            </table>
        </main>
    </body>
</html>