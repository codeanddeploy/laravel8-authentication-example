<?php

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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('authy_status')->nullable()->after('password');
            $table->string('authy_id', 25)->after('authy_status');
            $table->string('authy_country_code', 10)->after('authy_id');
            $table->string('authy_phone')->after('authy_country_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('authy_status');
            $table->dropColumn('authy_id', 25);
            $table->dropColumn('authy_country_code', 10);
            $table->dropColumn('authy_phone');
        });
    }
};
