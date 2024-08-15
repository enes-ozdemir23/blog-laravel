<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


//Models
use App\Models\Article;             // Modelleri burada import ediyoruz
use App\Models\Category;
use App\Models\Page;
use App\Models\Contact;
use App\Models\Config;

use Mail;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function PHPUnit\TestFixture\func;

class Homepage extends Controller
{
    public function __construct(){
        if(Config::find(1)->active==0){
            return redirect()->to('site-bakimda')->send();
        }

        view()->share('pages',Page::where('status',1)->orderBy('order','ASC')->get());
        view()->share('categories',Category::where('status',1)->inRandomOrder()->get());
        // ilk yazılan paylaşmak istediğin değişkenin adını ikinciye ise değişkeni yazıyorsun.
    }

    public function index(){
        $data['articles']=Article::with('getCategory')->where('status',1)->whereHas('getCategory',function ($query){
            $query->where('status',1);
        })->orderBy('created_at','DESC')->paginate(5);
        $data['articles']->withPath(url('sayfa'));               //bu url için bir root tanımlamaszsan çalışmaz. url() olmazsa sayfa 2 den 1 e geçemez.override oluyor çünkü
        return view('front.homepage',$data);                    // return ile front/homepage dosyasına $data değişkeni ile gidiliyor. Böylece o sayfadakiler gösteriliyor.
    }   //return'den önce yazılan kodlarla gidilecek/gösterilecek sayfa ile ilgili ayarlamalar yapılıyor

    public function single($category,$slug){
        $category=Category::whereSlug($category)->first() ?? abort(403,'Böyle bir kategori bulunamadı.'); //a
        $article=Article::whereSlug($slug)->whereCategoryId($category->id)->first() ?? abort(403,'Böyle bir yazı bulunamadı'); //b
        $article->increment('hit');
        $data['article']=$article;
        return view('front.single',$data);

    // 2 soru işaretiyle kontrol yapmamız sağlanıyor. yani categori ve article isimleri url'de doğru yazılmışsa sayfa açılır yoksa hata mesajını verir
    // a'da url'de yazılan kategori veri t. da var mı diye kontrol ediyor. varsa değişkene atıyor. Yani Eşleşme kontrolü yapıyor.
    // b ' de ise var olan kategori ile article eşleşiyor mu diye kontrol ediyor. yoksa kategori ismi ne olursa olsun yine sayfa gösterir.
    // yani ilişkisel veritabanı ile eşleşmeler kontrol ediliyor.

    }
    public function category($slug){
        $category=Category::whereSlug($slug)->first() ?? abort(403,'Böyle bir kategori bulunamadı.');
        $data['category']=$category;
        $data['articles']=Article::where('category_id',$category->id)->where('status',1)->orderBy('created_at','DESC')->paginate(1);
        // seçilen kategoriye ait yazıları getirir
        return view('front.category',$data);
    }
    public function page($slug){
        $page=Page::whereSlug($slug)->first() ?? abort(403,'Böyle bir sayfa bulunamadı.');
        $data['page']=$page;
        $data['pages']=Page::orderBy('order','ASC')->get(); //bu kısım çağırılmazsa sayfa gösterilmez
        return view('front.page',$data);

    }

    public function contact(){
        return view('front.contact');
    }
    public function contactpost(Request $request){
        $rules=[                        //istenilen kurallar yazılıyor. name min 5 karakter olabilir.
            'name'=>'required|min:5',
            'email'=>'required|email',
            'topic'=>'required',
            'message'=>'required|min:10'

        ];
        $validate=Validator::make($request->post(),$rules);

        if($validate->fails()){
            return redirect()->route('contact')->withErrors($validate)->withInput();
        }

        Mail::send([],[],function($message) use($request){
        $message->from('ilitisim@blogsitesi.com','Blog Sitesi');
        $message->to('enes@gmail.com');
        $message->html('Mesajı Gönderen:'.$request->name.'<br/>
                        Mesajı Gönderen Mail:'.$request->email.'<br/>
                        Mesaj Konusu:'.$request->topic.'<br/>
                        Mesaj:'.$request->message.'<br/><br/>
                        Mesaj Gönderilme Tarihi: '.now().'','text/html');
        $message->subject($request->name. ' iletişimden mesaj geldi');
        });


        // $contact=new Contact;
        // $contact->name=$request->name;
        // $contact->email=$request->email;
        // $contact->topic=$request->topic;
        // $contact->message=$request->message;
        // $contact->save();
        return redirect()->route('contact')->with('success','Mesajınız Bana iletildi. Teşekkür Ederim');




        // Tamam aynı sayfaya dönüyorsun ama bilgi mesajı vermiyorsun :) Bunun için redirect'ten sonra with yazılırsa yapılabilir.
        // Dikkat edersen with'in session'ı success. Bu bilgiyi if ile contact.blade.php sayfasında succes mesajı için kullan.
        // Bu fonksiyonla birlikte iletişim sayfasındaki veriler veritabanına kaydedilir.
        // Gönder butonuna bastıktan sonra tekrar sashay dönmesi için redirect metodunu kullandık.
        // $contact adında yeni bir Contact modeli oluşturulur.( $contact=new Contact )
        // Bu model, bir veritabanı tablosunu temsil eder ve iletişim formundan gelen bilgileri saklamak için kullanılır.
        // Yani Contact tablosunu kontrol etmek için $contact değişkenini oluşturduk.


    }
}

// "whereSlug($slug)->first()" bu komut ile veritabanında kategori isimlerini aldı ve bir değişkene attı.
// Veritabanında verileri get() fonksiyonu ile çekiyoruz. Fakat sayfalama yaparsak pagination() ile çekiyoruz.
// Anasayfada ki ve kategorilerde ki makale gösterme kodları aynı olduğu için bir ortak kod yazıp tekrarı önlemeliyiz.
// Böylelikle anasayfa ve kategorilerde ki pagination kodlarıda aynı olacaktır.
// Not: Veritabanında veri çekme ve bunları değişkene atma işlemleri homepage.php'de yapılır. İlgili sayfa için metot oluşturulur.
// construct: homepage.php sayfasındaki metotlarda ortak olan komutları constrtuct içinde topluyoruz. Böylece bu kodlar her metotta çalışır.
// eğerki  construct kullanılmasaydı tüm metotları bu kod yazılmalıydı: $data['pages']=Page::orderBy('order','ASC')->get();
// page'den sonra kategoriler içinde construct kullandık ve kodlar azaldı
// get metoduyla alınan veriler url'de gözükür ve güvenlik çaısından risklidir
// post metoduyla alınan form verileri url'de gözükmez ve güvenlidir.
// Laravel'de Modeller, uygulamanızın veri tabanı işlemlerini kolaylaştırmak ve düzenlemek için kullanılır.
// Withinput() fonksiyonu withErrors() fonksiyonuyla birlikte withInput() kullanarak kullanıcının girdiği verileri tekrar gösterir.
//   Böylece kullanıcılar tekrar doldurmaları gereken alanları hatırlayabilir ve bu, kullanıcı deneyimini iyileştirir.
// {{old('name')}} fonksiyonuyla hata döndüğünde formdaki veriler silinmez korunur.

//mailtrap:


//    });
