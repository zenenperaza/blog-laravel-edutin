<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Mostrar categoria en el admin
        $categories = Category::orderBy('id', 'desc')
                            ->simplePaginate(8);

        return view('admin.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {        
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
      $category = $request->all();

      if ($request->hasFile('image')) {
        $category['image'] = $request->fiel('image')->store('categories');
      }  

      // guardar informacin
      Category::create($category);
      
      return redirect()->action([CategoryController::class, 'index'])
      ->with('success-create', 'Categoria creada con exito');

    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        // si el usuario sube una imagen
        if ($request->hasFile('image')) {
            File::delete(public_path('storage/' . $category->image));
            $category['image'] = $request->file('image')->store('categories');
        }

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'status' => $request->status,
            'is_featured' => $request->is_featured,
       ]);

        return redirect()->action([CategoryController::class, 'index'], compact('category'))
        ->with('success-update', 'Categoria actualizada con exito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
                // eliminar la imagen del articulo
                if ($category->image) {
                    File::delete(public_path('storage/' . $category->image));
        
                }
                $category->delete();
        
                return redirect()->action([CategoryController::class, 'index'])
                ->with('success-delete', 'Categoria eliminada con exito');
        
    }

    //filtrar articulos por categoria
    public function detail(Category $category){
        $articles = Article::where([
            ['category_id', $category->id],
            ['status', 1]
        ])
        ->orderBy('id', 'desc')
        ->simplePaginate(5);

        $navbar = Category::where([
            ['status', '1'],
            ['is_featured', '1']
        ])->paginate(3);

        return view('subscriber.categories.detail', compact('articles', 'category', 'navbar'));
  
    }
}
