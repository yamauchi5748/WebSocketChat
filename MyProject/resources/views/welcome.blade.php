
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="api-token" content="{{ $user->api_token }}">
    <title>Vue table</title>
    <link href="{{mix('css/app.css')}}" rel="stylesheet">
</head>
<body>
    <div id="app" class="container-fluid">
        <my-chat
            :user_id={{ $user->id }}
        ></my-chat>
    </div>
    User
    {{ $user }}
    <script src="{{mix('js/app.js')}}"></script>
</body>
</html>