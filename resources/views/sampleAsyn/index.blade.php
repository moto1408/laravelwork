<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$pageName}}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('/bootstrap/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('/js/jquery-3.5.1.js') }}"></script>
    <script src="{{ asset('/bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('/js/common.js') }}"></script>
    <script src="{{ asset('\sampleAsyn\js\index.js') }}"></script>
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
        // $('.search_btn').on('click',function(event){
        //     var name = $('#name').val();
        //     var email = $('#email').val();
            
        //     $('.data_area').empty().fadeOut(300);

        //     $.ajax(
        //     {
        //         url: '{{ route('sampleAsyn.ajax.ajaxSearch') }}'
        //         ,type: 'post'
        //         ,data:
        //         {
        //             'name'  : name,
        //             'email' : email
        //         }
        //         ,dataType: "json"
        //         // 成功
                
        //     }).done(function(data,textStatus,jqXHR){
        //         console.log("成功");
        //         console.log(data,textStatus,jqXHR);
        //         setData(data);
                
        //     }).fail(function(jqXHR,textStatus,errorThrown){
        //         console.log("失敗");
        //         console.log(jqXHR,textStatus,errorThrown);
        //     });
        //     // var data = [{id:"1",name:"太郎",email:"info+002@example.com",age:"19",created_at:"2020-08-08 04:11",updated_at:"2020-08-18 04:11"},{id:"2",name:"B太郎",email:"info+009@example.com",age:"29",created_at:"2020-08-08 04:11",updated_at:"2020-08-18 04:11"}];
        //     // setData(data);
        //     // $.each(data,function(index,val){
        //     //     var name = $('<td></td>',{class:"col-xs-2"}).text(val["name"]);
        //     //     var email = $('<td></td>',{class:"col-xs-2"}).text(val["email"]);
        //     //     var age = $('<td></td>',{class:"col-xs-1"}).text(val["age"]);
        //     //     var created_at = $('<td></td>',{class:"col-xs-2"}).text(val["created_at"]);
        //     //     var updated_at = $('<td></td>',{class:"col-xs-2"}).text(val["updated_at"]);
        //     //     var modify_btn = $('<td></td>',{class:"col-xs-1"}).append($('<button>',{
        //     //         class : "btn btn-primary btn-sm modify_btn",
        //     //         "data-id" : val["id"],
        //     //         type:"button"
        //     //     }).text("編集"));
        //     //     var delete_btn = $('<td></td>',{class:"col-xs-2"}).append($('<button></button>',{
        //     //         class : "btn btn-danger btn-sm delete_btn",
        //     //         "data-id" : val["id"],
        //     //         type:"button"
        //     //     }).text("削除"));
        //     //     var data_recode = $('<tr></tr>',{
        //     //     'data-area-id':val["id"]
        //     //     }).append(name).append(email).append(age).append(created_at).append(updated_at).append(modify_btn).append(delete_btn);
        //     //     $('.data_area').append(
        //     //         data_recode
        //     //     ).fadeIn(300);
        //     //     console.log(index,val);
        //     // });
            
            
        //     // location.href = '{{route('sample001.update')}}?id=' + id;
        //     return false;
        // });
        
        
        
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
            
            location.href = '{{route('sample001.update')}}?id=' + id;
        }
        $('.search_btn').on('click',search_event)
        $('.update_btn').on('click',update_event);
        $('.delete_btn').on('click',delete_event);

        function setData(data){
            $.each(data,function(index,val){
                // レコード情報をDOM要素にセットする
                var name = $('<td></td>',{class:"col-xs-2"}).text(val["name"]);
                var email = $('<td></td>',{class:"col-xs-2"}).text(val["email"]);
                var age = $('<td></td>',{class:"col-xs-1"}).text(val["age"]);
                var created_at = $('<td></td>',{class:"col-xs-2"}).text(val["created_at"]);
                var updated_at = $('<td></td>',{class:"col-xs-2"}).text(val["updated_at"]);
                var modify_btn = $('<td></td>',{class:"col-xs-1"}).append($('<button>',{
                    class : "btn btn-primary btn-sm modify_btn",
                    "data-id" : val["id"],
                    type:"button"
                }).text("編集"));
                var delete_btn = $('<td></td>',{class:"col-xs-2"}).append($('<button></button>',{
                    class : "btn btn-danger btn-sm delete_btn",
                    "data-id" : val["id"],
                    type:"button"
                }).text("削除"));

                // イベントセット
                modify_btn.on('click',update_event);
                delete_btn.on('click',delete_event);
                
                var data_recode = $('<tr></tr>',{
                'data-area-id':val["id"]
                }).append(name).append(email).append(age).append(created_at).append(updated_at).append(modify_btn).append(delete_btn);

                $('.data_area').append(
                    data_recode
                ).hide().fadeIn(1);
            });
        }
    });
    </script>
    

    
</head>
<body>
    <div class="container">
        <div class="row">
            <h1>index page</h1>

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
    </div>
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

                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-25">
                            <span class="input-group-text w-100" id="inputGroup-sizing-sm">名前</span>
                        </div>
                        <input type="text" id="form_name" class="form-control" aria-label="Sizing input" aria-describedby="inputGroup-sizing-sm">
                    </div>

                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-25">
                            <span class="input-group-text w-100" id="inputGroup-sizing-sm">メールアドレス</span>
                        </div>
                        <input type="text" id="form_email" class="form-control" aria-label="Sizing input" aria-describedby="inputGroup-sizing-sm">
                    </div>

                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-25">
                            <span class="input-group-text w-100" id="inputGroup-sizing-sm">年齢</span>
                        </div>
                        <input type="number" id="form_age" class="form-control" aria-label="Sizing input" aria-describedby="inputGroup-sizing-sm">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                    <button type="button" class="btn btn-primary">保存</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
