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

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{$data['user']['nm'].' | '.$data['user']['id']}}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <div class="container">

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">name</th>
                    <th scope="col">image</th>
                    <th scope="col">sensors </th>
                    <th scope="col">location </th>
                    <th scope="col">speed</th>
                    <th scope="col">address</th>
                </tr>
            </thead>
            <tbody id="tbody">
            </tbody>
        </table>
    </div>
    {{-- Select unit: <select id="units"><option></option></select>
        <div id="log"></div> --}}
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/wialon2.js') }}"></script>
    <script>
        function notification(text) {
            $("#log").prepend(text + "<br/>");
        }


        function init() { // Execute after login succeed
            console.log('int run');
            var sess = wialon.core.Session.getInstance(); // get instance of current Session
            // flags
            var flags = wialon.item.Item.dataFlag.base | wialon.item.Unit.dataFlag.sensors | wialon.item.Unit.dataFlag
                .lastMessage;
            sess.loadLibrary("itemIcon"); // load Icon Library
            sess.loadLibrary("unitSensors"); // load Sensor Library
            sess.updateDataFlags( // load items to current session
                [{
                    type: "type",
                    data: "avl_unit",
                    flags: flags,
                    mode: 0
                }], // Items specification
                function(code) { // updateDataFlags callback
                    if (code) {
                        notification(wialon.core.Errors.getErrorText(code));
                        return;
                    } // exit if error code

                    // get loaded 'avl_unit's items
                    var units = sess.getItems("avl_unit");
                    if (!units || !units.length) {
                        notification("Units not found");
                        return;
                    } // check if units found

                    for (var i = 0; i < units.length; i++) { // construct Select object using found units
                        var u = units[i]; // current unit in cycle
                        var id = u.getId();
                        if ($('#tbody').find('#' + id).length > 0) {
                            updateUnitInfo(id);
                        } else {
                            getUnitInfo(id);
                        }
                    }
                }
            );

        }

        function getUnitInfo(id) { // print information about  Unit
            var unit = wialon.core.Session.getInstance().getItem(id); // get unit by id
            var sens = unit.getSensors(); // get unit's sensors
            var sensorRow = ' ';
            // sensors information
            for (var i in sens) { // forloop sensors
                var s = sens[i];
                // calculate sensor value
                var result = unit.calculateSensorValue(s, unit.getLastMessage());
                if (result == -348201.3876) result = "N/A"; // compare result with invalid sensor value constant
                // print sensor info
                sensorRow += "<span>" + s.n + " (" + s.t + ") " + "=" + result + " (" + s.m + ")</span><br>";
            }
            //  unit information
            var text = "<tr id=" + id + "><th>" + id + "</th><th class='col-md-2 name'>" + unit.getName() +
            "</th>"; // get unit name
            var icon = unit.getIconUrl(32); // get unit Icon url
            if (icon) text += "<th class=' img'><img class='icon' src='" + icon +
            "' alt='icon'/></th>"; // add icon to message
            var pos = unit.getPosition(); // get unit position
            //  position data
            text += "<th class='col-md-4 sensors'>" + sensorRow + "</th>" +
                "<th class='postion'>Y" + pos.y + ", X:" + pos.x + "</th>" +
                "<th class='speed'>" + pos.s + "</th>"
            // find unit location using coordinates and show content
            function add(add) {
                $('#' + id + ' .address').html(add);
            }
            var s = wialon.util.Gis.getLocations([{
                lon: pos.x,
                lat: pos.y
            }], function(code, address) {
                $("#tbody").prepend(text + "<th class='col-md-3 address'>" + address + "</th>"); //show content
            });

        }

        function updateUnitInfo(id) { // print information about selected Unit
            var unit = wialon.core.Session.getInstance().getItem(id); // get unit by id
            var sens = unit.getSensors(); // get unit's sensors
            var sensorRow = ' ';
            // unit sensors
            for (var i in sens) { // forloop sensors
                var s = sens[i];
                // calculate sensor value
                var result = unit.calculateSensorValue(s, unit.getLastMessage());
                if (result == -348201.3876) result = "N/A"; // compare result with invalid sensor value constant
                sensorRow += "<span>" + s.n + " (" + s.t + ") " + "=" + result + " (" + s.m +
                ")</span><br>"; // add sensor info
            }
            // unit information
            var text = "<th>" + id + "</th><th class='col-md-2 name'>" + unit.getName() + "</th>"; // get unit name
            var icon = unit.getIconUrl(32); // get unit Icon url
            if (icon) text += "<th><img class='icon' src='" + icon + "' alt='icon'/></th>"; // add icon to message
            var pos = unit.getPosition(); // get unit position
            text += "<th class='col-md-4 sensors'>" + sensorRow + "</th>" //  position data
                +
                "<th class='postion'>Y" + pos.y + ", X:" + pos.x + "</th>" +
                "<th class='speed'>" + pos.s + "</th>"
            // find unit location using coordinates and update content
            wialon.util.Gis.getLocations([{
                lon: pos.x,
                lat: pos.y
            }], function(code, address) {
                $("#tbody #" + id).html(text + "<th class='col-md-3 address'>" + address +
                "</th>"); //update content
            });
            console.log('updated');
        }


        // execute when DOM ready
        $(document).ready(function() {
            wialon.core.Session.getInstance().initSession("http://gps.tawasolmap.com"); // init session

            wialon.core.Session.getInstance().loginToken("<?php echo $token ?>", "", // try to login
                function(code) { // login callback
                    // if error code - print error message
                    if (code) {
                        notification(wialon.core.Errors.getErrorText(code));
                        return;
                    }
                    notification("Logged successfully");
                    const element = document.getElementById("log");
                    init()
                    setInterval(function() {
                        init()
                    }, 10000);

                });

        });
    </script>
</body>

</html>
