<?php

namespace App\Http\Models;

Use Schema;
Use DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
// 論理削除
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Model
{
    // 論理削除の利用を宣言
    use SoftDeletes;

    public function getTableData(Request $request=null){

        // 条件を取得
        $where = array();
        $name = $request->input('name','');
        $email = $request->input('email','');
        $where[] = array('name','like','%' . $name . '%');
        $where[] = array('email','like','%' . $email . '%');

        // 検索を実行する
        $recodes = $this->select([DB::raw('SQL_CALC_FOUND_ROWS *')])
                        ->where($where)
                        ->get();
        return $recodes;
    }
    /**
     * 新規登録・更新登録を行う関数
     * request Request 
     * id
     */
    public function upsert(Request $request=null,$id=null){

        if($request == null){
            throw new Exception('no-data');
        }
        $param = array();

        // カラム一覧を取得する
        $colums = Schema::getColumnListing('users');

        // 主キー取得する
        $primaryKey = $this->getKeyName();
        unset($colums[$primaryKey]);

        // カラムと一致するPOST値を取得する
        foreach($colums as $value)
        {
            if($request->has($value))
            {
                $param[$value] = $request->input($value);
            }
        }     

        // 各値をセットする
        // idがある場合（更新）
        if(!empty($id))
        {
            $param['password'] = !empty($param['password']) ? $param['password'] : "";
            $param['updated_at'] = date("Y-m-d H:i:s");
            $id = $request->input($primaryKey);
            $this
                // 更新対象を指定する
                ->where($primaryKey,$id)
                // 更新内容をセットする
                ->update($param);
        }
        else
        {
            $param['password'] = !empty($param['password']) ? $param['password'] : "";
            $param['created_at'] = date("Y-m-d H:i:s");
            $param['updated_at'] = date("Y-m-d H:i:s");
            // 新規登録
            $id = $this
                    ->insertGetId($param);
        }

        return $id;
    }
    /**
     * 対象のデータを削除する関数
     * request Request 
     * id
     */
    public function dataDelete($id=null){

        if($id == null){
            throw new Exception('no-data');
        }
        // 削除実行
        $target = $this::find($id);
        // 削除データの名前を取得する
        $delName = $target->name;
        // 削除を実行する
        $target->delete();

        return $target;
    }
}
