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
            $table->string('firstname', 100);
            $table->string('lastname', 100);
            $table->string('username', 20)->unique();
            $table->string('password', 100);
            $table->enum('gender', ['MALE', 'FEMALE']);
            $table->string('mobile_number', 15);
            $table->string('email', 100)->unique();
            $table->string('profile_picture', 100)->nullable();
            $table->enum('login_type', ['USER', 'PROPERTY_OWNER', 'ADMIN']);
            $table->enum('status', ['ENABLE', 'DISABLE']);
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
