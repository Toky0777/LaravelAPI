<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\EditPostRequest;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {


        try {
            $query = Post::query();
            $perPage = 30;
            $page = $request->input('page', 1);
            $search = $request->input('search');

            if ($search) {
                $query->whereRaw("titre LIKE '%" . $search . "%'");
            }

            $total = $query->count();

            $result = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

            return response()->json([
                "status_code" => 200,
                'status_message' => 'Les posts ont été récupéré ',
                'current_page' => $page,
                'last_page' => ceil($total / $perPage),
                'items' => $result,
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function store(CreatePostRequest $request)
    {
        try {
            $post = new Post();
            $post->titre = $request->titre;
            $post->description = $request->description;
            $post->user_id = auth()->user()->id;
            $post->save();

            return response()->json([
                "status_code" => 200,
                'status_message' => 'Le post a été ajouté',
                'data' => $post
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function update(EditPostRequest $request, Post $post)
    {
        try {
            $post->titre = $request->titre;
            $post->description = $request->description;

            if ($post->user_id == auth()->user()->id) {
                $post->save();
            } else {
                return response()->json([
                    "status_code" => 422,
                    'status_message' => 'Vous n\'êtes pas l\'auteur de ce post.',
                ]);
            }

            return response()->json([
                "status_code" => 200,
                'status_message' => 'Le post a été modifié',
                'data' => $post
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function delete(Post $post)
    {
        try {
            if ($post->user_id == auth()->user()->id) {
                $post->delete();
            } else {
                return response()->json([
                    "status_code" => 422,
                    'status_message' => 'Vous n\'êtes pas l\'auteur de ce post. Suppression non autorisé.',
                ]);
            }
            return response()->json([
                'status_code' => 200,
                'status_message' => 'Le post a été supprimé',
                'data' => $post
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
