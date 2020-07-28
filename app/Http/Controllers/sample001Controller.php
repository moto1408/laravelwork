<?php

namespace App\Http\Controllers;

use Log;
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
			$name = $request->input('name');
			$email = $request->input('email');
			$where[] = array('name','like','%' . $name . '%');
			$where[] = array('email','like','%' . $email . '%');
		}
		
		
		$recodes = DB::table('users')->where($where)->get();
		
		$recodes = User::all();
		// var_dump($neko);
		return view('sample001.index',compact('recodes'));
	}

	// 新規登録画面表示
	public function add()
	{
		return view('sample001.add');
	}

	// 更新画面表示
	public function modify(Request $request)
	{
		$id = $request->input('id');
		$recodes = User::find($id)->first();
		
		return view('sample001.add',compact('recodes'))->withInput($recodes);
	}

	// 登録処理
	public function post(Request $request ){

		// 入力チェック条件指定する
		$validate_rule;
		// 名前｜必須
		$validate_rule['name'] = 'required';
		// メール｜フォーマット
		$validate_rule['email'] = 'required|email|unique:users,email';
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
		
		// $param = array();
		// $param['name'] = $request->input('name');
		// $param['email'] = $request->input('email');
		// $param['age'] = $request->input('age');
		// $param['password'] = '';
		// $param = [
		// 	'name' => $request->input('name'),
		// 	'email' => $request->input('email'),
		// 	'age' => $request->input('age'),
		// 	'password' => ''
		// ];

		// $insert_sql = 
		// 	"INSERT INTO users"
		// 		. "( name, email, age, password)"
		// 	. "VALUES "
		// 		. "( :name, :email, :age, :password);";
		// DB::insert($insert_sql,$param);
		// DB::table('users')->insert($param);

		// モデル呼び出し
		$ModelUser = new User;
		// トランザクション用意
		DB::beginTransaction();
		try{
			// 各値をセットする
			// 名前
			$ModelUser->name = $request->name;
			// メール
			$ModelUser->email = $request->email;
			// 年齢
			$ModelUser->age = $request->age;
			// パスワード
			$ModelUser->password = '';
			// 登録日時
			$ModelUser->created_at = time();
			// 更新日時
			$ModelUser->updated_at = time();

			// 登録実行
			$ModelUser->save();
			// throw new \Exception('意図したエラー');
			// コミット
			DB::commit();
		}catch(Exception $e){
			// ロールバック
			DB::rollback();
		}
		

		
		
		$method_name = __METHOD__;
		// $temp = $this->validate($request,$validate_rule);
		// var_dump($temp);
		// return view('sample001.post');
		// return view('sample001.post',compact('method_name'));
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
}
