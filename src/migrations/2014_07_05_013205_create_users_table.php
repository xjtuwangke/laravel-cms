<?php
/**
 * php artisan migrate:make create_users_table
 * php artisan migrate
 *
 * 创建 tables:
 * users  普通用户信息
 * user_profile 用户profile
 * admins 管理员信息
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Xjtuwangke\LaravelModels\Rbac\AdminUserModel;
use Xjtuwangke\LaravelModels\Rbac\RoleModel;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        //创建admins表
        Schema::create( AdminUserModel::getTableName() , function( Blueprint $table ){
            $table->engine = 'InnoDB';
            $table->increments( 'id' );
            $table->string( 'username' , 100 )->unique();
            $table->string( 'employee_id' , 100 )->unique();
            $table->string( 'email' , 100 )->unique();
            $table->string( 'mobile' , 20 )->unique();
            $table->string( 'avatar' , 200 )->nullable();
            $table->string( 'password' , 100 );
            $table->string( 'remember_token' , 100 );
            AdminUserModel::_schema( $table );
            $table->timestamp( 'last_login' );
            $table->softDeletes();
            $table->timestamps();
        });

        //创建roles表
        Schema::create( RoleModel::getTableName() , function( Blueprint $table ){
            $table->engine = 'InnoDB';
            $table->increments( 'id' );
            RoleModel::_schema( $table );
            $table->softDeletes();
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
		//
        Schema::dropIfExists( RoleModel::getTableName() );
        Schema::dropIfExists( AdminUserModel::getTableName() );
	}

}
