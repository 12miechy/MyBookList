<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;
use Validator;
use Auth;

class BooksController extends Controller
{
    // コンストラクタ
    public function __construct()
    {
        $this->middleware('auth');
    }
    // 登録画面-初期表示
    public function index(Request $request)
    {
        $keywords = $request->keywords;
        if (empty($keywords))  {
            $keywords = "";
        }

        $books = Book::where('user_id',Auth::user()->id)->where('item_name', 'like', '%'.$keywords.'%')->orderBy('created_at', 'asc')->paginate(3);
        return view('books',[
            'books' => $books,
            'keywords' => $keywords,
        ]);
    }

    // 検索
    public function search(Request $request)
    {
        // バリデーション
        $validator = Validator::make($request->all(), [
            'keywords' => 'max:10', // 仮で10文字にしてみる！
        ]);

        $keywords = $request->keywords;
        if (empty($keywords))  {
            $keywords = "";
        }

        $books = Book::where('user_id',Auth::user()->id)->where('item_name', 'like', '%'.$keywords.'%')->orderBy('created_at', 'asc')->paginate(3);
        return view('books',[
            'books' => $books,
            'keywords' => $keywords,
        ]);
    }


    // 登録
    public function store(Request $request)
    {
        // バリデーション
        $validator = Validator::make($request->all(), [
            'item_name' => 'required | min:3 | max:255',
            'item_number' => 'required | min:1 | max:3',
            'item_amount' => 'required | max:6',
            'published' => 'required',
        ]);

        // バリデーション：エラー
        if ($validator->fails()) {
            return redirect('/')
                ->withInput()
                ->withErrors($validator);
        }

        // file取得
        $file = $request->file('item_img');
        // fileが空かチェック
        if(!empty($file)) {
            // ファイル名を取得
            $filename = $file->getClientOriginalName();
            // ファイル名を取得
            $move = $file->move('./upload/',$filename); //public/upload/..
        } else {
            $filename = "";
        }

        // Eloquent モデル
        $books = new Book;
        $books->user_id = Auth::user()->id;
        $books->item_name = $request->item_name;
        $books->item_number = $request->item_number;
        $books->item_amount = $request->item_amount;
        $books->item_img = $filename;
        $books->published = $request->published;
        $books->save();
        return redirect('/')->with('flashmessage', '登録が完了しました。'); //「/」ルートにリダイレクト
    }

    // 更新画面-初期表示
    public function edit($book_id)
    {
        $books = Book::where('user_id',Auth::user()->id)->find($book_id);
        return view('booksedit', ['book'=>$books]);
    }

    // 更新
    public function update(Request $request)
    {
        // バリデーション
        $validator = Validator::make($request->all(), [
            'id' => 'required', // 登録処理と違う場所。
            'item_name' => 'required | min:3 | max:255',
            'item_number' => 'required | min:1 | max:3',
            'item_amount' => 'required | max:6',
            'published' => 'required',
        ]);

        // バリデーション：エラー
        if ($validator->fails()) {
            return redirect('/')
                ->withInput()
                ->withErrors($validator);
        }

        // Eloquent モデル(データ更新)
        $books = Book::where('user_id',Auth::user()->id)->find($request->id); // 登録処理と違う場所。
        $books->item_name = $request->item_name;
        $books->item_number = $request->item_number;
        $books->item_amount = $request->item_amount;
        $books->published = $request->published;
        $books->save(); //「/」ルートにリダイレクト
        return redirect('/')->with('flashmessage', '更新が完了しました。');
    }

    // 削除処理
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect('/')->with('flashmessage', '削除が完了しました。');
    }
}