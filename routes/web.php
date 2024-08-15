<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
*/
Route::get('site-bakimda',function (){
    return view('front.offline');
});


//Giriş Yoksa
Route::prefix('admin')->name('admin.')->middleware('isLogin')->group(function (){
    Route::get('giris','App\Http\Controllers\Back\AuthController@login')->name('login');
    Route::post('giris','App\Http\Controllers\Back\AuthController@loginPost')->name('login.post');
});

// url'de ve isimde admin ortakolduğu için gruplama yapabiliriz.

// Giriş Varsa:
Route::prefix('admin')->name('admin.')->middleware('isAdmin')->group(function (){
    Route::get('panel','App\Http\Controllers\Back\Dashboard@index')->name('dashboard');
    // MAKALE ROUTELARI
    Route::get('makaleler/silinenler','App\Http\Controllers\Back\ArticleController@trashed')->name('trashed.article');
    Route::resource('makaleler','App\Http\Controllers\Back\ArticleController');
    Route::get('/switch','App\Http\Controllers\Back\ArticleController@switch')->name('switch');
    Route::get('/deletearticle/{id}','App\Http\Controllers\Back\ArticleController@delete')->name('delete.article');
    Route::get('/harddeletearticle/{id}','App\Http\Controllers\Back\ArticleController@hardDelete')->name('hard.delete.article');
    Route::get('/recoverarticle/{id}','App\Http\Controllers\Back\ArticleController@recover')->name('recover.article');
    // KATEGORİ ROUTELARI
    Route::get('/kategoriler','App\Http\Controllers\Back\CategoryController@index')->name('category.index');
    Route::post('/kategoriler/create','App\Http\Controllers\Back\CategoryController@create')->name('category.create');
    Route::post('/kategoriler/update','App\Http\Controllers\Back\CategoryController@update')->name('category.update');
    Route::post('/kategoriler/delete','App\Http\Controllers\Back\CategoryController@delete')->name('category.delete');
    Route::get('/kategori/status','App\Http\Controllers\Back\CategoryController@switch')->name('category.switch');
    Route::get('/kategori/getData','App\Http\Controllers\Back\CategoryController@getData')->name('category.getdata');
    // SAYFA ROUTELARI
    Route::get('/sayfalar','App\Http\Controllers\Back\PageController@index')->name('page.index');
    Route::get('/sayfalar/olustur','App\Http\Controllers\Back\PageController@create')->name('page.create');
    Route::get('/sayfalar/guncelle{id}','App\Http\Controllers\Back\PageController@update')->name('page.edit');
    Route::post('/sayfalar/guncelle{id}','App\Http\Controllers\Back\PageController@updatePost')->name('page.edit.post');
    Route::post('/sayfalar/olustur','App\Http\Controllers\Back\PageController@post')->name('page.create.post');
    Route::get('sayfa/switch','App\Http\Controllers\Back\PageController@switch')->name('page.switch');
    Route::get('/sayfa/sil/{id}','App\Http\Controllers\Back\PageController@delete')->name('page.delete');
    Route::get('/sayfa/siralama','App\Http\Controllers\Back\PageController@orders')->name('page.orders');
    // CONFİG ROUTELARI
    Route::get('/ayarlar','App\Http\Controllers\Back\ConfigController@index')->name('config.index');
    Route::post('/ayarlar/update','App\Http\Controllers\Back\ConfigController@update')->name('config.update');
    // LOGOUT ROUTE
    Route::get('cikis','App\Http\Controllers\Back\AuthController@logout')->name('logout');
});




/*
|--------------------------------------------------------------------------
| Front Routes
|--------------------------------------------------------------------------
*/

Route::get('/', 'App\Http\Controllers\Front\Homepage@index')->name('homepage');
Route::get('sayfa','App\Http\Controllers\Front\Homepage@index');
Route::get('/iletisim','App\Http\Controllers\Front\Homepage@contact')->name('contact');
Route::post('/iletisim','App\Http\Controllers\Front\Homepage@contactpost')->name('contact.post');
Route::get('/kategori/{category}','App\Http\Controllers\Front\Homepage@category')->name('category');
Route::get('/{category}/{slug}','App\Http\Controllers\Front\Homepage@single')->name('single');
Route::get('/{sayfa}','App\Http\Controllers\Front\Homepage@page')->name('page');


// admin.dashboard 'i category'nin üstüne yazmazsan; ikiside iki parçalı url olduğu için categor'nin 404 hatasını gösterir
//category üstte olmazsa alttaki single ie kategoriden dolayı karışıyor ve single fonksiyonuna yönlendiriyor.
//NOT:Sabit verdiğimiz url adreslerini en üstte yazmak gerek. Yani , süslü parantez içinde yazılan url adreslerinin
//yazarsan örn: 'sayfa' altına 'iletisim' yazarsan 403 hata kodu verir. Eğesr üstüne yazarsan doğru şekilde çalışır.
//Login.html sayfası yapılırken aslında temel mantığın özeti kullanılıyor. Sıkıştığında hep buraya bak.
// auth kütüphanesini admin tablosuna yerleştireceğiz. Bunun avantajı auth ile tüm giriş çıkış kayıt işlemlerini kolayca yapabileceğiz.
// auth ile zamandan tasarruf edeceğiz.

//middleware:
//url'de admin/panel yazdığımda kullanıcı girişi yapmadan da bu sayfaya erişebiliyorum. Bunu engellemek için midddleware kullanmak gerek.
//Yani bu katman bir admin girişi olup olmadığnı kabul kontrol eder. Varsa sayfa açılır yoksa girişe yönlendirir
// if(!Auth::check()) ile kontrol edilir. Varsa panel açılır yoksa izin vermez. Bunun için admin grubuna middleware'i ekliyoruz.
// middleware route grubunun içinde admin login/post işlemleri olmamalı yoksa giriş yapma ekranı açılmaz.(isAdmin)
// Ayrıca admin.login/post routelarının sayfası açıldığında oturum açık ise bu sayfaların direkt panel'i açması gerekir. Bunun için
// ayrı bir route grubu oluşturman gerekir. Yani paneldeyken giriş sayfasına girdiğinde yine panele yönlendirme yapması lazım.(isLogin)

// Resource Controller Avantajı şöyle: Create Read Update Delete işlemlerini kolaylaştır. Yani Hazır fonksiyonları bulunan bir
// Sınıf/ conrtroller oluşturur. Route içinde kolaylığı var şöyle ki:
//     Route::resource('makaleler','App\Http\Controllers\Back\ArticleController'); dediğimizde makaleler/a
// a yerine show , edit vb fonksiyonların routeları otomatikmen gelir. Sadece makaleler yazılırsa index Fonksiyonu çalışır.
// Crud kullanmak zorunlu değildir. Kullanırsan işlerini kolaylaştırır. örn: admin.makaleler.create route'u yok ama otomatik oluşur ve kullanılır.


//Dikkat edilirise tüm makaleler sayfasında article modelinde category fonksiyonu olduğu için $article nesnesi ile categori ismine
// kolayca ulaşabiliyorum

// Dökümantasyona göre create sayfasında form elemanları admin.makaleler.store route ile insert yapılır.(yani göndere tıkladığında store
// route çalışır.
// 14 te veritabanı insert işlemleri güzelce anlatılmış.
// resim göstermeme hatası için back-articles-index.blade.php sayfasında  {{$article->image}} yerine-> {{asset($article->image)}} bu beni uğraştırdı yazabilirsin.


//<a href="{{route('admin.makaleler.edit',$article->id)}}" title="Düzenle"
//Bu komutla birlikte düzenlenecek makalenin id’sini alarak ve edit sayfasını açarız.
//return $id."makale id'sidir"; Bu komutla makalenin id’sinin görebilirsin.(edit)

//Sen ResourceControllerin isimlerini serviceprovider ile türkçe yapmadın bunları kullanırken dikkat et.
// $makale=Article::findOrFail($id); Bukomut makalenin olup olmadığını kontrol eder varsa işler yoksa 404
// laravel'de güncellemeiçin route put kullannılır.
// form demek verinin database'e kaydedilmesi demektir. Bunu göre ayarlama yapılır.
// Formdan gelen veriler request ile yakalanır. id ile makale yakalanır.(update). $request->Mycontent ?? "editör hatası";

// 17 'de kategoride temel mvc mantığı var bakabilirsin.

//her oluşturduğun seed'i DatabaseSeeder'a eklemek zorundasin
