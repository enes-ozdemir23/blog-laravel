@if(count($articles)>0)
@foreach($articles as $article)
    <div class="post-preview">
        <a href="{{route('single',[$article->getCategory->slug,$article->slug])}}">
            <h2 class="post-title">
                {{$article->title}}
            </h2>
            <img src="{{asset($article->image)}} " width="600" height="600"/>
            <h3 class="post-subtitle">
                {!!Str::limit($article->content,50)!!}
            </h3>
        </a>
        <p class="post-meta"> Kategori :
            {{$article->getCategory->name}}
            <span class="float-right">{{$article->created_at->diffForHumans()}}</span> </p>
    </div>
    @if(!$loop->last)
        <hr>
    @endif
@endforeach
<div class="float-center">
    {{$articles->links()}}
</div>
@else
    <div class="alert alert-danger">
        <h1>Bu kategoriye ait yazı bulunamadı.</h1>
    </div>
@endif


{{--homepage ve category sayfaları burdaki kodu alıp kullanıor. çünkü kod tekrarı yaptırmıyoruz--}}
