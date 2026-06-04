<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\Job;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
class JobController extends Controller
//求人データの操作担当クラス
{
    /**
     * Display a listing of the resource.(求人データの操作担当)
     * @return \Illuminate\Http\Response
     */

    //デフォルトの一覧表示 */
    // public function index()
    // {
    //     $jobs = Job::orderByDesc('id')->paginate(20);
    //     //jobsテーブルをid降順で取得した後、1ページ20件でページング
    //     //getは全権取得だが、paginateは自動でページング機能がつく
    //     return view('admin.jobs.index', [
    //         'jobs' => $jobs,
    //         /**
    //      * view('どのBladeを使うか','Bladeに渡すデータ')
    //      * Blade側ではデータは$jobsという名前で使える(配列キー名がそのままBlade変数名になる)
    //      */
    //     ]);
    // }

    /**
     * Store a newly created resource in storage.(新規作成フォーム表示)
     *
     * @param  \App\Http\Requests\StoreJobRequest
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //create.blade.php(新規画面)をHTML変換してブラウザに変える
        //Laravelのビュー呼び出しルールでドットがフォルダ区切りになっている
        return view('admin.jobs.create');
    }
    /**
     * DBへ新規登録
     *
     */

    public function store(StoreJobRequest $request)
    {
        $job = Job::create([
            'name' => $request->name
            //nameというカラムに$requestを追加。この時点でバリデーションが実施されている。
            //$requestはnameメソッドで処理
        ]);
        return redirect(
            route('admin.jobs.index')
            // route('admin.jobs.show',['job' =>$job])
        )->with('messages.success', '新規登録が完了しました。');
        //redirectで保存完了後、詳細画面(show)にリダイレクト
        //['job'=>$job]で「どのレコードの詳細画面か」をURLに埋め込む
    }

    /**
     * Display the specified resource.(1件詳細表示)
     *
     * @param  \App\Models\Job
     * @return \Illuminate\Http\Response
     * 詳細画面　新規画面とは違い1件のみ表示させたいのでshow関数を用いる
     * 新規画面ではすでに存在しているデータがないけど、詳細画面は既存データが必要なので$jobが引数に必要
     * Laravelが自動でＤＢからデータ取得し変数に格納：ルートモデルバインディング
     */

    public function show(Job $job)
    {
        return view('admin.jobs.show', [
            'job' => $job,
        ]);
    }

    /**
     * Show the form for editing the specified resource.(編集フォーム表示)
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        return view('admin.jobs.edit', [
            'job' => $job,
        ]);
    }

    /**
     * Update the specified resource in storage.(DB更新)
     *
     * @param  \App\Http\Requests\UpdateJobRequest  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateJobRequest $request, Job $job)
    {
        $job->name = $request->name;
        $job->update();
        return redirect(
            route('admin.jobs.show', ['job' => $job])
        )->with('message.success', '更新が完了しました。');
        //
    }

    /**
     * Remove the specified resource from storage.(DB削除)
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        $job->delete();
        return Redirect(route('admin.jobs.index'));
        //削除が終われば一覧画面に戻る
    }

    /**
     * TSVダウンロード(公開口)
     *
     * @return void
     */

    public function downloadTsv()
    //公開口。処理を組み立てレスポンスを返す。
    {
        $csvRecords = self::getJobCsvRecords();
        return self::streamDownloadCsv('jobs.tsv', $csvRecords, "\t");
        //streamDownloadCsv:TSVファイル生成してブラウザにデータをダウンロードさせる。
    }
    /**
     * CSVダウンロード(公開口)
     *
     * @return void
     */
    public function downloadCsv()
    {
        $csvRecords = self::getJobCsvRecords();
        return self::streamDownloadCsv('jobs.csv', $csvRecords, ',');
    }

    /**
     * DBからデータ取得→2次元配列変換
     *
     * @return array
     */
    private static function getJobCsvRecords(): array
    //DBからデータ受取、2次元配列に変換
    {
        // id 降順で全レコード取得
        $jobs = Job::orderByDesc('id')->get();

        $csvRecords = [
            ['ID', '名称'], // ヘッダーを指定（二次元配列では添え字０の行になる）
        ];
        foreach ($jobs as $job) {
            $csvRecords[] = [$job->id, $job->name];
            // レコード $jobs->idはオブジェクトのプロパティにアクセスしている
            //ヘッダーがなぜ上書きされないの
        }
        return $csvRecords;
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    /**
     *ストリームダウンロード処理(汎用)
     *
     */
    private static function streamDownloadCsv(
        //汎用的なダウンロード処理
        string $name,
        iterable $fieldsList,
        string $separator = ',',
        string $enclosure = '"',
        string $escape = "\\",
        string $eol = "\r\n"
        //$separator,$enclosure, $eolが何で\rじゃなくて\r\nなのか
    ) {
        // Content-Type
        $contentType = 'text/plain';
        /**
         * MIMEタイプという規格　
         * 'text/csv'→CSVファイルの標準MIMEタイプ
         * 'text/tab-separated-values'→TSVの標準MIMEタイプ
         * 'text/plain'→それ以外のテキストファイル
         */
        if ($separator === ',') {
            $contentType = 'text/csv'; // CSVファイル
        } elseif ($separator === "\t") {
            $contentType = 'text/tab-separated-values'; // TSVファイル
        }
        $headers = ['Content-Type' => $contentType];


        return response()->streamDownload(function () use ($fieldsList, $separator, $enclosure, $escape, $eol) {
            $stream = fopen('php://output', 'w');
            /**
             * fopen():ファイルを開く関数
             * fopen(開く対象,モード) ※w:書き込みモード/a:追記/r:読み込みモード
             * php://output ファイルパスではなく、PHPの特殊ストリーム　ここへ書き込むと画面出力へ流れる
             */
            foreach ($fieldsList as $fields) {
                fputcsv($stream, $fields, $separator, $enclosure, $escape, $eol);
                //fputcsv:CSV形式で1行書き出す関数/どこに書くか,1行分のデータ,区切り文字,囲み文字,エスケープ文字,行末文字の順に
            }
            fclose($stream);
            //fopenでの処理を終了させる
        }, $name, $headers);
        /**
         * $nameと$headersはstreamDownload()の第二、第三引数
         * streamDownload():何を何という名前でどんな形式でダウンロードするかを決める関数
         * 第一引数:クロージャで何をダウンロードさせるか中身を定義
         * 第二引数:ダウンロード時のファイル名定義
         * 第三引数:HTTPレスポンスヘッダー(ブラウザにファイル形式を伝えている)
         */
    }

    //検索キーワードあれば絞り込み求人を無ければ全件を表示
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        //input():リクエストから値を取り出す関数
        //フォームやURLパラメータからkeywordという値を取り出して$keywordへ入れる
        $jobs = Job::orderByDesc('id')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            })
            //when():条件(第一引数)が真のとき第二引数の処理を行う
            //where():SQLのWHERE句を組み立てる関数
            //where('カラム名','演算子','値')
            ->paginate(20);
            //20件ずつに分割して現在のページ文取得する

        return view('admin.jobs.index',[
            'jobs' => $jobs,
            'keyword' => $keyword,
        ]);
    }
}
