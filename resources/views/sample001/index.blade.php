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
                    <button type="submit" class="btn btn-info float-right">検索</button>
                </div>
            </form>
        </div>
        <div class="row mt-5">
            <div class="clearfix w-100">
                <button type="submit" class="btn btn-warning float-right" onclick="location.href='{{ route('sample001.add') }}';return false;">新規登録</button>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>名前</th>
                            <th>メール</th>
                            <th>年齢</th>
                            <th>新規登録日</th>
                            <th>最終更新日</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>                        
                        @foreach ($recodes as $recode)
                        <tr data-area-id="{{ $recode->id }}">
                            <td>{{ $recode->name }}</td>
                            <td>{{ $recode->email }}</td>
                            <td>{{ $recode->age }}</td>
                            <td>{{ $recode->created_at }}</td>
                            <td>{{ $recode->updated_at }}</td>
                            <td><button type="button" class="btn btn-primary modify_btn" data-id="{{ $recode->id }}">編集</button></td>
                            <td><button type="button" class="btn btn-danger delete_btn" data-id="{{ $recode->id }}">削除</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
