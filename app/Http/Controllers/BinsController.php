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
        if(isset($request->checkout)){
            $this->checkout($request->checkout);
        }
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
        
        $viewBin = $request->viewBin;
        $viewBinName = $this->getBinName($viewBin);
        $viewTags = $this->getTags($viewBin);
        $checked = $this->isCheckedOut($viewBin);

        return view('bin/search')
            ->with('binNames', $binsWithTag_names)
            ->with('binFileNames', $binsWithTag_fileNames)
            ->with('viewBin', $viewBin)
            ->with('viewBinName', $viewBinName)
            ->with('tag', $tag)
            ->with('viewTags', $viewTags)
            ->with('checked', $checked);
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
    
    
    private function checkout($fileName){
        $imageDir= $this->getImageDir();
        if ($fileName[0]!="_"){
            rename($imageDir.$fileName, $imageDir."_" . $fileName);
        }
    }
    
    private function isCheckedOut($fileName){
        if($fileName[0]=='_'){
            return true;
        }
        return false;
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
        
        if(strlen($name)<1){
            return "Error, need bin name <br>";
        }
        else if(! (preg_match('/^[a-zA-Z0-9\-]+$/', $tags) || strlen($tags)<1 )){
            return "Error, tags must be alphaNumeric, and separated by - (hyphen) <br>";
        }
        else{
            $filename = $imageDir . $name . "-" . $tags . ".jpg";
            file_put_contents($filename, base64_decode($request->binImage));
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
        echo $this->getBinName($fileName) . "<br>";
        $tags = $this->getTags($fileName);
        foreach ($tags as $tag) {
            echo $tag . ', ';
        }
        echo '<br>';
        echo '<img src=' . url('/showImage') . '/' . $fileName . "></img>";
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
		    $dir = "../storage/app/images/" . Auth::user()->id . "/";
		if (!file_exists($dir))
			mkdir($dir, 0755, true);
		return $dir;
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
        $allFileNames = $this->scan_sorted($this->getImageDir());
        foreach ($allFileNames as $fileName){
            $subFileName = explode(".", $fileName);
            $subFileName = explode("-", $subFileName[0]);
            if ( strcmp($subFileName[0],$binName) == 0 ) return $fileName;
            if ( strcmp($subFileName[0],'_'.$binName) == 0 ) return $fileName;
        }
        // not found, return empty string
        return "";
    }
    
    private function scan_sorted($dir){
        $files = array();    
        foreach (scandir($dir) as $file) {
            $files[$file] = filemtime($dir . '/' . $file);
        }
    
        arsort($files);
        $files = array_keys($files);
        return ($files) ? $files : false;
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
          $allFileNames = $this->scan_sorted($this->getImageDir());
          $matchBinNames = array();
          foreach($allFileNames as $fileName){
              $subFileName = explode('.',$fileName);
              $subFileName = $subFileName[0];
              $subFileName = explode("-",$subFileName);
              if ( sizeof($subFileName) < 2 ) continue;
              $i = 0;
              foreach ($subFileName as $tag) {
                  $tag = preg_replace("/[^a-z]+/", "", strtolower($tag));
                  $searchTag = preg_replace("/[^a-z]+/", "", strtolower($searchTag));
                  similar_text($tag, $searchTag, $percentSimilar);
                  if ($percentSimilar >= 75){
                      if ( ! in_array($this->getBinName($fileName), $matchBinNames) ) {
                          array_push($imagesWithTag,$fileName);
                          array_push($matchBinNames,$this->getBinName($fileName));
                      }
                      break;
                  }
                  $i++;
              }
          }
          return $imagesWithTag;
      }
      
      private function getTags($fileName){
          $tags = array();
          $subFileName = explode('.',$fileName);
          $subFileName = $subFileName[0];
          $subFileName = explode("-",$subFileName);
          $i = 0;
          foreach ($subFileName as $tag) {
              if ($i!=0){
                  array_push($tags,$tag);
              }
              $i++;
          }
          return $tags;
      }
}
