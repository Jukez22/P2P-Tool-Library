<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiagnosticArticle;

class KnowledgeBaseController extends Controller
{
    public function getArticles(Request $request)
    {
        $articles = DiagnosticArticle::orderBy('created_at', 'desc')->get();
        return response()->json(['articles' => $articles]);
    }

    public function createArticle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author_id' => 'required|exists:users,id'
        ]);

        $article = DiagnosticArticle::create([
            'title' => $request->title,
            'content' => $request->content,
            'author_id' => $request->author_id
        ]);

        return response()->json([
            'message' => 'Diagnostic article created successfully',
            'article' => $article
        ]);
    }
}
