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
    public function index()
    {
        #memo ページネーションはget()でハンク、pginatite(x)を使う。
        #変更前:$books = Book::orderBy('created_at', 'asc')->get();
        $books = Book::where('user_id',Auth::user()->id)->orderBy('created_at', 'asc')->paginate(3);
        return view('books',[
            'books' => $books
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
        return redirect('/'); //「/」ルートにリダイレクト
    }
    
    // 更新画面-初期表示
    public function edit($book_id)
    {
        // {books} id値を取得 => Book $books id値の１レコード取得
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
        return redirect('/');
    }
    
    // 削除処理
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect('/');
    }
}
