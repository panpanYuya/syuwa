<?php

namespace App\Http\Controllers\API\Auth;

use App\Consts\ErrorMessageConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\UserInfoEditRequest;
use App\Models\Users\TmpUserRegistration;
use App\Models\Users\User;
use App\Services\RegistUserService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class UserInfoEditController extends Controller
{

    private RegistUserService $registUserService;
    private UserService $userService;

    public function __construct(
        RegistUserService $registUserService,
        UserService $userService
    ) {
        $this->registUserService = $registUserService;
        $this->userService = $userService;
    }


    /**
     * ユーザー編集画面表示
     *
     * @param integer $userId
     * @return void
     */
    public function userInfoEdit(int $userId)
    {
        $loginId = Auth::id();
        if ($userId !== $loginId) {
            return response()->json([
                'result' => false,
            ], 500);
        }
        $user = $this->userService->findUser($loginId);
        return response()->json([
            'result' => true,
            'User' => $user,
        ], 200);

    }

    /**
     * ユーザー情報の更新、又はメールアドレス変更認証用メール送信
     *
     * @param UserInfoEditRequest $request
     * @return void
     */
    public function userInfoUpdate(UserInfoEditRequest $request)
    {
        $loginId = Auth::id();
        if ($request->user_id != $loginId) {
            return response()->json([
                'result' => false,
            ], 500);
        }
        $user = $this->userService->findUser($loginId);

        //メールアドレスの変更を確認
        $password = $user->password;
        if (!empty($request->password)) {
            $password = $this->registUserService->hashedPassword($request->password);
        }

        $message ="";
        if ($request->email == $user->email) {
            $newUserModel = $this->createUserForm($request->user_name, $request->email, $password);
            $this->userService->updateUser($newUserModel);
            $message = "個人情報の変更が完了しました。";
        } else {
            //requestでバリデーションがうまく実行されなかったため、判定処理を追加
            $registedTmpUser = $this->registUserService->findTmpUserByEmail($request->email);
            if (!empty($registedTmpUser)) {
                return response()->json([
                    'result' => false,
                    'message' => "指定のメールアドレスは既に使用されています。"
                ], 422);
            }
            $newTmpUserModel = $this->createTmpUserForm($request->user_name, $request->email, $password, $user->birthday);
            $this->registUserService->createTmpUser($newTmpUserModel);
            $this->registUserService->sendCertifyNewAddressMail($newTmpUserModel->email, $newTmpUserModel->token);
            $message = "メールを送信しました。メールから個人情報変更を完了させてください。";
        }

        return response()->json([
            'result' => true,
            'message'=> $message
        ], 200);

    }

    /**
     * メールアドレス変更の処理を完了
     *
     * @param string $token
     * @return void
     */
    public function completedEmailCertification(string $token)
    {
        $tmpUser = $this->registUserService->findTmpUserByToken($token);
        if (!$this->registUserService->checkExpirationDate($tmpUser->updated_at)) {
            $this->registUserService->deleteRegistedTmpUser($tmpUser);
            return response()->json([
                'result' => 'error',
                'message' => ErrorMessageConst::EXPIRATION_DATE,
            ], 200);
        }

        $user = $this->createUserFromTmpUser($tmpUser);
        $this->userService->updateUser($user);
        $this->registUserService->deleteRegistedTmpUser($tmpUser);
        return response()->json([
            'result' => 'success'
        ], 200);
    }

    /**
     * 新しいユーザーモデルを作成
     *
     * @param string $userName
     * @param string $email
     * @param string $password
     * @return User
     */
    private function createUserForm(string $userName, string $email, string $password):User
    {
        $user = new User();
        $user->id = Auth::id();
        $user->user_name = $userName;
        $user->email = $email;
        $user->password = $password;

        return $user;
    }

    /**
     * 新しい仮登録ユーザーモデルを作成
     *
     * @param string $userName
     * @param string $email
     * @param string $password
     * @return TmpUserRegistration
     */
    private function createTmpUserForm(string $userName, string $email, string $password, string $birthday): TmpUserRegistration
    {
        $tmpUser = new TmpUserRegistration();
        $tmpUser->user_id = Auth::id();
        $tmpUser->user_name = $userName;
        $tmpUser->email = $email;
        $tmpUser->birthday = $birthday;
        $tmpUser->password = $password;
        $tmpUser->token = $this->registUserService->createTmpToken();

        return $tmpUser;
    }

    /**
     * 仮登録用ユーザーのモデルをユーザーモデルに変換
     *
     * @param TmpUserRegistration $tmpUser
     * @param string $password
     * @return User
     */
    private function createUserFromTmpUser(TmpUserRegistration $tmpUser): User
    {
        $user = new User();
        $user->id = $tmpUser->user_id;
        $user->user_name = $tmpUser->user_name;
        $user->email = $tmpUser->email;
        $user->password = $tmpUser->password;
        $user->birthday = $tmpUser->birthday;

        return $user;
    }
}
