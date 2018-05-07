@extends('frontend.layouts.master')

@section('before-styles-end')
    <link type="text/css" rel="stylesheet" href="/css/cardview.css">
@endsection

@section('content')
    <div id="card-print">
        <div id="card-back-print">
        {!! HTML::image("img/cards/card_view.png", "card") !!}
        </div>
        
        <div id="card-number-print">{{ $c_number }}</div>
        <div id="label-code-print">{{ trans('cards.code') }}</div>
        <div id="code-print">{{ $v_number }}</div>
        <div id="expire-group-print">
            <div id="label-expire-print">{{ trans('cards.exp') }}</div>
            <div id="expire-print">{{ $c_end }}</div>
        </div>
        <div id="holder-print">{{ $c_holder }}</div>
    </div>
    {!! Form::open(['route' => ['payment.card.destroy', $id], 'id' => 'printForm', 'hidden' => 'true', 'method' => 'delete']) !!}
    {!! Form::close() !!}
    
    <button type="button" class="btn btn-primary" onclick="confirmDel()" value="{{ $id }}"><span class="glyphicon glyphicon-trash"></span> {{ trans('cards.delete') }}</button>
    <button type="button" class="btn btn-primary" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> {{ trans('cards.print') }}</button>
    
    <div id="back-cardlist-ref">
        <a href="/payment/card">{{ trans('cards.back') }}</a>
    </div>
@endsection

@section('after-scripts-end')
    <script>
    function confirmDel () {
        if (confirm("{{ trans('cards.you_are') }}" + " {{ $c_number }}" + ". {{ trans('cards.sure') }}") === true) {
            $("#printForm").submit();
        }
    }
    </script>
@endsection


