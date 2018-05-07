@extends('backend.layouts.master')

@section('page-header')
    <h1>
        {{ trans('menus.news_edit') }}
        <small>{{ trans('news.update') }}</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('admin.news.index')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.news_edit') }}</a></li>
    <li class="active">{!! trans('news.update') !!}</li>
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">{{ trans('news.update') }}</h3>
          <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body" >
            {!! Form::model($news, ['url' => ['admin/news', $news->id], 'files' => 'true', 'method' => 'put']) !!}
            <div class="form-group">
                {!! Form::label('header', trans('news.header')) !!}
                {!! Form::text('header', null, ['class' => 'form-control', 'placeholder' => trans('news.header_this')]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('article', trans('news.article')) !!}
                {!! Form::textarea('article', null, ['class' => 'form-control', 'placeholder' => trans('news.article_txt')]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('picture', trans('news.picture')) !!}
                {!! Form::text('del', 'pic', ['style' => 'visibility: hidden', 'id' => 'del']) !!}
                @if ($news->picture == '')
                    {!! Form::file('picture', ['class' => 'form-control']); !!}
                @else
                    <div id="add-pic-but" style="visibility: hidden; display: none;" >
                        {!! Form::file('picture', ['class' => 'form-control']); !!}
                    </div>
                    <div id="pic-but">
                        <img src="../../../img/news/{!! $news->picture !!}"  width="250" />
                        <a class="btn btn-xs btn-danger" onclick="delPic()"><i class="fa fa-times"></i></a>
                    </div>
                @endif
            </div>
            <div class="form-group" >
                {!! Form::label('status', trans('news.publish')) !!}&nbsp;
                {!! Form::checkbox('status', '1', ['class' => 'form-control']) !!}
            </div>
            
                {!! Form::submit(trans('news.add_this_news'),['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div><!-- /.box-body -->
    </div><!--box box-success-->
    <script src="//cdn.ckeditor.com/4.5.3/standard/ckeditor.js"></script>
    <script>CKEDITOR.replace( 'article', {
        customConfig: '/js/vendor/ckeditor_config.js'
    });</script>
    <script>
        function delPic () {
            document.getElementById('pic-but').style.display ='none';
            document.getElementById('add-pic-but').style.display ='';
            document.getElementById('add-pic-but').style.visibility ='visible';
            document.getElementById('del').value ='delete';
        }
    </script>
@endsection

 