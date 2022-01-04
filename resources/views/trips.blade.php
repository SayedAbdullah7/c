<?php header('Access-Control-Allow-Origin: *'); ?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        .sensors span {
            font-size: 0.85rem;
        }

    </style>
</head>

<body class="antialiased">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                Tawasolmap
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->



                </ul>
            </div>
        </div>
    </nav>
    <div id="container" class="container">


        <table class="table">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">name</th>
                    <th scope="col">image</th>
                    <th scope="col">sensors </th>
                    <th scope="col">speed</th>
                    <th scope="col">location </th>
                    <th scope="col">address</th>
                    <th scope="col">last trip</th>
                </tr>
            </thead>
            <tbody id="tbody">
                @foreach ($items as $index =>$item)
                    <tr>
                        <td>{{ $item['id'] }}</td>
                        <td>{{ $item['nm'] }}</td>
                        <td><img src="http://gps.tawasolmap.com{{$item['uri']}}?side={{$sid}}" alt="" srcset=""></td>
                        <td>
                            @foreach ($item['sens'] as $key => $s)
                                <span>{{ $s['n'] . '(' . $s['t'] . ') = ' .$sensors_values[$index][$key]. '(' . $s['m'] . ')'  }}</span><br>
                            @endforeach
                        </td>
                        <td>{{ $item['pos']['s'] }}</td>
                        <td class="col-md-2">Y: {{$item['pos']['y'].", X:". $item['pos']['x']}}</td>
                        <th>{{file_get_contents('http://gps.tawasolmap.com/gis_geocode?coords=[{"lon":'.$item["pos"]["x"].',"lat":'.$item["pos"]["y"].'}]&flags=1&uid=62289')}}</th>
                        <th> @if ($last_trip[$index] != null)
                        {{$last_trip[$index]['distance']}}
                            @endif
                            {{-- {{$last_trip[$index]['format']}} --}}
                        </th>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // execute when DOM ready
        // $(document).ready(function() {

        $.get('http://gps.tawasolmap.com/gis_geocode?coords=[{"lon":24.7828783,"lat":46.6375516}]&flags=1&uid=62289',
            function(data) {
                // $( ".result" ).html( data );
                console.log(data);
                // alert( "Load was performed." );
            });

        var container = $('#container');

        // $.ajax({
        //     type : 'get',
        //     url : '{{ route('api') }}',
        //     // data:{  'type':type,
        //     // 'range':range},
        //     // dataType:'json',
        //     success:function(data){
        //         console.log('run');
        //         console.log('{{ route('api') }}');
        //         console.log(data);
        //         container.html(data);
        //     },
        //     error: function(data){
        //         var errors = data.responseJSON;
        //         console.log(errors);
        //     }
        //     });

        // });
    </script>



    <script type="text/javascript">
        // $.ajaxSetup({
        //     headers: {
        //         'csrftoken': '{{ csrf_token() }}'
        //     }
        // });
    </script>

</body>

</html>
