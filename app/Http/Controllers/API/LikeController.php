<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;


class LikeController extends Controller
{
    public function likeOrUnlike()
    {
        $attrs = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ],403);
        }

        $like = $post->likes()->where('user_id',auth()->user()->id)->first();

        // if not liked then like
        if(!$like)
        {
            Like::create([
                'post_id' => $id,
                'user_id' => auth()->user()->id
            ]);

            return response([
                'message' => 'Liked.'
            ],200);
        }
        // else dislike it
        $like->delete();

        return response([
            'message' => 'Disliked',
        ], 200);
    }
}
