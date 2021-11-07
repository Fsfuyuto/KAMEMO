<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// DBへのアクセスはモデルを通じて行う→Memoモデルをインポート
use App\Models\Memo;
use App\Models\Tag;
use App\Models\Memotag;
use DB;



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {       
        $tags = Tag::where('user_id', '=', \Auth::id())->whereNull('deleted_at')->orderBy('id', 'DESC')->get();
        
        // 取得したViewを渡す
        // compactで変数を渡す（＄は不要）
        return view('create', compact('tags'));
    }

    //呪文のように覚える
    // POSTの場合は、 Requestファザードをインスタンス化しておくと便利
    public function store(Request $request)
    {
        // リクエストで投げられたデータを全て取得する
        $posts = $request->all();
        // バリデーションを実装（メモの内容は必須）。contentのname属性と一致
        $request->validate([ 'content' => 'required']);

         // ===== ここからトランザクション開始 ======
         DB::transaction(function() use($posts) {
            // メモIDをインサートして取得
            // DBにPOSTで取得したコンテンツを配列で入れる。左側のキーがDBのカラム名、Valueがテキストエリアにある値
            // useridにはAuthファザードを使う
            $memo_id = Memo::insertGetId(['content' => $posts['content'], 'user_id' => \Auth::id()]);
            $tag_exists = Tag::where('user_id', '=', \Auth::id())->where('name', '=', $posts['new_tag'])->exists(); 
            // 新規タグが入力されているかチェック
            if( !empty($posts['new_tag']) && !$tag_exists){
                // 新規タグが既に存在しなければ、tagsテーブルにインサート→IDを取得
                $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
                // memo_tagsにインサートして、メモとタグを紐付ける
                MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag_id]);
            }
            // 既存タグが紐付けられた場合→memo_tagsにインサート
            if(!empty($posts['tags'][0])){
                foreach($posts['tags'] as $tag){
                    MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag]);
                }
            }
        
         });
         // ===== ここまでがトランザクションの範囲 ======

        return redirect( route('home') );
    }

    public function edit($id)
    {
        
        // MemosとTagsの🆔を被らず取得
        $edit_memo = Memo::select('memos.*', 'tags.id AS tag_id')
            // memosテーブルにmemotagsテーブルをくっつける
            ->leftJoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
            // 次にtagsテーブルをくっつける。これで三つのテーブルをくっつける
            ->leftJoin('tags', 'memo_tags.tag_id', '=', 'tags.id')
            ->where('memos.user_id', '=', \Auth::id())
            ->where('memos.id', '=', $id)
            ->whereNull('memos.deleted_at')
            ->get();

            // タグだけを抽出した配列
            $include_tags = [];
            foreach($edit_memo as $memo){
                array_push($include_tags, $memo['tag_id']);
            }
        
        // タグ一覧を取得
        $tags = Tag::where('user_id', '=', \Auth::id())->whereNull('deleted_at')->orderBy('id', 'DESC')->get();    

        // 取得したViewを渡す
        // compactで変数を渡し（＄は不要）、edit用のBladeファイルでview
        return view('edit', compact('edit_memo', 'include_tags', 'tags'));
    }


    public function update(Request $request)
    {
        // リクエストで投げられたデータを全て取得する
        $posts = $request->all();
        $request->validate([ 'content' => 'required']);
         // トランザクションスタート
         DB::transaction(function () use($posts){
            Memo::where('id', $posts['memo_id'])->update(['content' => $posts['content']]);
            // 一旦メモとタグの紐付けを削除
            MemoTag::where('memo_id', '=', $posts['memo_id'])->delete();
            // 再度メモとタグの紐付け
            foreach ($posts['tags'] as $tag) {
                MemoTag::insert(['memo_id' => $posts['memo_id'], 'tag_id' => $tag]);
            }
            // 新規タグが入力されているかチェック
            // もし、新しいタグの入力があれば、インサートして紐付ける
            $tag_exists = Tag::where('user_id', '=', \Auth::id())->where('name', '=', $posts['new_tag'])->exists(); 
            // 新規タグが既にtagsテーブルに存在するのかチェック
            if( !empty($posts['new_tag']) && !$tag_exists ){
                // 新規タグが既に存在しなければ、tagsテーブルにインサート→IDを取得
                $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
                // memo_tagsにインサートして、メモとタグを紐付ける
                MemoTag::insert(['memo_id' => $posts['memo_id'], 'tag_id' => $tag_id]);
            }
        });
        // トランザクションここまで

        return redirect( route('home') );
    }

    public function destroy(Request $request)
    {
        // リクエストで投げられたデータを全て取得する
        $posts = $request->all();
        
        // DBにPOSTで取得したコンテンツを配列で入れる。左側のキーがDBのカラム名、Valueがテキストエリアにある値
        // 論理削除の場合はupdateを使う
        // 論理削除はデータを復活できる
        Memo::where('id', $posts['memo_id'])->update(['deleted_at'=> date("Y-m-d H:i:s", time())]);

        return redirect( route('home') );
    }
}
