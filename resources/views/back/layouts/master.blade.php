@include('back.layouts.header')
@include('back.layouts.menu')
@yield('content')
@include('back.layouts.footer')






{{--layouts klasöründeki parçalanmış temalar buradan kontrol edilir. Çağrılır gösterilir vb...--}}
{{--content kısmında dashboard.blade.php vardır--}}
{{--master ile parçalar birleştirildi . master ise dashboard.blade.php' de çağrıldı. gösterilen kısım yani view dashboard.blade.php'dir unutma--}}
{{--eğer ki dashboar.blade.php'de section arasına bir şey yazmazsan header menu footer harici bir şey gözükmez ekranda. adını yazarsan adını sadece yazar--}}
{{--yield(title) fonksiyonunu menünün sonundaki panel yazısı içinde kullandık.Yani bir fonksiyonla birden fazla iş yaptık.--}}
