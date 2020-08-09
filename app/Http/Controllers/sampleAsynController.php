<?php

namespace App\Http\Controllers;

use App\Http\Models\User;
use Illuminate\Http\Request;
use log;
use DB;

class sampleAsynController extends Controller
{
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Http\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Http\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Http\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
