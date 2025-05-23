<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Auth;

use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    public function show(Request $request)
    {
        $posts = Post::with('user', 'postComments', 'subCategories')->get();
        $categories = MainCategory::with('subCategories')->get();
        $like = new Like;
        $post_comment = new Post;
        if (!empty($request->keyword)) {
            // 変更
            $keyword = $request->keyword;

            $posts = Post::with('user', 'postComments', 'subCategories') // 必要な関連モデルも一緒に取得
                ->where(function ($query) use ($keyword) {
                    // 複数条件をグループ化して where 句で処理
                    $query->where('post_title', 'like', '%' . $keyword . '%') // タイトルの部分一致
                        ->orWhere('post', 'like', '%' . $keyword . '%')     // 本文の部分一致
                        ->orWhereHas('subCategories', function ($q) use ($keyword) {
                            // サブカテゴリーの完全一致（関連テーブルを検索）
                            $q->where('sub_category', $keyword);
                        });
                })
                ->get(); // 該当する投稿を取得
            // $posts = Post::with('user', 'postComments', 'subCategories')
            //     ->where('post_title', 'like', '%' . $request->keyword . '%')
            //     ->orWhere('post', 'like', '%' . $request->keyword . '%')->get();
        } else if ($request->category_word) {
            $sub_category = $request->category_word;
            $posts = Post::with('user', 'postComments', 'subCategories')->get();

            // 追記
        } else if ($request->sub_category_id) {
            $posts = Post::whereHas('subCategories', function ($query) use ($request) {
                $query->where('sub_categories.id', $request->sub_category_id);
            })
                ->with('user', 'postComments', 'subCategories')
                ->get();
            // ここまで
        } else if ($request->like_posts) {
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = Post::with('user', 'postComments', 'subCategories')
                ->whereIn('id', $likes)->get();
        } else if ($request->my_posts) {
            $posts = Post::with('user', 'postComments', 'subCategories')
                ->where('user_id', Auth::id())->get();
        }
        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
    }

    public function postDetail($post_id)
    {
        $post = Post::with('user', 'postComments', 'subCategories')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput()
    {
        // 追記
        $main_categories = MainCategory::with('subCategories')->get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request)
    {
        // 追記
        $request->validate([
            'post_category_id' => 'required|exists:sub_categories,id',
            'post_title' => 'required|string|max:100',
            'post_body' => 'required|string|max:2000',
        ], [
            'post_category_id.required' => 'カテゴリーは必須項目です。',
            'post_category_id.exists' => '選択されたカテゴリーが無効です。',
            'post_title.required' => 'タイトルは必須です。',
            'post_title.string' => 'タイトルは文字列で入力してください。',
            'post_title.max' => 'タイトルは100文字以内で入力してください。',
            'post_body.required' => '投稿内容は必須です。',
            'post_body.string' => '投稿内容は文字列で入力してください。',
            'post_body.max' => '投稿内容は2000文字以内で入力してください。',
        ]);

        DB::beginTransaction();
        try {
            // 投稿作成
            $post = Post::create([
                'user_id' => Auth::id(),
                'post_title' => $request->post_title,
                'post' => $request->post_body,
            ]);

            // 中間テーブルにサブカテゴリーとの関係を登録（追記部分）
            $post->subCategories()->attach($request->post_category_id);

            DB::commit();
            return redirect()->route('post.show')->with('success', '投稿が完了しました。');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => '投稿の登録に失敗しました。'])->withInput();
        }


        // $post = Post::create([
        //     'user_id' => Auth::id(),
        //     'post_title' => $request->post_title,
        //     'post_category_id' => $request->post_category_id,
        //     'post' => $request->post_body,
        // ]);
        // return redirect()->route('post.show');
    }



    public function postEdit(Request $request)
    {
        //バリデーションを最初に追加
        $request->validate([
            'post_title' => ['required', 'string', 'max:100'],
            'post_body'  => ['required', 'string', 'max:2000'],
        ], [
            'post_title.required' => 'タイトルは必須です。',
            'post_title.string'   => 'タイトルは文字列で入力してください。',
            'post_title.max'      => 'タイトルは100文字以内で入力してください。',

            'post_body.required' => '投稿内容は必須です。',
            'post_body.string'   => '投稿内容は文字列で入力してください。',
            'post_body.max'      => '投稿内容は2000文字以内で入力してください。',
        ]);


        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id)
    {
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show')->with('success', '投稿を削除しました。');
    }

    public function mainCategoryCreate(Request $request)
    {
        // 追記
        $request->validate([
            'main_category_name' => [
                'required',
                'string',
                'max:100',
                'unique:main_categories,main_category'
            ],
        ], [
            'main_category_name.required' => 'メインカテゴリー名は必須です。',
            'main_category_name.string' => 'メインカテゴリー名は文字列で入力してください。',
            'main_category_name.max' => 'メインカテゴリー名は100文字以内で入力してください。',
            'main_category_name.unique' => 'このメインカテゴリーはすでに存在します。',
        ]);

        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }

    // 追記
    public function subCategoryCreate(Request $request)
    {
        $request->validate([
            'main_category_id' => 'required|integer|exists:main_categories,id',
            'sub_category_name' => 'required|string|max:100|unique:sub_categories,sub_category'
        ], [
            // main_category_idのエラーメッセージ
            'main_category_id.required' => 'メインカテゴリーは必須です。',
            'main_category_id.integer' => 'メインカテゴリーの形式が不正です。',
            'main_category_id.exists' => '選択されたメインカテゴリーは存在しません。',
            // sub_category_nameのエラーメッセージ
            'sub_category_name.required' => 'サブカテゴリー名は必須です。',
            'sub_category_name.string' => 'サブカテゴリー名は文字列で入力してください。',
            'sub_category_name.max' => 'サブカテゴリー名は100文字以内で入力してください。',
            'sub_category_name.unique' => 'このサブカテゴリー名はすでに登録されています。',
        ]);

        SubCategory::create([
            'main_category_id' => $request->main_category_id,
            'sub_category' => $request->sub_category_name
        ]);

        return redirect()->route('post.input')->with('success', 'サブカテゴリーを追加しました');
    }


    public function commentCreate(Request $request)
    {
        //バリデーションを追加
        $request->validate([
            'comment' => ['required', 'string', 'max:250'],
        ], [
            'comment.required' => 'コメントは必須です。',
            'comment.string' => 'コメントは文字列で入力してください。',
            'comment.max' => 'コメントは250文字以内で入力してください。',
        ]);

        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard()
    {
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard()
    {
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request)
    {
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    public function postUnLike(Request $request)
    {
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
            ->where('like_post_id', $post_id)
            ->delete();

        return response()->json();
    }
}
