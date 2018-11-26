@extends('layouts.app')
@section('content')
    <!-- resources/views/books.blade.php -->
    <!-- Bootstrapの定形コード -->

    <div class="panel-body">
        <!-- バリデーションエラーの表示に使用 -->
    @include('common.errors')

    <!-- 本登録フォーム -->
        <form enctype="multipart/form-data" action="{{ url('books') }}" method="POST" class="form-horizontal">
        {{ csrf_field() }}

        <!-- 本のタイトル -->
            <div class="form-group">
                <div class="col-sm-6">
                    <label for="book" class="col-sm-3 control-label" style="text-align: left;"/>書籍名</label>
                    <input type="text" name="item_name" id="book-name" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label for="number" class="col-sm-3 control-label" style="text-align: left;">冊数</label>
                    <input type="text" name="item_number" id="book-number" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label for="amount" class="col-sm-3 control-label" style="text-align: left;">金額</label>
                    <input type="text" name="item_amount" id="book-amount" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label for="published" class="col-sm-3 control-label" style="text-align: left;">公開日</label>
                    <input type="datetime" name="published" id="published" class="form-control">
                </div>
                <div class="col-sm-6" style="padding-top: 10px;">
                    <label for="published" class="col-sm-2 control-label" style="text-align: left;">画像</label>
                    <div style="padding-top: 5px;">
                        <input type="file" name="item_img">
                    </div>
                </div>

                <!-- 本 登録ボタン -->
                <div class="col-sm-6" style="padding-top: 10px;">
                    <button type="submit" class="btn btn-default">
                        <i class="glyphicon glyphicon-plus" aria-hidden="true"></i>登録
                    </button>
                </div>
            </div>
        </form>

        <hr>
        <form enctype="multipart/form-data" action="{{ url('bookssearch') }}" method="GET" class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-6">
                    {{ csrf_field() }}
                    <label for="book" class="col-sm-3 control-label" style="text-align: left;">書籍名</label>
                    <input type="text" name="keywords" id="keywords" class="form-control" value="{{$keywords}}" placeholder='キーワード検索'>
                </div>
                <div class="col-sm-6" style="padding-top: 30px;">
                    <button type="submit" class="btn btn-success">
                        <i class="glyphicon glyphicon-search" aria-hidden="true"></i>検索
                    </button>
                </div>
            </div>
        </form>

        @if(count($books)>0)
            <div class="panel-body" style="padding-top: 30px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        現在の本
                    </div>
                </div>

                <table class="table table-striped task-table">
                    <!-- TODO class task-tableが入力補完候補に出てこなかった。存在する？ -->
                    <!-- テーブルヘッダ -->
                    <thead>
                    <th>本一覧</th>
                    <th>&nbsp;</th>
                    </thead>
                    <!-- テーブル本体 -->
                    <tbody>
                    @foreach($books as $book)
                        <tr>
                            <!--本タイトル -->
                            <td class="table-text">
                                <div>{{$book->item_name}}</div>
                                <div><img src="upload/{{$book->item_img}}" width="100"></img></div>
                            </td>
                            <!-- 本:更新ボタン -->
                            <td>
                                <form action="{{ url('booksedit/'.$book->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-primary">
                                        <i class="glyphicon glyphicon-pencil"></i>更新
                                    </button>
                                </form>
                            </td>

                            <!-- 本:削除ボタン -->
                            <td>
                                <form action="{{url ('books/'.$book->id )}}" id="form_{{ $book->id }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                <!-- button type="submit" class="btn btn-danger" data-id="{{ $book->id }}" onclick="deletePost(this);">
                                            // TODO buttonタグだと削除確認ポップアップでキャンセルを洗濯しても削除されてしまう。
                                            <i class="glyphicon glyphicon-trash"></i>削除
                                        </button-->
                                    <a href="#" data-id="{{ $book->id }}" class="glyphicon glyphicon-trash btn btn-danger" onclick="deletePost(this);">削除</a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
                <!-- paginate -->
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                    <!-- {{ $books->links() }} -->
                        {{ $books->appends(Request::only('keywords'))->links() }}
                    </div>
                </div>
            </div>
    @endif
    <!-- memo @ endif を書き忘れるとエラーが発生する。後で再現。-->
    </div>
    <!-- Book: 既に登録されている本のリスト -->

    @if(Session::has('flashmessage'))
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
        <script>
            $(window).load(function() {
                $('#modal_box').modal('show');
            });
        </script>

        <!-- モーダルウィンドウの中身 -->
        <div class="modal fade" id="modal_box" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Information</h4>
                    </div>
                    <div class="modal-body">
                        {{ session('flashmessage') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function deletePost(e) {
            'use strict';

            if (confirm('本当に削除していいですか?')) {
                document.getElementById('form_' + e.dataset.id).submit();
            }else{
                //cancel
                return false;
            }
        }
    </script>
@endsection
