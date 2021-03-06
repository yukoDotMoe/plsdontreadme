<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique()->default('');
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->float('credit');
            $table->float('total_paypal_credit');
            $table->text('wallet_code');
            $table->integer('user_debt');
            $table->string('type', 191);
            $table->timestamp('email_verified_at');
            $table->integer('ref_user_id');
            $table->integer('user_ref_count');
            $table->integer('user_ref_commission');
            $table->string('password');
            $table->string('avatar', 255)->nullable();
            $table->integer('role_member');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
