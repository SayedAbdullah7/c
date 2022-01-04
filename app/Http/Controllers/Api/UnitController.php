<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UnitResource;
use Illuminate\Http\Request;
use App\Wialon;
use App\WialonError;
use Response;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;
use View;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $token = $request->session()->get('token');

        $wialon_api = new Wialon();
        $result = $wialon_api->login($token);

        $data = json_decode($result, true);

        if(!isset($data['error'])){
            $data = json_decode($result, true);
            // return $data['user']['nm'].'|'.$data['user']['id'];
            // return view('home',compact('data','token'));
            // return UnitResource::collection($data);
            return 'data';
        } else {
            // $request->session()->forget('token');
            // return redirect()->route('login');
            return 'error token';
        }
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
