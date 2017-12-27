<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class BinsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('capture');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $imageDir= "../images/" . Auth::user()->id ;
        if(!file_exists ($imageDir)){
            mkdir($imageDir);
        }
        $name = $request->name;
        $tags = $request->tags;
        if(strlen($name)<1){
            return "Error, need bin name <br>";
        }
        else if(! preg_match('/^[a-zA-Z]+[a-zA-Z0-9\-]+$/', $tags)){
            echo "$tags <br>";
            return "Error, tags must be alphaNumeric, and separated by - (hyphen) <br>";
        }
        else{
            $filename = $imageDir . "/" . $name . "-" . $tags . ".jpg";
            file_put_contents($filename, base64_decode($request->binImage));
            return redirect('home');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
