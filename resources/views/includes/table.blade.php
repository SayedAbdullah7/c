{{-- @php
function get_value_of_sensor($unit_id){
    $paramsUnit = array(
			"unitId"=>$unit_id
		);
	return $unit = $wialon_api->unit_calc_last_message(json_encode($paramsUnit));
}
@endphp --}}
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
            @foreach ($items as $item)
                <tr>
                    <td>{{$wialon_api}}</td>
                    <td>{{$item['nm']}}</td>
                    <td>
                        @foreach ($item['sens'] as $s)
                        {{-- <span>{{ $s['n'] . '('.$s['t'].') = '. get_value_of_sensor($item['id'])}}</span><br> --}}

                        @endforeach
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
      <script>
        console.log('script');

        // $.ajax({
        //     url: 'http://gps.tawasolmap.com/gis_geocode?coords=[{"lon":46.6374833,"lat":24.78281}]&flags=1&uid=62289',
        //     // headers: {
        //     //     'Authorization': 'Bearer ' + access_token
        //     // },
        //     success: function(data) {
        //         console.log('success');
        //         addr = data;
        //         setTimeout(displayAddres, 10000); //recursive call
        //     }
        // });
    // function displayAddres(x,y,id) {
    //     console.log('success');
    //     var addr = document.getElementById(2751).innerHTML;

    //     $.ajax({
    //         url: 'http://gps.tawasolmap.com/gis_geocode?coords=[{"lon":46.6374833,"lat":24.78281}]&flags=1&uid=62289',
    //         // headers: {
    //         //     'Authorization': 'Bearer ' + access_token
    //         // },
    //         success: function(data) {
    //             console.log('success');
    //             addr = data;
    //             setTimeout(displayAddres, 10000); //recursive call
    //         }
    //     });
    // }
    </script>
