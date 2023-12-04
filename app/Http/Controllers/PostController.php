<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function viewSinglePost($id)
    {
        $post = DB::table('users')
            ->join('posts', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.*')
            ->where('posts.post_uuid', $id)
            ->first();

        $comments = DB::table('comments')
                    ->join('users', 'comments.user_id', '=', 'users.id')
                    ->select('comments.*', 'users.*')
                    ->where('post_uuid', $id)
                    ->get();

        if ($post) {
            return view('webpage.post', compact('post', 'comments'));
        } else {
            return redirect('/');
        }
    }

    public function viewAllPost()
    {
        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.id AS post_id', 'posts.*', 'users.*')
            ->get();

        return view('webpage.index', compact('posts'));
    }

    public function createPost(Request $request)
    {
        $info = Validator::make($request->all(), [
            'barta' => ['required'],
        ], [
            'barta' => 'Type something to post!',
        ])->validate();

        $post = DB::table('posts')
            ->insert([
                'post_uuid' => Str::uuid(),
                'description' => $request->barta,
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);

        return redirect()->back();
    }

    public function showUpdatePost($uuid)
    {
        $post = DB::table('posts')
            ->where('post_uuid', $uuid)->first();
        if (! $post) {
            return redirect()->back();
        }

        return view('webpage.edit-post', ['posts' => $post]);
    }

    public function updatePost(Request $request, $uuid)
    {
        $info = Validator::make($request->all(), [
            'barta' => ['required'],
        ], [
            'barta' => 'Type something to post!',
        ])->validate();

        $post = DB::table('posts')
            ->where('post_uuid', $uuid)
            ->update([
                'description' => $request->barta,
                'created_at' => now(),
            ]);

        return redirect('/newsfeed');
    }

    public function deletePost($uuid)
    {
        $post = DB::table('posts')
            ->where('post_uuid', $uuid)
            ->delete();

        return redirect('/newsfeed');
    }
}
