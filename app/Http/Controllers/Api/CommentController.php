<?php

namespace App\Http\Controllers\Api;

use App\Models\blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    
    public function create(Request $request, $blog_id) {

        $blog = blog::where('id', $blog_id)->first();
        if($blog) {
            $validator = Validator::make($request->all(), [
                'message' => 'required|max:250',
            ]);

            if($validator->fails()) {
                return response([
                    'message' => 'Validation errors',
                    'errors' => $validator->messages()
                ], 422);
            } 

            $comment = Comment::create([
                'message' => $request->message,
                'blog_id' => $blog->id,
                'user_id' => $request->user()->id
            ]);

            $comment->load('user');

            return response([
                'message' => 'Comment successfully created',
                'data' => $comment
            ], 200);

        }
        else {
            return response([
                'message' => 'Validation errors',
            ], 422);
        }

    }

    public function list($blog_id, Request $request) {
        $blog = blog::where('id', $blog_id)->first();
        if($blog) {
           
            // $perpage = 200;
            $comments = Comment::where('blog_id', $blog_id)->get();

            return response ([
                'message' => 'Comment successfully fetched',
                'data' => $comments
            ], 200);

        }
        else {
            return response([
                'message' => 'Validation errors',
            ], 422);
        }
    }



}