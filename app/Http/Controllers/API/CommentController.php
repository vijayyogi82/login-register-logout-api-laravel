<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;

class CommentController extends Controller
{   
    // get all comments
    public function index($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ],403);
        }

        return response([
                'post' => $post->comments()->with('user:id,name,image')->get()
        ],200);
    }

    // creats a comments
    public function store(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ],403);
        }

        // validate fields
        $attrs = $request->validate([
            'comment' => 'required'
        ]);

        Comment::create([
            'comment' => $attrs['comment'],
            'post_id' => $id,
            'user_id' => auth()->user()->id
        ]);

        return response([
            'message' => 'comment created.'
        ],200);
    }

    // update a comment
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if(!$comment)
        {
            return response([
                'message' => 'Comment not found.'
            ],403);
        }

        if($comment->user_id !=auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ],403);
        }

        // validate fields
        $attrs = $request->validate([
            'comment' => 'required'
        ]);

        $comment->update([
            'comment' => $attrs['comment']
        ]);

        return response([
            'message' => 'comment Updated.'
        ],200);
    }

    // delete a comments
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if(!$comment)
        {
            return response([
                'message' => 'Comment not found.'
            ],403);
        }

        if($comment->user_id !=auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ],403);
        }

        $comment->delete();

        return response([
            'message' => 'Comment deleted.',
        ], 200);

    }

}
