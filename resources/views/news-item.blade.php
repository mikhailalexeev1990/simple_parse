@extends('layout')

@section('title', 'Main page')

@section('main')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <a href="/" class="mt-3 mb-3 btn btn-primary">Go back</a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if(!empty($news_item))
                    <h1 class="text-4xl">{{$news_item->title}}</h1>

                    @if(empty($news_item->target_blank))
                        @php
                            $descriptions = explode("\n",$news_item->info);
                        @endphp
                        @foreach($descriptions as $index => $text)
                            <p class="text mt-4 mb-4">{!! trim($text) !!}</p>
                            @if ($loop->first && !empty($news_item->image))
                                <p>
                                    <img src="{{$news_item->image->path}}"
                                         alt="{{$news_item->image->name}}"
                                    >
                                </p>
                            @endif
                        @endforeach
                    @else
                        <br>
                        <a href="{{$news_item->link}}"
                           target="_blank"
                           class="btn btn-primary">Read</a>
                    @endif
                @else
                    <h1>News wasn't found!</h1>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('additional-scripts')
    <script src="{{mix('js/vue/app.js')}}"></script>
@endsection
