<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/mail-result.css') }}" type="text/css">
    <title>期限切れ</title>
</head>

<body>
    <div class="wrapper">
        <div class="title">
            <p>期限切れ</p>
        </div>
        <div class="message">
            <p>仮登録から24時間以上経過してしまいました。</p>
            <p>再度登録画面から必要情報を入力してください。</p>
        </div>
        <div class="url">
            <a href="http://localhost:4200/auth/create/regist">こちらから再度必要情報を入力してください。</a>
        </div>
    </div>
</body>

</html>
