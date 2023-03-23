<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;


class PostController extends Controller
{
    // get all post
    public function index()
    {
        return response([
            'posts' => Post::orderBy('created_at', 'desc')->with('user:id,name,image')->withCount('comments', 'likes')->get()
        ], 200);
    }

    // get single post
    public function show($id)
    {
        return response([
            'post' => Post::where('id',$id)->withCount('comments', 'likes')->get()
        ], 200);
    }

    // create post
    public function store(Request $request)
    {

        $attrs = $request->validate([
            'body' => 'required'
        ]);

        $attrs = Post::create([
            'body' => $attrs['body'],
            'user_id' => auth()->user()->id()
        ]);

        return response([
            'message' => 'post created',
            'post' => $attrs
        ], 200);
    }

    // update post
    public function update(Request $request, $id)
    {
        $attrs = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ],403);
        }

        if($post->user_id !=auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ],403);
        }

        // validate fields
        $attrs = $request->validate([
            'body' => 'required'
        ]);

        $post->update([
            'body' => $attrs['body']
        ]);

        // skip image
        return response([
            'message' => 'post updated',
            'post' => $attrs
        ], 200);
    }

    // delete post
    public function destroy($id)
    {
        $attrs = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ],403);
        }

        if($post->user_id !=auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ],403);
        }

        $post->comments()->delete();
        $post->like()->delete();
        $post->delete();

        return response([
            'message' => 'post deleted.',
        ], 200);

    }

}
