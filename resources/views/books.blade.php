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
                    <label for="book" class="col-sm-3 control-label">Book</label>
                    <input type="text" name="item_name" id="book-name" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label for="number" class="col-sm-3 control-label">数</label>
                    <input type="text" name="item_number" id="book-number" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label for="amount" class="col-sm-3 control-label">金額</label>
                    <input type="text" name="item_amount" id="book-amount" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label for="published" class="col-sm-3 control-label">公開日</label>
                    <input type="datetime" name="published" id="published" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label>画像</label>
                    <input type="file" name="item_img">
                </div>
            </div>

            <!-- 本 登録ボタン -->
            <div class="form-group">
                <div class="col-sm-offset-3 con-sm-6">
                    <button type="submit" class="btn btn-default">
                        <i class="glyphicon glyphicon-plus" aria-hidden="true"></i> Save
                    </button>
                </div>
            </div>
        </form>

        <!-- 現在の本 -->
        @if(count($books)>0)
            <div class="panel panel-default">
                <div class="panel-heading">
                    現在の本
                </div>
            </div>
            <div class="panel-body">
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
                                    <form action="{{url ('books/'.$book->id )}}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <button type="submit" class="btn btn-danger">
                                            <i class="glyphicon glyphicon-trash"></i>削除
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
            <!-- paginate -->
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    {{ $books->links() }}
                </div>
            </div>
        @endif
        <!-- memo @ endif を書き忘れるとエラーが発生する。後で再現。-->
    </div>
    <!-- Book: 既に登録されている本のリスト -->
@endsection
