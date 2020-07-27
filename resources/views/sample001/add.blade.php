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

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
        <!--
            @if (count($errors) > 0)
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        -->
        @if (!empty($method_name ))
            {{ $method_name }}
        @endif
            <table>
                <form action="{{ route('sample001.post') }}" method="post">
                    {{ csrf_field() }}

                    @if ($errors->has('name'))
                    <tr>
                        <th>ERROR</th>
                        <td>{{ $errors->first('name') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>name: </th>
                        <td>
                            <input type="text" name="name" value="{{old('name')}}">
                        </td>
                    </tr>
                    @if ($errors->has('email'))
                    <tr>
                        <th>ERROR</th>
                        <td>{{ $errors->first('email') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>mail: </th>
                        <td>
                            <input type="text" name="email" value="{{old('email')}}">
                        </td>
                    </tr>
                    @if ($errors->has('age'))
                    <tr>
                        <th>ERROR</th>
                        <td>{{ $errors->first('age') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>age: </th>
                        <td>
                            <input type="number" name="age" value="{{old('age')}}">
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><input type="submit" value="send"></td>
                    </tr>
                </form>
            </table>
        </div>
    </body>
</html>
