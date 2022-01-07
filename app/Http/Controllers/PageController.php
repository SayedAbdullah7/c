<?php

namespace App\Http\Controllers;

use App\Http\Resources\UnitResource;
use Illuminate\Http\Request;
use App\Wialon;
use App\WialonError;
use Response;
use View;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('trips');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $token = $request->session()->get('token');
        $wialon_api = new Wialon();
        $result = $wialon_api->login($token);
        $data = json_decode($result, true);

        if (!isset($data['error'])) {
            $sid = $data['eid']; // session id
            $params = array(
                "spec" => array(
                    "itemsType" => 'avl_unit',
                    "propName" => 'sys_name',
                    "propValueMask" => '*',
                    "sortType" => 'sys_name',
                ), "force" => 1,
                "flags" => 0x1024 | 0x1 | 0x00400000 | 0x10,
                "from" => 0,
                "to" => 0
            );
            $units = $wialon_api->core_search_items(json_encode($params, true));
            $units_json = json_decode($units, true);
            $items = $units_json['items'];
            // sensors values code
            $sensors_values = array(); // set var for sensors values
            foreach ($items as $key => $item) {
                $paramsUnit = [
                    'unitId' => $item['id'],
                ];
                $values = $wialon_api->unit_calc_last_message(json_encode($paramsUnit, true)); // calc sensor value
                $sensors_values[] = json_decode($values, true); // store the output in array
            }

            // last trip code
            $selector = array(
                "type" => 'trips',
                "timeFrom" => strtotime("yesterday"),
                "timeTo" => time(),
                "detalization" => '0x20',
            );
            $detectors = array(
                "type" => 'trips',
                "filter1" => '0'
            );
            $last_trip = array(); // set var for last trip
            foreach ($items as $key => $item) {
                $item = array(
                    "itemId" => $item['id'],
                    "ivalType" => '1',
                    "timeFrom" => strtotime("yesterday"),
                    "timeTo" => time(),
                    "detectors" => [$detectors],
                    "selector" => $selector
                );
                $cURLConnection = curl_init();
                $url = 'http://gps.tawasolmap.com/wialon/ajax.html?svc=events/load&params=' . json_encode($item) . '&sid=' . $sid;
                curl_setopt($cURLConnection, CURLOPT_URL, $url);
                curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
                $output = curl_exec($cURLConnection);
                $output = json_decode($output, true); // convert to array
                $last = end($output['selector']['trips']['0']);
                if ($last == null) {
                    $last['format'] = null;
                }
                $last_trip[] = $last['format']; // store final output in array

            }
            if ($request->ajax()) {
                return  view('includes.table', compact('items', 'sensors_values', 'sid', 'last_trip'));
            }
            return  view('trips', compact('items', 'sensors_values', 'sid', 'last_trip','data'));
        } else {
            $request->session()->forget('token');
            return redirect()->route('login');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
