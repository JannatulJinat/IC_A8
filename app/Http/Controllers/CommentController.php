<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CommentController extends Controller
{
    public function createComment(Request $request, $postUUID)
    {
        $info = Validator::make($request->all(), [
            'comment' => ['required'],
        ], [
            'comment' => 'Type something to comment!',
        ])->validate();

        $comments = DB::table('comments')
            ->insert([
                'comment_description' => $request->comment,
                'post_uuid' => $postUUID,
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);

        return redirect()->back();
    }


}
