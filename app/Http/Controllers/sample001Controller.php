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
	public function update(Request $request)
	{
		$id = $request->input('id');
		$recodes = User::find($id);
		
		return view('sample001.add',compact('recodes'))->withInput($recodes);
	}

	// 登録処理
	public function post(Request $request ){

		$id = $request->input('id','');
		$user = User::find($id);
		
		// 入力チェック条件指定する
		$validate_rule;
		// 名前｜必須
		$validate_rule['name'] = 'required';
		// メール｜フォーマット
		$validate_rule['email'] = !empty($user->id) ? 'required|email|unique:users,email,' . $user->id . ',id' : 'required|email|unique:users,email';
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
			$param = array();

			// カラム一覧を取得する
			$colums = Schema::getColumnListing('users');
			// 主キー取得する
			$primaryKey = $ModelUser->getKeyName();
			unset($colums[$primaryKey]);

			// カラムと一致するPOST値を取得する
			foreach($colums as $value)
			{
				if($request->has($value))
				{
					$param[$value] = $request->input($value);
				}
			}

			// $ModelUser->fill($param);
			// 各値をセットする
			// idがある場合（更新）
			if(!empty($id))
			{
				$param['password'] = !empty($param['password']) ? $param['password'] : "";
				$param['updated_at'] = date("Y-m-d H:i:s");
				$ModelUser
					->where($primaryKey,$request->input($primaryKey))
					->update($param);
			}
			else
			{
				$param['password'] = !empty($param['password']) ? $param['password'] : "";
				$param['created_at'] = date("Y-m-d H:i:s");
				$param['updated_at'] = date("Y-m-d H:i:s");
				$ModelUser->insert($param);
			}
			
			
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
