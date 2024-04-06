@extends('front.layouts.master')
@section('title',$category->name.' Kategorisi | '.count($articles).' yazı bulundu') <!--veritabnında alınan kategori isimlerini burada kullanıyoruz-->
@section('content')
    <div class="col-md-9 mx-auto">
        @include('front.widgets.articleList')
        </div>
@include('front.widgets.categoryWidget')
@endsection

<!--article php'de yapılan ilişkisel bağlantılar burada düzenlendi (getCategory fonksiyonu ile) -->
