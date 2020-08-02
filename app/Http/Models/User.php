<?php

namespace App\Http\Models;
Use Schema;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
// 論理削除
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Model
{
    // 論理削除の利用を宣言
    use SoftDeletes;

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
}
