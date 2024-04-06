<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Str;
use File;
class PageController extends Controller
{
    public function index()
    {
        $pages = Page::all();
        return view('back.pages.index', compact('pages'));
    }
    public function orders(Request $request){
        foreach($request->get('page') as $key => $order){
            Page::where('id',$order)->update(['order'=>$key]);
        }

    }
    public function create(){
        return view('back.pages.create');
    }

    public function update($id){
        $page=Page::findOrFail($id);
        return view('back.pages.update',compact('page'));
    }

    public function updatePost(Request $request, string $id)
    {
        $request->validate([
            'title'=>'min:3',
            'image'=>'image|mimes:jpeg,png,jpg|max:2048' //resmin required olması gerekmiyor artık.
        ]);

        $page=Page::findOrFail($id);               //makalenin olup olmadığını kontrol ediyoruz.
        $page->title=$request->title;
        $page->content = $request->Mycontent ?? "editör hatası";
        $page->slug=Str::slug($request->title);

        if($request->hasFile('image')){
            $imageName=Str::slug($request->title).'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads'),$imageName);
            $page->image='uploads/'.$imageName;
        }
        $page->save();
        toastr()->success('BAŞARILI!','Sayfa Başarıyla Güncellendi');
        return redirect()->route('admin.page.index');
    }

    public function switch(Request $request)
    {
        $page = Page::findOrFail($request->id); //formdan gelen id'ye karşılık veritanbanında makale olup olmadığını kontrol eder
        $page->status = $request->statu == "true" ? 1 : 0;  // formun requesti olan statu true ise 1, false ise 0 verir.
        $page->save();
    }
    public function post(Request $request){

        $request->validate([
            'title'=>'min:3',
            'image'=>'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $last=Page::orderBy('order','DESC')->first();

        $page=new Page;               //Makaleleri database insert işlemiş için bir nesne oluşturduk
        $page->title=$request->title;
        $page->content = $request->Mycontent ?? "editör hatası";
        $page->order=$last->order+1;
        $page->slug=Str::slug($request->title);

        if($request->hasFile('image')){
            $imageName=Str::slug($request->title).'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads'),$imageName);
            $page->image='uploads/'.$imageName;
        }
        $page->save();
        toastr()->success('BAŞARILI!','Sayfa Başarıyla Oluşturuldu');
        return redirect()->route('admin.page.index');

    }

    public function delete(string $id){
        $page=Page::find($id);        //silinmiş makalelerdekini bulup siliyor.
        if(File::exists($page->image)){
            File::delete(public_path($page->image));    // makale silinecekse ona ait resimde upload klasöründen siliniyor.
        }
        $page->delete(); //silinmiş makalelerdekini bulup siliyor.
        toastr()->success('Sayfa Başarıyla Silindi');
        return redirect()->back();

    }


}
