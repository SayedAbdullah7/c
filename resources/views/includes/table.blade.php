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
        @foreach ($items as $index => $item)
            <tr>
                <td>{{ $item['id'] }}</td>
                <td>{{ $item['nm'] }}</td>
                <td><img src="http://gps.tawasolmap.com{{ $item['uri'] }}?side={{ $sid }}" alt="" srcset="">
                </td>
                <td>
                    @foreach ($item['sens'] as $key => $s)
                        <span>{{ $s['n'] . '(' . $s['t'] . ') = ' . $sensors_values[$index][$key] . '(' . $s['m'] . ')' }}</span><br>
                    @endforeach
                </td>
                <td>{{ $item['pos']['s'] }}</td>
                <td class="col-md-2">Y: {{ $item['pos']['y'] . ', X:' . $item['pos']['x'] }}</td>
                <th>{{ file_get_contents('http://gps.tawasolmap.com/gis_geocode?coords=[{"lon":' . $item['pos']['x'] . ',"lat":' . $item['pos']['y'] . '}]&flags=1&uid=62289') }}
                </th>
                <th>
                    @if ($last_trip[$index] != null)
                        {{ $last_trip[$index]['distance'] }}
                    @endif
                    {{-- {{$last_trip[$index]['format']}} --}}
                </th>
            </tr>
        @endforeach
    </tbody>
</table>
