<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles=Article::orderby('created_at','ASC')->get();
        return view('back.articles.index',compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories=Category::all();
        return view('back.articles.create',compact('categories'));
        // compact fonksiyonunun kolaylığı değişkeni $ yazmadan String tipinde göndermemizi sağlar.
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'min:3',
            'image'=>'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $article=new Article;               //Makaleleri database insert işlemiş için bir nesne oluşturduk
        $article->title=$request->title;
        $article->category_id=$request->category;
        $article->content = $request->Mycontent ?? "editör hatası";
        $article->slug=Str::slug($request->title);

        if($request->hasFile('image')){
            $imageName=Str::slug($request->title).'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads'),$imageName);
            $article->image='uploads/'.$imageName;
       }
        $article->save();
        toastr()->success('BAŞARILI!','Makale Başarıyla Oluşturuldu');
        return redirect()->route('admin.makaleler.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $id;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article=Article::findOrFail($id);
        $categories=Category::all();
        return view('back.articles.update',compact('categories','article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title'=>'min:3',
            'image'=>'image|mimes:jpeg,png,jpg|max:2048' //resmin required olması gerekmiyor artık.
        ]);

        $article=Article::findOrFail($id);               //makalenin olup olmadığını kontrol ediyoruz.
        $article->title=$request->title;
        $article->category_id=$request->category;
        $article->content = $request->Mycontent ?? "editör hatası";
        $article->slug=Str::slug($request->title);

        if($request->hasFile('image')){
            $imageName=Str::slug($request->title).'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads'),$imageName);
            $article->image='uploads/'.$imageName;
        }
        $article->save();
        toastr()->success('BAŞARILI!','Makale Başarıyla Güncellendi');
        return redirect()->route('admin.makaleler.index');
    }
    public function switch(Request $request){
        $article=Article::findOrFail($request->id); //formdan gelen id'ye karşılık veritanbanında makale olup olmadığını kontrol eder
        $article->status=$request->statu=="true" ? 1 : 0 ;  // formun requesti olan statu true ise 1, false ise 0 verir.
        $article->save();



    }

    /**
     * Remove the specified resource from storage.
     */


    public function delete(string $id){
        Article::find($id)->delete();
        toastr()->success('Makale, Silinmiş Makaleler klasörüne taşındı');
        return redirect()->route('admin.makaleler.index');
    }

    public function trashed(){
        $articles=Article::onlyTrashed()->orderby('deleted_at','desc')->get();
        return view('back.articles.trashed',compact('articles'));
    }
    public function recover(string $id){
        Article::onlyTrashed()->find($id)->restore();
        toastr()->success('Makale Başarıyla Geri Yüklendi');
        return redirect()->back(); //aynı sayfayı yeniler

    }

    public function hardDelete(string $id){
        $article=Article::onlyTrashed()->find($id);        //silinmiş makalelerdekini bulup siliyor.
        if(File::exists($article->image)){
            File::delete(public_path($article->image));    // makale silinecekse ona ait resimde upload klasöründen siliniyor.
        }
        $article->forceDelete(); //silinmiş makalelerdekini bulup siliyor.
        toastr()->success('Makale Başarıyla Silindi');
        return redirect()->back();

    }
}
