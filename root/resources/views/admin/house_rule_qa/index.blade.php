@extends('layouts.admin')
@section('title', 'ハウスルール Q&A')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: #2d6a4f; color: white;">
                    <h5 class="mb-0">ハウスルール Q&A</h5>
                </div>
                <div class="card-body">
                    <!-- 質問フォーム -->
                    <form id="qaForm">
                        @csrf
                        <div class="form-group">
                            <label for="question" class="font-weight-bold">質問を入力してください</label>
                            <textarea
                                id="question"
                                name="question"
                                class="form-control"
                                rows="4"
                                placeholder="例：ゲストの宿泊は何日までですか？"
                                required
                            ></textarea>
                            <small class="form-text text-muted mt-2">
                                ハウスルール内の質問にはAIが自動で回答します。
                            </small>
                        </div>

                        <div class="d-flex justify-content-center gap-2">
                            <button type="submit" class="btn btn-lg" style="background-color: #2d6a4f; color: white; border: none;">
                                <span data-feather="send" style="width: 18px; height: 18px; margin-right: 6px;"></span>
                                質問する
                            </button>
                        </div>
                    </form>

                    <!-- ローディング表示 -->
                    <div id="loading" class="alert alert-info mt-4" role="alert" style="display: none;">
                        <div class="spinner-border spinner-border-sm mr-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        回答を取得中...
                    </div>

                    <!-- 回答表示 -->
                    <div id="response" class="alert alert-success mt-4" role="alert" style="display: none;">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="alert-heading">
                                    <span data-feather="help-circle" style="width: 20px; height: 20px; margin-right: 6px; vertical-align: middle;"></span>
                                    質問
                                </h5>
                                <p class="mb-0" id="responseQuestion"></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h5 class="alert-heading">
                                    <span data-feather="check-circle" style="width: 20px; height: 20px; margin-right: 6px; vertical-align: middle;"></span>
                                    回答
                                </h5>
                                <p class="mb-0" id="responseAnswer" style="line-height: 1.8;"></p>
                            </div>
                        </div>
                        <hr>
                        <small id="responseTime" class="text-muted"></small>
                    </div>

                    <!-- エラー表示 -->
                    <div id="error" class="alert alert-danger mt-4" role="alert" style="display: none;">
                        <span data-feather="alert-circle" style="width: 20px; height: 20px; margin-right: 6px; vertical-align: middle;"></span>
                        <span id="errorMessage"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.getElementById('qaForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const question = document.getElementById('question').value;

        // UI表示切替
        document.getElementById('loading').style.display = 'block';
        document.getElementById('response').style.display = 'none';
        document.getElementById('error').style.display = 'none';

        try {
            const response = await fetch('{{ route("admin.house_qa.ask") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify({ question }),
            });

            const data = await response.json();

            if (response.ok) {
                document.getElementById('responseQuestion').textContent = data.question;
                document.getElementById('responseAnswer').textContent = data.answer;
                document.getElementById('responseTime').textContent =
                    '回答時刻: ' + new Date(data.timestamp).toLocaleString('ja-JP');

                document.getElementById('response').style.display = 'block';
                document.getElementById('question').value = '';
            } else {
                const errorMsg = data.message || '入力内容を確認してください';
                document.getElementById('errorMessage').textContent = errorMsg;
                document.getElementById('error').style.display = 'block';
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('errorMessage').textContent =
                'エラーが発生しました。もう一度試してください。';
            document.getElementById('error').style.display = 'block';
        } finally {
            document.getElementById('loading').style.display = 'none';
        }
    });
</script>
@endsection