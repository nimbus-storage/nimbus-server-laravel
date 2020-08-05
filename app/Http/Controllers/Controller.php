<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function ok($data){
    	return response()->json([
    		'status' => 'ok',
    		'content' => $data
    	], 200, ['Access-Control-Allow-Origin' => '*']);
    }

    protected function nok($data, $code = 400){
    	return response()->json([
    		'status' => 'nok',
    		'content' => $data
    	], $code, ['Access-Control-Allow-Origin' => '*']);
    }
}
