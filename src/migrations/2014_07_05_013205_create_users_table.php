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
            AdminUserModel::_schema( $table );
        });

        //创建roles表
        Schema::create( RoleModel::getTableName() , function( Blueprint $table ){
            RoleModel::_schema( $table );
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
