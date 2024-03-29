<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User; // 追加

class UsersController extends Controller
{
    public function index()
    {
        // ユーザ一覧をidの降順で取得
        $users = User::orderBy('id', 'desc')->paginate(10);

        // ユーザ一覧ビューでそれを表示
        return view('users.index', [
            'users' => $users,
        ]);
    }
    
    public function show($id)
    {
       // idの値でユーザを検索して取得
        $user = User::findOrFail($id);
        $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);
        
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        
        // ユーザ詳細ビューでそれを表示
        return view('users.show', [
            'user' => $user,
            'microposts' => $microposts
     ]);
    }
    
    /**
     * ユーザのフォロー一覧ページを表示するアクション。
     *
     * @param  $id  ユーザのid
     * @return \Illuminate\Http\Response
     */
    public function followings($id)
    {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザのフォロー一覧を取得
        $followings = $user->followings()->paginate(10);

        // フォロー一覧ビューでそれらを表示
        return view('users.followings', [
            'user' => $user,
            'users' => $followings,
        ]);
    }

    /**
     * ユーザのフォロワー一覧ページを表示するアクション。
     *
     * @param  $id  ユーザのid
     * @return \Illuminate\Http\Response
     */
    public function followers($id)
    {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザのフォロワー一覧を取得
        $followers = $user->followers()->paginate(10);

        // フォロワー一覧ビューでそれらを表示
        return view('users.followers', [
            'user' => $user,
            'users' => $followers,
        ]);
    }
    
    public function favorites($id)
    {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);
        
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        
        // ユーザの(お気に入りした)投稿(microposts)の一覧を取得
        $microposts = $user->favorites()->paginate(10);
        
        // お気に入り一覧ビューでそれらを表示
        return view('users.favorites', [
            'user' => $user,
            'microposts' => $microposts,
        ]);
    }
    
    public function destroy($id)
    {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);
        
        if(\Auth::user()->is_admin==1){
            // ユーザを削除
             $user->delete();
        }

        // リダイレクト
        return redirect('/');
    }
    
    public function confirmation($id)
    {
        if(\Auth::user()->is_admin==1){
            // idの値でユーザを検索して取得
            $user = User::findOrFail($id);
            
            return view('account_delete.confirmation', [
                'user' => $user, 
            ]);
        }
    }
}


