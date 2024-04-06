@extends('front.layouts.master')
@section('title',$article->title)
@section('bg',$article->image)
@section('content')
                <div class="col-md-9 mx-auto">
                    {!! $article->content !!}
                    <br/><br/>
                    <span class="text-danger">Okuma Sayısı : <b>{{$article->hit}}</b></span>
                </div>
@include('front.widgets.categoryWidget')
@endsection

<!--article php'de yapılan ilişkisel bağlantılar burada düzenlendi (getCategory fonksiyonu ile.) -->
{{--çift ünlem  içine yazılanlar html içine yazılan yazı gibi gözükür--}}
<!-- Eğer ki bu 'Anasayfa' kısmı olmasaydı yield'ın içindeki ekrana yazılacaktı-->
