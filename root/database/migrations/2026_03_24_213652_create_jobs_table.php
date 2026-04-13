<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Migration:PHPのコードでDBのテーブル構造を定義し、コマンド一発で作成・変更できる仕組み
     *
     * @return void
     * この関数は何も返さないというメモ
     *
     * @return string　　文字列を返す
     * @return int　　　数値を返す
     *
     */
    public function up()
    {
        $tableName = 'jobs';
        Schema::create($tableName, function (Blueprint $table) {
            // 第2引数としてコールバック関数と、クラスBlueprint（型宣言）が渡されている
            //コールバック関数：「あとで処理する関数」関数を呼ばず、渡している。
            $table->id() -> comment('ID');
            $table->string('name') -> comment('名称');
            $table->softDeletes() -> comment('消去日時');
            $table->timestamp('created_at') -> nullable() -> comment('作成日時');
            $table->timestamp('updated_at') -> nullable() -> comment('更新日時');
        });
        DB::statement("ALTER TABLE {$tableName} COMMENT '職業'");
        //statementの引数は任意のSQL文１つ
        // テーブル自体にコメント追加
    }

    /**
     * 「テーブルが存在すれば削除する」 メソッド
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};
