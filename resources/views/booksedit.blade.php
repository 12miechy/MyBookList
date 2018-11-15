@extends('layouts.app')

@section('content')

    <!-- Bootstrapの定形コード… -->
    <div class="col-md-12">
        <!-- バリデーションエラーの表示 -->
        @include('common.errors')
        <form action="{{url("books/update")}}" method="POST">
            <!-- item_name -->
            <div class="form-group">
                <label for="item_name">Title</label>
                <input type="text" id="itme_name" name="item_name" class="form-control" value="{{$book->item_name}}">

                <label for="item_number">Number</label>
                <input type="text" id="itme_number" name="item_number" class="form-control" value="{{$book->item_number}}">

                <label for="item_number">Amount</label>
                <input type="text" id="itme_amount" name="item_amount" class="form-control" value="{{$book->item_amount}}">

                <label for="item_number">Published</label>
                <input type="datetime" id="published" name="published" class="form-control" value="{{$book->published}}">

                <!-- Saveボタン/Backボタン -->
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a class="btn btn-link pull-right" href="{{url('/')}}">
                        <i class="glyphicon glyphicon-backward"></i>Back
                    </a>
                </div>

                <!-- id値を送信 -->
                <input type="hidden" name="id" value="{{$book->id}}" />

                <!-- CSRF -->
                {{ csrf_field() }}

            </div>
        </form>
    </div>
@endsection