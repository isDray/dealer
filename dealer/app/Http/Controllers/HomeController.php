<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if( Auth::user()->hasRole('Admin') ){
            
            return view('home')->with(['title'=>'管理者儀表'

                                      ]);

        }elseif( Auth::user()->hasRole('Dealer') ){

            return view('home')->with(['title'=>'經銷商儀表'

                                      ]);
        }
        
    }
}
