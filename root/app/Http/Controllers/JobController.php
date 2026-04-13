<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\Job;
use Illuminate\Support\Facades\Redirect;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //一覧画面
        $jobs =Job::orderByDesc('id')->paginate(20);
        //jobsテーブルをid降順で取得した後、1ページ20件でページング
        //getは全権取得だが、paginateは自動でページング機能がつく
        return view('admin.jobs.index',[
            'jobs'=> $jobs,
        //不明　DBは触らず空のフォーム画面を返すだけ。保存はstore()が担当
        //Laravelでは/の代わりに.でフォルダ階層を表す
        //view()の第2引数でコントローラからBladeへデータ渡す
        ]);
    }
        //view()：()内のBlade部分をHTMLに変換させる関数
        //view(Bladeの位置,Bladeに渡すデータ)
        //Laravelのコントローラーでは、自分でechoするのではなく、returnでviewを渡すのがルール

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreJobRequest  $request
     * @return \Illuminate\Http\Response
     */


    public function create()
    {
        // 新規"画面"
        return view('admin.jobs.create');
    }

    public function store(StoreJobRequest $request)
    {
        //新規"登録"
        $job = Job::create([
            'name' => $request -> name
            //nameという項目にリクエスト中のnameの値をいれる
        ]);
        return redirect(
            route('admin.jobs.show',['job' =>$job])
        )->with('messages.success','新規登録が完了しました。');
    }
    //->は後に処理をつなげる記号　オブジェクトA->メソッドB():Aというオブジェクトに対しBという処理を実行


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */

    // 詳細画面　新規画面とは違い1件のみ表示させたいのでshow関数を用いる
    //新規画面ではすでに存在しているデータがないけど、詳細画面は既存データが必要なので$jobが必要
    //Laravelが自動でＤＢからデータ取得し変数に格納：ルートモデルバインディング
    public function show(Job $job)
    //Job
    //$job
    {

        return view('admin.jobs.show', [
            'job' => $job,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        return view('admin.jobs.edit',[
            'job' => $job,
        ]);
    }

    /**
     * Update the specified resource in storage.
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
            route('admin.jobs.show',['job'=>$job])
        )->with('message.success','更新が完了しました。');
        //
    }

    /**
     * Remove the specified resource from storage.
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


//TSVダウンロード処理

    public function downloadTsv()
    //公開口。処理を組み立てレスポンスを返す。
    {
        $csvRecords = self::getJobCsvRecords();
        //   CSVストリームダウンロード
        return self::streamDownloadCsv('jobs.tsv', $csvRecords,"\t");

    }

    private static function getJobCsvRecords(): array
    //DBからデータ受取、2次元配列に変換
    {
        // id 降順で全レコード取得
        $jobs = Job::orderByDesc('id')->get();

        $csvRecords = [
            ['ID', '名称'], // ヘッダー
        ];
        foreach ($jobs as $job) {
            $csvRecords[] = [$job->id, $job->name]; // レコード
        }
        return $csvRecords;
    }

    private static function streamDownloadCsv(
    //汎用的なダウンロード処理
        string $name,
        iterable $fieldsList,
        string $separator = ',',
        string $enclosure = '"',
        string $escape = "\\",
        string $eol = "\r\n"
    ) {
        // Content-Type
        $contentType = 'text/plain'; // テキストファイル
        if ($separator === ',') {
            $contentType = 'text/csv'; // CSVファイル
        } elseif ($separator === "\t") {
            $contentType = 'text/tab-separated-values'; // TSVファイル
        }
        $headers = ['Content-Type' => $contentType];

        return response()->streamDownload(function () use ($fieldsList, $separator, $enclosure, $escape, $eol) {
            $stream = fopen('php://output', 'w');
            foreach ($fieldsList as $fields) {
                fputcsv($stream, $fields, $separator, $enclosure, $escape, $eol);
            }
            fclose($stream);
        }, $name, $headers);
    }
}