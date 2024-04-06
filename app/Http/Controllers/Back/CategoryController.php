<?php

namespace App\Http\Controllers\Back;
// Burada veritabanındaki article ve category ile işlem yaptığımız için bu modelleri çağırmamız gerekiyor(use)
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Article;
use Illuminate\Queue\RedisQueue;
use Illuminate\Support\Str;
class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();        // Bu controllerdaki değişken articles\index içinde kullanılabilir
        return view('back.categories.index', compact('categories'));

        // kategorileri Modelde import edip (App\Models\Category) ardından veritabanında 'categories' in tüm niteliklerin alıyoruz.
        // categories değişkeni category modelindeki fonksiyonada doğrudan erişebilir.
    }
    public function create(Request $request){
        $isExist=Category::whereSlug(Str::slug($request->category))->first();
        if($isExist){
            toastr()->error($request->category.' adında bir kategori zaten mevcut!');
            return redirect()->back();
        }
        $category=new Category;    // database işlemleri için model'den bir nesne üretmek şarttır.
        $category->name=$request->category;
        $category->slug=Str::slug($request->category);
        $category->save();
        toastr()->success('Kategori Başarıyla Oluşturuldu');
        return redirect()->back();

    }

    public function update(Request $request){
        $isSlug=Category::whereSlug(Str::slug($request->slug))->whereNotIn('id',[$request->id])->first();       // kendi dışındaki idler kontrol edililir.
        $isName=Category::whereName($request->category)->whereNotIn('id',[$request->id])->first();
        if($isSlug or $isName){
            toastr()->error($request->category.' adında bir kategori zaten mevcut!');
            return redirect()->back();
        }
        $category=Category::find($request->id);    // database işlemleri için model'den bir nesne üretmek şarttır.
        $category->name=$request->category;
        $category->slug=Str::slug($request->slug);
        $category->save();
        toastr()->success('Kategori Başarıyla Güncellendi');
        return redirect()->back();

    }
    public function delete(Request $request){
        $category=Category::findOrFail($request->id);
        if ($category->id==1){
            toastr()->error('Bu Kategori Silinemez');
            return redirect()->back();
        }
        $message='';
        $count=$category->articleCount();
        if($count>0){
            Article::where('category_id',$category->id)->update(['category_id'=>1]);
            $defaultCategory=Category::find(1);
            $message='Bu Kategoriye ait '.$count.' makale '.$defaultCategory->name.' kategorisine taşındı';
        }
        $category->delete();
        toastr()->success($message,'Kategori Başarıyla Silindi!');
        return redirect()->back();

        // Silme işleminde ilişkisel veriler bulunduğundan her iki modelide use yaptık
    }

    public function getData(Request $request)
    {
        $category = Category::findOrFail($request->id);
        return response()->json($category);
        // kategori bulunduysa tüm bilgilerinin gönderiri. json tipinde. array olarak
    }

    public function switch(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->status = $request->statu == "true" ? 1 : 0;
        $category->save();
        // bize gelen id ve status değerlerini işliyoruz
        // Category modeli veritabanında id kontrolü yapıyor varsa işlem yapıyor yoksa 404
    }
}
