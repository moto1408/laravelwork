{{-- 基本レイアウト継承 --}}
@extends('layouts.layout')

{{-- タイトルタグにページ名割当 --}}
@section('title',$pageName)

{{-- Header個別記述 --}}
@section('header')
<script src="{{ asset('/js/sampleAsyn.js') }}"></script>
<script>
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var search_event = function(event){
        // 一覧表示クリアする
        $('.data_area').empty();

        // 送信データ準備する
        var name = $('#name').val();
        var email = $('#email').val();
        var param =
        [
            ['name' , name],
            ['email' , email]
        ];
        
        var type = 'post';
        // 通信先URL
        var actionUrl = '{{ route('sampleAsyn.ajax.ajaxSearch') }}';
        
        var successCallBack = function(data){
            // 一覧表示する
            setData(data['list']);

            // メッセージエリア初期化する
            $('.alert_aler').empty();

            // メッセージDOM作成
            var alertDom = getAlertDom('primary',data['message']);

            // メッセージ要素を表示する
            $('.alert_aler').append(alertDom).hide().fadeIn(300);
        }
        var errorCallBack = function(data){
            // メッセージエリア初期化する
            $('.alert_aler').empty();

            // メッセージDOM作成
            var alertDom = getAlertDom('danger',data['message']);

            // メッセージ要素を表示する
            $('.alert_aler').append(alertDom).hide().fadeIn(300);
        }
        // 通信実行
        ajax(type,actionUrl, param, successCallBack, errorCallBack)

        return false;
    }
    var successCallBackDelete = function(data){
        // メッセージエリア初期化する
        $('.alert_aler').empty();

        // メッセージDOM作成
        var alertDom = getAlertDom('primary',data['message']);

        // メッセージ要素を表示する
        $('.alert_aler').append(alertDom).hide().fadeIn(300);

        // フェードアウトアニメーション
        $('[data-area-id="' + data.id + '"]').fadeOut(500,function(){
            $(this).remove();
        });
    }
    var errorCallBackDelete = function(data){
        // メッセージエリア初期化する
        $('.alert_aler').empty();

        // メッセージDOM作成
        var alertDom = getAlertDom('danger',data['message']);

        // メッセージ要素を表示する
        $('.alert_aler').append(alertDom).hide().fadeIn(300);
    }
    
    var delete_event = function(event){
        
        var check_flg = confirm('削除してよろしいですか？');
        if(check_flg == true){
            var id = $(this).data('id');
            var type = 'post';    
            var param = {'id' : id};
            // 通信先URL
            var actionUrl = '{{ route('sampleAsyn.ajax.ajaxDelete') }}';

            // 通信実行
            ajax(type,actionUrl, param, successCallBackDelete, errorCallBackDelete)
        } 
    }
    var update_event = function(event){
        var id = $(this).data('id');
        var type = 'post';    
        var param = {'id' : id};

        // 通信先URL
        var actionUrl = '{{ route('sampleAsyn.ajax.ajaxGetData') }}';
        
        var successCallBack = function(data){
            // 入力項目初期化
            $('[data-modal-form="id"]').val([data.record['id']]);
            $('[data-modal-form="updated_at"]').val([data.record['updated_at']]);
            $('[data-modal-form="name"]').val([data.record['name']]);
            $('[data-modal-form="email"]').val([data.record['email']]);
            $('[data-modal-form="age"]').val([data.record['age']]);

            $('#formModal').modal('show');
        }
        var errorCallBack = function(data){
            alert(data['message']);
        }
        // 通信実行
        ajax(type,actionUrl, param, successCallBack, errorCallBack)

        // location.href = '{{route('sample001.update')}}?id=' + id;
    }
    $('.search_btn').on('click',search_event)
    $('.update_btn').on('click',update_event);
    $('.delete_btn').on('click',delete_event);

    function setData(data){
        $.each(data,function(index,val){
            // レコード情報をDOM要素にセットする
            var name = $('<td></td>').text(val["name"]);
            var email = $('<td></td>').text(val["email"]);
            var age = $('<td></td>').text(val["age"]);
            var created_at = $('<td></td>').text(val["created_at"]);
            var updated_at = $('<td></td>').text(val["updated_at"]);
            var modify_btn = $('<td></td>').append($('<button>',{
                class : "btn btn-primary btn-sm modify_btn",
                "data-id" : val["id"],
                type:"button"
            }).text("編集"));
            var delete_btn = $('<td></td>').append($('<button></button>',{
                class : "btn btn-danger btn-sm delete_btn",
                "data-id" : val["id"],
                type:"button"
            }).text("削除"));

            // イベントセット
            modify_btn.find('button').on('click',update_event);
            delete_btn.find('button').on('click',delete_event);
            
            var data_recode = $('<tr></tr>',{
            'data-area-id':val["id"]
            }).append(name).append(email).append(age).append(created_at).append(updated_at).append(modify_btn).append(delete_btn);

            $('.data_area').append(
                data_recode
            ).hide().fadeIn(1);
            
        });
    }
    var successCallBackUpsert = function(data){

        // モーダルエラーメッセージ初期化する
        $('.modal-alert').empty();
        // モーダルを閉じる
        $('#formModal').modal('hide');

        // 一覧表示する
        setData(data['list']);

        // メッセージエリア初期化する
        $('.alert_aler').empty();
        // メッセージDOM作成
        var alertDom = getAlertDom('primary',data['message']);
        // メッセージ要素を表示する
        $('.alert_aler').append(alertDom).hide().fadeIn(300);

    }
    var errorCallBackUpsert = function(data){
        // モーダルメッセージエリア取得する
        var modalAlertDom = $('.modal-alert');
        // メッセージエリア初期化する
        modalAlertDom.empty();
        $.each(data.errors,function(index,value){
            // メッセージDOM作成と出力を行う
            var alertDom = getAlertDom('danger',value);
            modalAlertDom.append(alertDom );
            
        });
    }
    
    var upsert_event = function(event){

        var id = $('[data-modal-form="id"]').val();
        var updated_at = $('[data-modal-form="updated_at"]').val();
        var name = $('[data-modal-form="name"]').val();
        var email = $('[data-modal-form="email"]').val();
        var age = $('[data-modal-form="age"]').val();
        
        
        var type = 'post';    
        var param = 
        {
            'id' : id,
            'updated_at':updated_at,
            'name':name,
            'email':email,
            'age':age
        };
        // 通信先URL
        var actionUrl = '{{ route('sampleAsyn.ajax.ajaxUpsert') }}';

        // 通信実行
        ajax(type,actionUrl, param, successCallBackUpsert, errorCallBackUpsert)
    }
    $('.modal-footer>.submit').on('click',upsert_event);
    
    /**
	 * Modal Event
	 */
    $('[data-toggle="modal"]').on('click',function(index,value){
        $('#formModal').modal('show');
    });
    // モーダルhideメソッドを呼出イベント
    $('#formModal').on('hide.bs.modal', function (event) {
        // // Close Confirm
        // var conf = confirm("編集は保存されませんが、閉じてよろしいですか？");
        // if(conf == false){
        //     return false;
        // }

        // 入力項目初期化
        $('[data-modal-form="id"]').val([""]);
        $('[data-modal-form="updated_at"]').val([""]);
        $('[data-modal-form="name"]').val([""]);
        $('[data-modal-form="email"]').val([""]);
        $('[data-modal-form="age"]').val([""]);
	});

});
</script>
@endsection

{{-- コンテンツ部 --}}
@section('content')
<div class="row mt-3">
    <h1>{{$pageName}} マスタ</h1>
</div>
<div class="">
    <form class="form-row" method="post">
        {{ csrf_field() }}
        <div class="form-group col-sm-6">
            <label for="text4a">名前</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="なまえ" value="{{old('name')}}">
        </div>
        <div class="form-group col-sm-6">
            <label for="text4b">メール</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="メール" value="{{old('email')}}">
        </div>
        <div class="clearfix w-100">
            <button class="btn btn-info btn-sm float-right search_btn mr-3" onclick="return false;">検索</button>
        </div>
    </form>
</div>

<div class="alert_aler"></div>

<div class="alert alert-primary mt-2 d-none" role="alert">
    <span class="message_display"></span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="row mt-5">
    <div class="clearfix w-100">
        <button type="button" class="btn btn-warning btn-sm float-right" data-toggle="modal" data-target="#formModal">新規登録</button>
    </div>
</div>

<div class="row mt-3">
    <div class="table-responsive">
        <table class="table table-hover table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th class="col-xs-2">名前</th>
                    <th class="col-xs-3">メール</th>
                    <th class="col-xs-1">年齢</th>
                    <th class="col-xs-2">新規登録日</th>
                    <th class="col-xs-2">最終更新日</th>
                    <th class="col-xs-1"></th>
                    <th class="col-xs-1"></th>
                </tr>
            </thead>
            <tbody class="data_area">                        
                @foreach ($recodes as $recode)
                <tr data-area-id="{{ $recode->id }}">
                    <td>{{ $recode->name }}</td>
                    <td>{{ $recode->email }}</td>
                    <td>{{ $recode->age }}</td>
                    <td>{{ $recode->created_at }}</td>
                    <td>{{ $recode->updated_at }}</td>
                    <td><button type="button" class="btn btn-primary btn-sm update_btn" data-id="{{ $recode->id }}">編集</button></td>
                    <td><button type="button" class="btn btn-danger btn-sm delete_btn" data-id="{{ $recode->id }}">削除</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('modal')
<!-- Modal -->
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel">{{$pageName}}<span>登録</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="modal-alert"></div>
                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-25">
                        <span class="input-group-text w-100" id="inputGroup-sizing-sm">名前</span>
                    </div>
                    <input type="text" data-modal-form="name" class="form-control" aria-label="Sizing input" aria-describedby="inputGroup-sizing-sm">
                </div>

                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-25">
                        <span class="input-group-text w-100" id="inputGroup-sizing-sm">メールアドレス</span>
                    </div>
                    <input type="text" data-modal-form="email" class="form-control" aria-label="Sizing input" aria-describedby="inputGroup-sizing-sm">
                </div>

                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-25">
                        <span class="input-group-text w-100" id="inputGroup-sizing-sm">年齢</span>
                    </div>
                    <input type="number" data-modal-form="age" class="form-control" aria-label="Sizing input" aria-describedby="inputGroup-sizing-sm">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                <button type="button" class="btn btn-primary submit">保存</button>
                <input type="hidden" data-modal-form="updated_at" value="">
                <input type="hidden" data-modal-form="id" value="">
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
@endsection