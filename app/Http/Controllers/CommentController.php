<?php

namespace App\Http\Controllers;

use App\ResponseTrait;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    use ResponseTrait;

    public function index($article_id) {

        if(auth()->user()->role == 'admin') {

            $comments = Comment::where('article_id', $article_id)->where('status', 1)->get();
            if($comments) {

                return $this->response(message: 'Show All Comments Suc', data: CommentResource::collection($comments));

            } else {

                return $this->response(message: 'Not Found', status: 404);

            }

        } elseif (auth()->user()->role == 'publisher') {

            $article = Article::where('id', $article_id)->where('publisher_id', auth()->user()->id)->first();
            if($article) {

                $comments = Comment::where('article_id', $article_id)->where('status', 1)->get();
                if($comments) {

                    return $this->response(message: 'Show All Comments Suc', data: CommentResource::collection($comments));

                } else {

                    return $this->response(message: 'Not Found', status: 404);

                }

            } else {

                return $this->response(message: 'Not Found', status: 404);

            }

        } elseif (auth()->user()->role == 'subscriber') {

            $magazine_id = Article::findOrFail($article_id)->magazine->id;
            $subscription = Subscription::where('magazine_id', $magazine_id)->where('status', 'active')->where('subscriber_id', auth()->user()->id)->first();
            if ($subscription) {

                $comments = Comment::where('article_id', $article_id)->where('status', 1)->get();
                if($comments) {
    
                    return $this->response(message: 'Show All Comments Suc', data: CommentResource::collection($comments));
    
                } else {
    
                    return $this->response(message: 'Not Found', status: 404);
    
                }

            } else {

                return $this->response(message: 'You are not subscribed');

            }

        }

    }

    public function store(Request $request) {

        if(auth()->user()->role == 'subscriber') {

            $validator = Validator::make($request->all(), [
                'comment' => ['required', 'string', 'min:3', 'max:255'],
                'article_id' => ['required', 'integer', 'exists:articles,id']
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }

            $magazine_id = Article::findOrFail($request->article_id)->magazine->id;
            $subscription = Subscription::where('magazine_id', $magazine_id)->where('status', 'active')->where('subscriber_id', auth()->user()->id)->first();
            if ($subscription) {

                Comment::create([
                    'comment' => $request->comment,
                    'article_id' => $request->article_id,
                    'subscriber_id' => auth()->user()->id
                ]);
    
                return $this->response(message: 'Created Suc');

            } else {

                return $this->response(message: 'You are not subscribed');

            }

        } else {

            return $this->response(message: 'Sorry Your Not Subscriber');

        }

    }

    public function block_comment($id) {

        if(auth()->user()->role == 'admin') {

            Comment::findOrFail($id)->update([
                'status' => 0
            ]);
            return $this->response(message: 'Updated Suc');

        } else {

            return $this->response(message: 'Sorry Your Not Admin');

        }

    }
}
