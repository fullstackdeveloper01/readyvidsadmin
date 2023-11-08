<?php

/*
 * Name: ImageController.php
 * Use: Method to upload image
 * Author: Homepage Infotech Solutions LLP
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
//use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function postFile($file, $path, $type) {
      
      //set file name of image
      $fileName = $type."_".rand().'.'.$file->getClientOriginalExtension();

      $destinationPath = public_path('uploads/' . $path);   
      
      //  make all directories if they do not exist
      if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );

      if(file_exists($destinationPath.$fileName)) { 
          unlink($destinationPath.$fileName); //remove the file
      }
     
      //upload image to path
      try {
        $uploadSuccess=$file->move($destinationPath,$fileName);
      }
      catch(Exception $e) {
        echo $e->getMessage();
      }
      

      //return image name
      return 'uploads/'.$path.$fileName;
    
    }

    public function postExternalFile($url, $path, $type) {
      //set file name of image
      $filename_from_url = parse_url($url);
      $ext = pathinfo($filename_from_url['path'], PATHINFO_EXTENSION);
      $fileName = $type."_".rand().'.'.$ext;

      $destinationPath = public_path('uploads/' . $path);   
      
      //  make all directories if they do not exist
      if( !is_dir( $destinationPath ) ) mkdir( $destinationPath, 0755, true );

      if(file_exists($destinationPath.$fileName)) { 
          unlink($destinationPath.$fileName); //remove the file
      }
     
      //upload image to path
      file_put_contents( $destinationPath.$fileName,file_get_contents($url));

      //return image name
      return 'uploads/'.$path.$fileName;

    }

  
    
    
}