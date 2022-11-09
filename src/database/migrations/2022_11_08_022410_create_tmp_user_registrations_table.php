<?php

use App\Models\Users\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmp_user_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->comment('ユーザーid');
            $table->string('user_name', 100)->comment('ユーザーネーム');
            $table->string('email', 255)->unique()->comment('メールアドレス');
            $table->string('password', 255)->comment('パスワード');
            $table->date('birthday')->comment('誕生日');
            $table->string('token', 16)->comment('仮登録トークン');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tmp_user_registrations');
    }
};
