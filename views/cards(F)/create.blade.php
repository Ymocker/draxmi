@extends('frontend.layouts.master')

@section('before-styles-end')
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css">    
@endsection

@section('content')
    <h2>{{ trans('cards.create_new') }}</h2>

    {!! Form::open(['url' => 'payment/card']) !!}
        <div class="form-group col-xs-2">
            {!! Form::label('amount', trans('cards.amount')) !!}&nbsp;
            {!! Form::input('number', 'amount', 1, ['class' => 'form-control', 'min' => 1, 'max' => $balance, 'required' => true]) !!}
        </div>
    
        <div class="form-group col-xs-3">
            {!! Form::label('end', trans('cards.end_day')) !!}&nbsp;
            <div class="input-group date">
                <input name="end" id="end" class="form-control" type="text" readonly="" value="{{ \Carbon\Carbon::Now()->format(trans('cards.date-format-php')) }}">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            </div>
        </div>
    
        <div class="form-group col-xs-4">
            {!! Form::label('holder', trans('cards.holder')) !!}&nbsp;
            {!! Form::text('holder', Auth::user()->name, ['class' => 'form-control', 'required' => true]) !!}
        </div>

        {!! Form::submit(trans('cards.create'),['class' => 'btn btn-primary', 'name' => 'yes']) !!}
        {!! Form::submit(trans('cards.cancel'),['class' => 'btn btn-primary', 'name' => 'no']) !!}
    {!! Form::close() !!}
@endsection

@section('before-scripts-end')
    <script type="text/javascript" charset="utf8" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('.date').datepicker({
                format: "{{ trans('cards.date-format-js') }}",
                startDate: "today",
            } );
        } );
    </script>
@endsection