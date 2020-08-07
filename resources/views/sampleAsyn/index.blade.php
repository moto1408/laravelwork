<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('/bootstrap/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('/js/jquery-3.5.1.js') }}"></script>
    <script src="{{ asset('/bootstrap/js/bootstrap.js') }}"></script>
    <script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.search_btn').on('click',function(event){
            var id = $(this).data('id');
            alert('検索');
            $('.data_area').empty().fadeOut(300);

            
            var data = [{id:"1",name:"太郎",email:"info+002@example.com",age:"19",created_at:"2020-08-08 04:11",updated_at:"2020-08-18 04:11"},{id:"2",name:"太郎",email:"info+002@example.com",age:"19",created_at:"2020-08-08 04:11",updated_at:"2020-08-18 04:11"}];
            $.each(data,function(index,val){
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
                var data_recode = $('<tr></tr>',{
                'data-area-id':val["id"]
                }).append(name).append(email).append(age).append(created_at).append(updated_at).append(modify_btn).append(delete_btn);
                $('.data_area').append(
                    data_recode
                ).fadeIn(300);
                console.log(index,val);
            });
            
            
            // location.href = '{{route('sample001.update')}}?id=' + id;
            return false;
        });
        $('.modify_btn').on('click',function(event){
            var id = $(this).data('id');
            
            location.href = '{{route('sample001.update')}}?id=' + id;
        });
        $('.delete_btn').on('click',function(event){
            var id = $(this).data('id');
            
            var check_flg = confirm('削除してよろしいですか？');
            if(check_flg == true){
                $.ajax(
                {
                    url: '{{ route('sample001ajax.delete') }}'
                    ,type: 'post'
                    ,data:
                    {
                        'id' : id
                    }
                    ,dataType: "json"
                    // 成功
                    
                }).done(function(data,textStatus,jqXHR){
                    console.log("成功");
                    console.log(data,textStatus,jqXHR);

                    // フェードアウトアニメーション
                    $('[data-area-id="' + id + '"]').fadeOut(500,function(){
                        $(this).remove();
                    });
                }).fail(function(jqXHR,textStatus,errorThrown){
                    console.log("失敗");
                    console.log(jqXHR,textStatus,errorThrown);
                });
            }
            
                
        });
    });
    </script>
    

    
</head>
<body>
    <div class="container">
        <div class="row">
            <h1>index page</h1>

        </div>
        <div class="">
            <form class="form-row" action="{{ route('sample001.index') }}" method="post">
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
                    <button type="submit" class="btn btn-info btn-sm float-right">検索</button>
                    <button class="btn btn-info btn-sm float-right search_btn mr-3" onclick="return false;">ajax検索</button>
                </div>
            </form>
        </div>
        <div class="row mt-5">
            <div class="clearfix w-100">
                <button type="submit" class="btn btn-warning btn-sm float-right" onclick="location.href='{{ route('sample001.add') }}';return false;">新規登録</button>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                Launch demo modal
                </button>
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
                            <td><button type="button" class="btn btn-primary btn-sm modify_btn" data-id="{{ $recode->id }}">編集</button></td>
                            <td><button type="button" class="btn btn-danger btn-sm delete_btn" data-id="{{ $recode->id }}">削除</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            ...
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>
    </div>
</body>
</html>
