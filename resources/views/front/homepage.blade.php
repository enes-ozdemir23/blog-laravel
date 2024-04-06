@extends('front.layouts.master')
@section('title','Anasayfa') <!-- Eğer ki bu kısım olmasaydı yield'ın içindeki ekrana yazılacaktı-->
@section('content')
    <div class="col-md-9 mx-auto">
        @include('front.widgets.articleList')
    </div>
@include('front.widgets.categoryWidget')
@endsection

<!--  article php'de yapılan ilişkisel bağlantılar burada düzenlendi (getCategory fonksiyonu ile) -->
{{-- " @include('front.widgets.articleList') " bu komutla birlikte kod tekrarını önledik--}}
