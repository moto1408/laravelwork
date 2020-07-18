<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;

// 入力チェッククラス
use App\Http\Requests\sample001Request;

class sample001Controller extends Controller
{
	public function __construct()
	{
		Log::debug('Debug Message');
	}

	// メイン画面表示
	public function index()
	{
		return view('sample001.post');
	}
	public function post(sample001Request $request ){
	// public function post(Request $request ){
		// var_dump(__METHOD__.__LINE__);die;
		// var_dump($request->input('name'));

		
		$method_name = __METHOD__;
		// $temp = $this->validate($request,$validate_rule);
		// var_dump($temp);
		// return view('sample001.post');
		// return view('sample001.post',compact('method_name'));
	}
}
