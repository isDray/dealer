<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    //

    public function index( Request $request){

        /*echo json_encode('ENTER');*/
        $key = 'a459ec4a-be91-461c-8b98-896d8283da64';
        
        if( $request->key == $key ){
            
            $now = date("Y-m-d H:i:s");

        	echo json_encode("ENTER");

        	logger("$now CALL SUCCESS \n\n-----------------------------------------------\n\n"); 

        }else{
        	
        	echo json_encode("DANM");
        }
    }
}
