@extends('layout')

@section('title', 'Main page')

@section('main')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>News list</h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                @foreach($news as $news_item)
                    <div>
                        <h3 class="mt-3 mb-3">{{$news_item->title}}</h3>
                        <a href="@if(empty($news_item->target_blank))/news/{{$news_item->id}}@else{{$news_item->link}}@endif"
                           class="btn btn-primary"
                           @if(!empty($news_item->target_blank))target="_blank"@endif
                        >
                            Перейти в новость
                        </a>
                        <hr>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection


@section('additional-scripts')
    <script src="{{mix('js/vue/app.js')}}"></script>
@endsection

