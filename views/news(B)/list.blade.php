<!-- resources/views/backend/list.blade.php -->
@extends('backend.layouts.master')

@section('page-header')
    <h1>
        {{ trans('menus.news_edit') }}
        <small>{{ trans('') }}</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('admin.news.index')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.news_edit') }}</a></li>
    <li class="active">{{ trans('news.here') }}</li>
@stop

@section('content')
<div class="newslist">
    <div>
        {!! Form::open(['url' => 'admin/news/create', 'method'=>'GET']) !!}
            {!! Form::submit(trans('news.add_news'),['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
        
        <table class="table table-striped table-bordered table-hover" stylе="display:none;">
            <thead>
            <tr>
                <th>ID</th>
                <th>{!! trans('news.header') !!}</th>
                <th>{!! trans('news.picture') !!}</th>
                <th>{!! trans('news.article') !!}</th>
                <th>{!! trans('news.publish') !!}</th>
                <th>{!! trans('news.created') !!}</th>
                <th>{!! trans('news.actions') !!}</th>
            </tr>
            </thead>
            
        <tbody>
        @foreach($newslist as $value)
        <tr>
            <td>
                <div class="id">
                     {!! $value->id !!}
                </div>
            </td>
            <td>
                <div class="news-header">
                    {!! $value->header !!}
                </div>
            </td>
            <td>
                @if ($value->picture != '')
                    <div class="news-picture">
                        <img src="../img/news/{!! $value->picture !!}"  width="100" />
                    </div>
                @endif
            </td>
            <td>
                <div class="news-text">
                    {!! mb_substr($value->article, 0, 300) !!}...
                </div>
            </td>
            <td>
                <div class="news-status">
                    @if ($value->status == 1)
                        {{ trans('news.yes') }}
                    @else
                        {{ trans('news.no') }}
                    @endif
                </div>
            </td>
            <td>
                <div class="news-date">
                    {!! $value->created_at->format('d-m-Y') !!}
                </div>
            </td>
            <td>
                <div class="btn-toolbar">
                    <div class="btn-group">
                        <a href="news/{!! $value->id !!}/edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>
                        <a class="btn btn-xs btn-danger" onclick="confirmDel(this)" value="{!! $value->id !!}"><i class="fa fa-times"></i></a>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
        </table>

        {!! str_replace('/?', '?', $newslist->render()) !!}
    </div>
</div>
@if ($newslist->count()!=0)
<script>
function confirmDel (obj) {
    if (confirm("Delete news with ID = " + obj.getAttribute('value') + " ?") === true) {
        window.location.href = "news/" + obj.getAttribute('value') + "/delete";
    }
}
</script>
@endif

@stop