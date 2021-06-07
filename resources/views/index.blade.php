@extends('layout')

@section('title', 'Main page')

@section('main')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-4xl mt-3 mb-3">News list</h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                @foreach($news as $news_item)
                    <div class="mb-3">
                        <h3 class="font-medium mt-3 mb-3">{{$news_item->title}}</h3>
                        <p>{!! Str::limit($news_item->info, 200) !!}</p>
                        <a href="@if(empty($news_item->target_blank))/news/{{$news_item->id}}@else{{$news_item->link}}@endif"
                           class="mb-3 btn btn-primary"
                           @if(!empty($news_item->target_blank))target="_blank"@endif
                        >
                            Read more
                        </a>
                        <hr>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 col-12">
                {{$news->links()}}
            </div>
        </div>
    </div>
@endsection

@section('additional-scripts')
    <script src="{{mix('js/vue/app.js')}}"></script>
@endsection
