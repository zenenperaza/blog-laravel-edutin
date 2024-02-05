<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // mostrar los articulos en el admin
        $user = Auth::user();
        $articles = Article::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->simplePaginate(10);

        return view ('admin.articles.index', compact('articles'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //obtener las categorias publicas
        $categories = Category::select(['id', 'name'])
                                ->where('status', '1')
                                ->get();

        
        return view ('admin.articles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        $request->merge([
            'user_id' => Auth::user()->id,
        ]);
        // guardo la solicitud en una variable
        $article = $request->all();

        // validar si hay archivo en el request
        if ($request->hasFile('image')) {
            $article['image'] = $request->file('image')->store('articles');
        }

        Article::create($article);

        return redirect()->action([ArticleController::class, 'index'])
                        ->with('success-create', 'Articulo creado con exito');

    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $comments = $article->comments()->simplePaginate(5);
        return view('subscriber.articles.show', compact('article', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
         //obtener las categorias publicas
         $categories = Category::select(['id', 'name'])
         ->where('status', '1')
         ->get();


        return view ('admin.articles.edit', compact('categories', 'article'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, Article $article)
    {
        // si el usuario sube una nueva imagen
        if ($request->hasFile('image')) {
            // eliminamos la imagen anterior
            File::delete(public_path('storage/' . $article->image));
            $article['image'] = $request->file('image')->store('articles');            
        }

        $article->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'introduction' => $request->introduction,
            'body' => $request->body,
            'user_id' => Auth::user()->id,
            'category_id' => $request->category_id,
            'status' => $request->status,
        ]);

        return redirect()->action([ArticleController::class, 'index'])
        ->with('success-update', 'Articulo actualizado con exito');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        // eliminar la imagen del articulo
        if ($article->image) {
            File::delete(public_path('storage/' . $article->image));

        }
        $article->delete();

        return redirect()->action([ArticleController::class, 'index'])
        ->with('success-delete', 'Articulo eliminado con exito');

    }
}
