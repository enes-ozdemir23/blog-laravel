@isset($categories)
<div class="col-md-3">
    <div class="card">
        <div class="card-header">
            Kategoriler
        </div>
        <div class="list-group">
            @foreach($categories as $category)
                <li class="list-group-item @if(Request::segment(2)==$category->slug) active @endif">
                    <a @if(Request::segment(2)!=$category->slug) href="{{route('category',$category->slug)}}" @endif>{{$category->name}}  </a>
                    <span class="badge bg-danger float right text-white">{{$category->articleCount()}}</span>
                </li>
            @endforeach
        </div>
    </div>
</div>
@endif


{{--segment 2 demek yani URL'de ki 2. '/' işaretinden sonraki kelimeyi(slug) alması demektir. --}}
{{--2.segment ile seçilen kategori adı aynı ise kategori widget'da bu belirtilir.--}}
{{--Diğer if ile'de seçilen kategori sayfasında bulunuyorsak o kategorinin linkini kaldırır ve tekrar tıklanamaz.--}}
