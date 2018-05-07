@extends('backend.layouts.master')

@section('page-header')
    <h1>
        {{ trans('menus.news_edit') }}
        <small>{{ trans('') }}</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('admin.news.index')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.news_edit') }}</a></li>
    <li class="active">{!! trans('news.add_news') !!}</li>
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{ trans('news.add_news') }}</h3>
          <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
            {!! Form::open(['url' => 'admin/news', 'files' => 'true']) !!}
            <div class="form-group">
                {!! Form::label('header', trans('news.header')) !!}
                {!! Form::text('header', null, ['class' => 'form-control', 'placeholder' => trans('news.header_this')]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('article', trans('news.article')) !!}
                {!! Form::textarea('article', null, ['class' => 'form-control', 'placeholder' => trans('news.article_txt')]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('image', trans('news.picture')) !!}
                {!! Form::file('image', ['class' => 'form-control']); !!}
            </div>
            <div class="form-group">
                {!! Form::label('publish', trans('news.publish')) !!}&nbsp;
                {!! Form::checkbox('publish', '1', ['class' => 'form-control']) !!}
            </div>
            
                {!! Form::submit(trans('news.add_this_news'),['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div><!-- /.box-body -->
    </div><!--box box-success-->
    
    <script src="//cdn.ckeditor.com/4.5.3/standard/ckeditor.js"></script>
    
    <script>
    CKEDITOR.replace( 'article', {
        customConfig: '/js/vendor/ckeditor_config.js'
    });</script>
@endsection

 