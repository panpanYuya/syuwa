<?php

namespace App\Consts;


class ErrorMessageConst
{

    /**
     * エラーごとのメッセージ定数
     */
    // 400:
    const BAD_REQUEST = '入力が正しくされませんでした。再度実行してください。';
    // 401:
    const UNAUTHORIZED = '認証情報が正しくないため、画面を表示できません。';
    // 403:
    const FORBIDDEN = 'リクエスト先のアクセス権がありません。';
    // 404:
    const NOT_FOUND = 'リクエスト先のページが存在しません。';
    // 500:
    const INTERNAL_SERVER_ERROR = 'サーバー内でエラーが発生しました。';


    //有効期限切れ
    const EXPIRATION_DATE = '有効期限が切れました。 再度登録をしてください。';
}
