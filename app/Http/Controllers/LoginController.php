<?php

namespace App\Http\Controllers;

use App\Wialon;
use App\WialonError;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function create(Request $request)
    {
        if($request->session()->has('token')){
            return redirect()->route('units');
        }
        return view('login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $wialon_api = new Wialon();
        $token = $request->token;

        $result = $wialon_api->login($token);
        $json = json_decode($result, true);
        if(!isset($json['error'])){
            $request->session()->put('token',$token);
            return redirect()->route('units');
        } else {
            return WialonError::error($json['error']);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->session()->forget('token');
        return redirect()->route('login');

    }
}
