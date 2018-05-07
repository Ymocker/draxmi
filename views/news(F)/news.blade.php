<!-- resources/views/news/news.blade.php -->
@extends('frontend.layouts.master')

@section('title')
    {!! $one->header !!}
@stop

@section('content')  
        <h2>
            {!! $one->header !!}
        </h2>
        <h5 class="col-sm-12">
            {!! $one->created_at->format('d-m-Y'); !!}
        </h5>
        @if ($one->picture != '')
             <div class="col-sm-12">
                <img src="../img/news/{!! $one->picture !!}" class="img-responsive" />
            </div>
        @endif
        <div class="col-sm-12">
            {!! $one->article !!}
        </div>  
        <a href="../news" class "back-link">{{ trans('news.back') }}</a>
@stop