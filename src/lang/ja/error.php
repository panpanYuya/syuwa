<?php

/*
|--------------------------------------------------------------------------
| Error Language Lines
|--------------------------------------------------------------------------
|
| The following language lines are used during authentication for various
| messages that we need to display to the user. You are free to modify
| these language lines according to your application's requirements.
|
*/

return [
    //400 error
    'bad_request' => '入力が正しくされませんでした。再度実行してください。',
    //401 error
    'unauthorized' => '認証情報が正しくないため、画面を表示できません。',
    //403 error
    'forbidden'=> 'リクエスト先のアクセス権がありません。',
    //404 error
    'not_found'=> 'リクエスト先のページが存在しません。',
    //500 error
    'server'   => 'アプリケーションでエラーが発生しました。',

    'expiration' => '有効期限が切れました。<br>再度登録をしてください。',
];
