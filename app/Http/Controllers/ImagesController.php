<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImagesController extends Controller
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
    // Directory to look for image files
    private $imageDir = '/home/cabox/workspace/images/';
  
    // Known types of images and their meta type
    private $imageTypes = [
        'gif'=> 'image/gif',
        'png'=> 'image/png',
        'jpeg'=> 'image/jpeg',
        'jpg'=> 'image/jpeg',
    ];
    
    // Get filename extension
    private function getExtension($name){
        $extension = explode(".", $name);
        $extension = $extension[count($extension) - 1];
        return $extension;
    }
    
    // Look up image meta type
    private function getType($name){
        $extension = $this->getExtension($name);
        $types = $this->imageTypes;
        foreach($types as $type=>$meta){
            if( $extension==$type ){
                return $meta;
            }
        }
    // if type is not found:
    return '';
    }
    
    public function index($imageName){
        $imagePath = $this->imageDir . $imageName;
        $imageType = $this->getType($imageName);
        if (!$imageType) echo "error getting image type";
        else if( file_exists($imagePath) ){
            header( "Content-type: " . $imageType );
            header( "Content-Length: " . filesize($imagePath));
            readfile($imagePath);
            return;
        }
        else echo "image not found";
    }
}
