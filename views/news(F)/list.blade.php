<!-- resources/views/news/list.blade.php -->
@extends('frontend.layouts.master')

@section('title')
    {{ trans('news.news') }}
@stop

@section('content')
<div class="newslist">
    <div class="container">
        <h2>{{ trans('news.news')}}</h2>

        @foreach($newslist as $value)
            <div class="row">
                <div class="col-sm-12">
                    <h2><a href="news/{!! $value->id !!}">{!! $value->header !!}</a></h2>
                    <h5>{!! $value->created_at->format('d-m-Y'); !!}</h5>
                </div>
                @if ($value->picture != '')
                    <div class="col-sm-4">
                        <img src="img/news/{!! $value->picture !!}" class="img-responsive" />
                    </div>
                    <div class="col-sm-8">
                @else
                    <div class="col-sm-12">
                @endif
                    {!! mb_substr($value->article, 0, 400) !!}...
                    <p><a href="news/{!! $value->id !!}" class "header-hyperlink">{{ trans('news.readmore')}}</a><p>
                </div>        
            </div>
        @endforeach

        {!! str_replace('/?', '?', $newslist->render()) !!}
        
    </div>
</div>
@stop