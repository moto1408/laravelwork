<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// 入力チェッククラス
use App\Http\Requests\sample001Request;

class sample001Controller extends Controller
{
	public function __construct()
	{
		// parent::__construct();
		Log::debug(__FILE__ . ":" . __LINE__);
		
	}

	// メイン画面表示
	public function index()
	{
		return view('sample001.index');
	}

	// メイン画面表示
	public function add()
	{
		return view('sample001.add');
	}
	// public function post(sample001Request $request ){
	public function post(Request $request ){

		// 入力チェック条件指定する
		$validate_rule;
		// 名前｜必須
		$validate_rule['name'] = 'required';
		// メール｜フォーマット
		$validate_rule['mail'] = 'email';
		//　年齢｜数値、0～150
		$validate_rule['age'] = 'numeric|between:0,150';

		$validator = validator::make($request->all(),$validate_rule);

		// エラー有無チェック
		if ($validator->fails()) {
			return redirect(route('sample001.add'))
						// バリデータエラーを付加する
						->withErrors($validator)
						// 入力データを付加する
                        ->withInput();
		}
		
		// var_dump(__METHOD__.__LINE__);die;
		// var_dump($request->input('name'));

		
		$method_name = __METHOD__;
		// $temp = $this->validate($request,$validate_rule);
		// var_dump($temp);
		// return view('sample001.post');
		return view('sample001.post',compact('method_name'));
	}
}
