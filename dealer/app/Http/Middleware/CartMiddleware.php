<?php

namespace App\Http\Middleware;

use Closure;

use App\User;
use App\Role;

class CartMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $dealers = Role::where('name','Dealer')->first()->users()->get();
        
        if( count($dealers) > 0){
            
            $dealers = json_decode($dealers,true);

            $dealerArr = [];

            foreach ($dealers as $dealer) {

                array_push($dealerArr, $dealer['id'] );

            }  
            
            $cartUser = User::whereIn('id',$dealerArr)->where('detect',$request->name)->first();

            if( $cartUser != NULL ){

                
                $request->session()->put('cartUser', $cartUser->id );
                
            }else{

                abort(404);
            }

        }else{
            abort(404);
        }

        return $next($request);


    }
}
