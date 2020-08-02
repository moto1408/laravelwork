<?php

namespace App\Http\Controllers;

use Log;
Use Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

// 入力チェッククラス
use App\Http\Requests\sample001Request;

use App\Http\Models\User;

class sample001Controller extends Controller
{
	public function __construct()
	{
		// parent::__construct();
		Log::debug(__FILE__ . ":" . __LINE__);
		
	}

	// メイン画面表示
	public function index(Request $request=null)
	{

		$where = array();
		if(!empty($request)){
			$name = $request->input('name','');
			$email = $request->input('email','');
			$where[] = array('name','like','%' . $name . '%');
			$where[] = array('email','like','%' . $email . '%');
		}
		
		$recodes = User::where($where)
						->get();
		
		return view('sample001.index',compact('recodes'));
	}

	/**
	 * 新規登録画面を表示する
	 */
	public function add()
	{
		// テンプレート指定する
		return view('sample001.add');
	}

	/**
	 * 更新登録画面を表示する
	 */
	public function update(Request $request)
	{
		$id = $request->input('id');
		$recodes = User::find($id);
		// テンプレート指定する、IDと一致するデータを画面へ値渡しする
		return view('sample001.add',compact('recodes'));
	}

	// 登録・更新処理
	public function post(Request $request ){

		// idを取得する
		$id = $request->input('id','');
		// 入力チェック定義を取得する
		$validate_rule = $this->getValidate($id);

		// 入力チェックを実行する
		$validator = validator::make($request->all(),$validate_rule);

		// エラー有無チェック
		if ($validator->fails()) {
			// エラーリダイレクト先を指定する（入力画面へリダイレクトする）
			return redirect(route('sample001.add'))
						// バリデータエラーを付加する
						->withErrors($validator)
						// 入力データを付加する
                        ->withInput();
		}

		// モデル呼び出し
		$ModelUser = new User;
		// トランザクション用意
		DB::beginTransaction();
		
		try{
			// データ登録・更新を行う
			$ModelUser->upsert($request,$id);
			// コミット
			DB::commit();
		}catch(Exception $e){
			// ロールバック
			DB::rollback();
		}
		
		// 一覧へリダイレクトする
		return redirect(route('sample001.index'));
	}
	// 削除
	public function delete(Request $request){
		$request->id;
		// モデル呼び出し
		$ModelUser = new User;
		// トランザクション用意
		DB::beginTransaction();
		try{
			$target = $ModelUser::find($request->id);
			$target->delete();
			DB::commit();
		}catch(Exception $e){
			// ロールバック
			DB::rollback();
		}
		return redirect(route('sample001.index'));
	}
	/**
	 * Ajax fuction
	 */
	// 削除
	public function ajaxDelete(Request $request){
		
		$request->id;
		// モデル呼び出し
		$ModelUser = new User;
		// トランザクション用意
		DB::beginTransaction();		

		$responseParam = array();
		try{
			$target = $ModelUser::find($request->id);
			$target->delete();
			DB::commit();
			$responseParam['resulet'] = 'success';
		}catch(Exception $e){
			$responseParam['resulet'] = 'failure';
			// ロールバック
			DB::rollback();
		}
		return response()->json([
            $responseParam
         ]);
		
	}
	// 登録・更新処理
	private function getValidate($id=null){
		$user = User::find($id);

		// 入力チェック条件指定する
		$validate_rule;
		// 名前｜必須
		$validate_rule['name'] = 'required';
		// メール｜フォーマット
		$validate_rule['email'] = !empty($user->id) ? 'required|email|unique:users,email,' . $user->id . ',id' : 'required|email|unique:users,email';
		//　年齢｜数値、0～150
		$validate_rule['age'] = 'numeric|between:0,150';

		return $validate_rule;
	}
}
