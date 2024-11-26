<?php

namespace App\Http\Controllers;

use App\ResponseTrait;
use App\Models\Article;
use App\Models\Magazine;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    use ResponseTrait;

    public function index($magazine_id) {

        if(auth()->user()->role == 'admin') {

            $articles = Article::where('magazine_id', $magazine_id)->get();
            if($articles) {

                return $this->response(message: 'Get All Articles Suc', data: ArticleResource::collection($articles));

            } else {

                return $this->response(message: 'Not Found', status: 404);

            }

        } elseif(auth()->user()->role == 'publisher') {

            $magazine = Magazine::where('publisher_id', auth()->user()->id)->where('id', $magazine_id)->first();
            if($magazine) {

                $articles = Article::where('publisher_id', auth()->user()->id)->where('magazine_id', $magazine_id)->get();

                if($articles) {

                    return $this->response(message: 'Get All Articles Suc', data: ArticleResource::collection($articles));
    
                } else {
    
                    return $this->response(message: 'Not Found', status: 404);
    
                }

            } else {

                return $this->response(message: 'Not Found', status: 404);

            }

        } elseif(auth()->user()->role == 'subscriber') {

            $subscription = Subscription::where('magazine_id', $magazine_id)->where('status', 'active')->where('subscriber_id', auth()->user()->id)->first();
            if ($subscription) {

                $articles = Article::where('magazine_id', $magazine_id)->get();
                if($articles) {
    
                    return $this->response(message: 'Get All Articles Suc', data: ArticleResource::collection($articles));
    
                } else {
    
                    return $this->response(message: 'Not Found', status: 404);
    
                }

            } else {

                return $this->response(message: 'You are not subscribed');

            }

        }

    }

    public function show($id) {

        if(auth()->user()->role == 'admin') {

            $article = Article::findOrFail($id);
            return $this->response(message: 'Show Article Suc', data: new ArticleResource($article));
            
        } elseif (auth()->user()->role == 'publisher') {

            $article = Article::where('id', $id)->where('publisher_id', auth()->user()->id)->first();
            if($article) {

                return $this->response(message: 'Show Article Suc', data: new ArticleResource($article));

            } else {

                return $this->response(message: 'Not Found', status: 404);

            }

        } elseif(auth()->user()->role == 'subscriber') {

            $magazine_id = Article::findOrFail($id)->magazine->id;
            $subscription = Subscription::where('magazine_id', $magazine_id)->where('status', 'active')->where('subscriber_id', auth()->user()->id)->first();
            if ($subscription) {            

                $article = Article::findOrFail($id);
                return $this->response(message: 'Show Article Suc', data: new ArticleResource($article));

            } else {

                return $this->response(message: 'You are not subscribed');

            }

        }

    }

    public function store(Request $request) {

        if(auth()->user()->role == 'publisher') {

            $validator = Validator::make($request->all(), [
                'title' => ['required', 'string', 'min:3', 'max:70'],
                'content' => ['required', 'string', 'min:20', 'max:700'],
                'magazine_id' => ['required', 'integer', 'exists:magazines,id']
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }

            $magazine = Magazine::findOrFail($request->magazine_id);
            if($magazine->publisher_id == auth()->user()->id) {

                Article::create([
                    'title' => $request->title,
                    'content' => $request->content,
                    'magazine_id' => $request->magazine_id,
                    'publisher_id' => auth()->user()->id
                ]);

                return $this->response(message: 'Created Suc');

            } else {

                return $this->response(message: 'This Magazine Is Not Yours', status: 401);

            }

        } else {

            return $this->response(message: 'Sorry You Dont Publisher');

        }

    }

}
