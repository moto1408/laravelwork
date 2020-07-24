<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="./bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
        <script src="./js/jquery-3.5.1.js"></script>
        <script src="./bootstrap/js/bootstrap.js"></script>
        
        

        
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
                <button type="submit" class="btn btn-primary float-right" onclick="location.href='{{ route('sample001.add') }}';return false;">新規登録</button>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>編集</th>
                            <th>名前</th>
                            <th>メール</th>
                            <th>年齢</th>
                            <th>新規登録日</th>
                            <th>最終更新日</th>
                        </tr>
                    </thead>
                    <tbody>                        
                        @foreach ($recodes as $recode)
                        <tr>
                            <td><button type="button" class="btn btn-warning" onclick="alert('{{ $recode->id }}');">編集</button></td>
                            <td>{{ $recode->name }}</td>
                            <td>{{ $recode->email }}</td>
                            <td>{{ $recode->age }}</td>
                            <td>{{ $recode->created_at }}</td>
                            <td>{{ $recode->updated_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
