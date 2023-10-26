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
            $perPage = 2;
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
            $post->description = $request->descritpion;
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
            $post = new Post();
            $post->titre = $request->titre;
            $post->description = $request->descritpion;
            $post->save();

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
            if ($post) {
                $post->delete();
                return response()->json([
                    'status_code' => 200,
                    'status_message' => 'Le post a été supprimé',
                    'data' => $post
                ]);
            } else {
                $post->delete();
                return response()->json([
                    'status_code' => 422,
                    'status_message' => 'Enregistrement introuvable',
                ]);
            }
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
