<?php

namespace App\Http\Controllers;

use log;
use DB;
use App\Http\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class sampleAsynController extends Controller
{
    public function __construct()
	{
		// parent::__construct();
		\Log::info(__FILE__ . ":" . __LINE__);
		
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // メイン画面表示
	public function index(Request $request=null)
	{
		// モデル呼び出し
        $ModelUser = new User;
        $recodes = array();
        $recodes =  $ModelUser->getTableData($request);
        
        $pageName = 'ユーザー';
        
		return view('sampleAsyn.index',compact('recodes','pageName'));
    }
    
    public function export(Request $request)
    {
        $headers = [ //ヘッダー情報
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=csvexport.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() 
        {
            
            $createCsvFile = fopen('php://output', 'w'); //ファイル作成
            
            $columns = [ //1行目の情報
                'reserved_date',
                'checkin_date',
                'total_price',
            ];

            mb_convert_variables('SJIS-win', 'UTF-8', $columns); //文字化け対策
    
            fputcsv($createCsvFile, $columns); //1行目の情報を追記

            $bookingCurve = DB::table('csvimport');  // データベースのテーブルを指定

            $bookingCurveResults = $bookingCurve  //データベースからデータ取得
                ->select(['reserved_date'
                , 'checkin_date' 
                ,DB::raw('sum(total_price) as total_price')])
                ->groupby('reserved_date')
                ->get();
        
            foreach ($bookingCurveResults as $row) {  //データを1行ずつ回す
                $csv = [
                    $row->reserved_date,  //オブジェクトなので -> で取得
                    $row->checkin_date,
                    $row->total_price,
                ];

                mb_convert_variables('SJIS-win', 'UTF-8', $csv); //文字化け対策

                fputcsv($createCsvFile, $csv); //ファイルに追記する
            }
            fclose($createCsvFile); //ファイル閉じる
        };
        
        return response()->stream($callback, 200, $headers); //ここで実行
        
    }

    /*********************************
     * Ajax 
     *********************************/
    /**
     * 非同期通信検索処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json
     */
    public function ajaxSearch(Request $request)
    {
        try{
            // モデル呼び出し
		    $ModelUser = new User;
            
            // 検索実行
            $recodes = array();
            $recodes =  $ModelUser->getTableData($request);
            
            
            // 返答値を準備する
            $response = array();
            $response['list'] = $recodes;
            $response['status'] = 'success';
            $response['message'] = '検索に成功しました。';
            $responseJson = response()->json($response);
            return $responseJson;
        }catch(\Exception $e){
            \Log::info($e);
            // 返答値を準備する
            $response = array();
            $response['list'] = array();
            $response['status'] = 'failure';
            $response['message'] = '通信に失敗しました。';
            $responseJson = response()->json($response);
            return $responseJson;
        }
    }
    /**
     * 非同期通信検索処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json
     */
    public function ajaxGetData(Request $request)
    {
        try{
            $id = $request->input('id','');
            // モデル呼び出し
		    $ModelUser = new User;
            
            // 検索実行
            $recodes = array();
            $recodes = $ModelUser->getTableDataById($id);
            
            
            // 返答値を準備する
            $response = array();
            $response['record'] = $recodes;
            $response['status'] = 'success';
            $response['message'] = '検索に成功しました。';
            $responseJson = response()->json($response);
            return $responseJson;
        }catch(\Exception $e){
            \Log::info($e);
            // 返答値を準備する
            $response = array();
            $response['record'] = array();
            $response['status'] = 'failure';
            $response['message'] = '通信に失敗しました。';
            $responseJson = response()->json($response);
            return $responseJson;
        }
    }
    /**
     * 非同期通信検索処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json
     */
    public function ajaxUpsert(Request $request)
    {
        try{
            // idを取得する
            $id = $request->input('id','');
            $resultName = $request->input('name','');
            // 入力チェック定義を取得する
            $validate_rule = $this->getValidate($id);

            // 入力チェックを実行する
            $validator = validator::make($request->all(),$validate_rule);

            // エラー有無チェック
            if ($validator->fails()) {
                \Log::info($validator->errors());
                // 返答値を準備する
                $response = array();
                $response['list'] = array();
                $response['status'] = 'failure';
                $response['message'] = '';
                $response['errors'] = $validator->errors();
                $responseJson = response()->json($response);
                return $responseJson;    
            }
            // モデル呼び出し
            $ModelUser = new User;
            // トランザクション用意
            DB::beginTransaction();
            
            // データ登録・更新を行う
            $ModelUser->upsert($request,$id);
            // コミット
            DB::commit();
            
            // 検索実行
            $recodes = array();
            $recodes =  $ModelUser->getTableData();
            
            
            // 返答値を準備する
            $response = array();
            $response['list'] = $recodes;
            $response['status'] = 'success';
            $response['message'] = sprintf('「%s」を登録しました。',$resultName);
            $responseJson = response()->json($response);
            return $responseJson;
        }catch(\Exception $e){
            // ロールバック
            DB::rollback();
            \Log::info($e);
            // 返答値を準備する
            $response = array();
            $response['list'] = array();
            $response['status'] = 'failure';
            $response['message'] = '通信に失敗しました。';
            $responseJson = response()->json($response);
            return $responseJson;
        }
    }
    /**
     * 削除を行う
     * 
     * @param $request
     * @return JSON
     */
	public function ajaxDelete(Request $request){
        
        try{
            $id = $request->input('id','');
            if(empty($id)){
                throw new \Exception('idがありません');
            }
            // モデル呼び出し
		    $ModelUser = new User;
            // トランザクション用意
            DB::beginTransaction();

            // // 削除実行
            $recode = $ModelUser->dataDelete($id);
            $delName = $recode->name;
            
            // コミット
			DB::commit();
            
            // 返答値を準備する
            $response = array();
            $response['id'] = $id;
            $response['status'] = 'success';
            $response['message'] = sprintf("「%s」を削除しました。",$delName);
            $responseJson = response()->json($response);
            return $responseJson;
        }catch(\Exception $e){
            // エラー内容ログ出力する
            \Log::info($e);
            // ロールバック
            DB::rollback();
            
            // 返答値を準備する
            $response = array();
            $response['status'] = 'failure';
            $response['message'] = '通信に失敗しました。';
            $responseJson = response()->json($response);
            return $responseJson;
        }
    }
    /**
     * Private Function
     */
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
