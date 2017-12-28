<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
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
    public function index(Request $request)
    {
        $tag = $request->input('tag');
        $binsWithTag = $this->searchImagesForTag($tag);
        $binsWithTag_names = array();
        $binsWithTag_fileNames = array();
        if( sizeof($binsWithTag) > 0 ){
            foreach($binsWithTag as $bin){
                $checkedOut = false;
                if ($bin[0] == '_'){
                    $checkedOut = true;
                }
                $bin_trimmed = ltrim($bin,'_');
                $bin_name = $this->getBinName($bin);
                array_push($binsWithTag_fileNames,$bin);
                array_push($binsWithTag_names,$bin_name);
            }
        }
        return view('bin/search')
            ->with('binNames', $binsWithTag_names)
            ->with('binFileNames', $binsWithTag_fileNames);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bin/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $imageDir= $this->getImageDir();
      // check for directory existence
        if(!file_exists ($imageDir)){
            mkdir($imageDir);
        }
        $name = $request->name;
        $tags = $request->tags;
        
      // check if bin already exists
        $fileToReplace = $this->getBinFileName($name);
        
        if(strlen($name)<1){
            return "Error, need bin name <br>";
        }
        else if(! (preg_match('/^[a-zA-Z0-9\-]+$/', $tags) || strlen($tags)<1 )){
            return "Error, tags must be alphaNumeric, and separated by - (hyphen) <br>";
        }
        else{
            $filename = $imageDir . $name . "-" . $tags . ".jpg";
            file_put_contents($filename, base64_decode($request->binImage));
          // delete old version
            if( strlen($fileToReplace) > 0 ) unlink($imageDir . $fileToReplace);
            return redirect('home');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $binName
     * @return \Illuminate\Http\Response
     */
    public function show($binName)
    {
        $fileName = $this->getBinFileName($binName);
        echo $fileName . "<br>";
        echo $this->getBinName($fileName) . "<br>";
        return;
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
    
    /** 
     * Get the directory where images are stored
     */
    private function getImageDir(){
        if(Auth::check()){
            return "../images/" . Auth::user()->id . "/";
        }
        return "";
    }
    
    /**
     * Get the file location of a bin
     * @param string $binName 
     * @return string - fileName
     */
    private function getBinFileName($binName){
        $binName = ltrim($binName,'_');
        $allFileNames = scandir($this->getImageDir());
        foreach ($allFileNames as $fileName){
            $subFileName = explode(".", $fileName);
            $subFileName = explode("-", $subFileName[0]);
            if ( strcmp($subFileName[0],$binName) == 0 ) return $fileName;
            if ( strcmp($subFileName[0],'_'.$binName) == 0 ) return $fileName;
        }
        // not found, return empty string
        return "";
    }
    
    /**
     * Extract the bin name from a file name
     * @param string $fileName
     * @return string (the bin's name)
     */
     private function getBinName($fileName){
         $subFileName = explode(".", $fileName);
         $subFileName = $subFileName[0];
         $subFileName = explode("-",$subFileName);
         if ( sizeof($subFileName) < 1) return "";
         return ltrim($subFileName[0],'_');
     }
     
     /**
      * Search through fileNames for a tag
      * @param string $searchTag - the tag to look for
      * @return array of strings - fileNames containing that tag
      */
      private function searchImagesForTag($searchTag){
          $imagesWithTag = array();
          $allFileNames = scandir($this->getImageDir());
          foreach($allFileNames as $fileName){
              $subFileName = explode('.',$fileName);
              $subFileName = $subFileName[0];
              $subFileName = explode("-",$subFileName);
              if ( sizeof($subFileName) < 2 ) continue;
              $i = 0;
              foreach ($subFileName as $tag) {
                  if ($i!=0){
                      if ( strcmp($tag,$searchTag) == 0 ){
                          array_push($imagesWithTag,$fileName);
                      }
                  }
                  $i++;
              }
          }
          return $imagesWithTag;
      }
}
