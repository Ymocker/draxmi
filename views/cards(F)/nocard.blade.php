@extends('frontend.layouts.master')

@section('content')
    <div>
        <h4>{{ $message }}</h4>
    </div>
    <a href="/payment/card">{{ trans('cards.back') }}</a>
@endsection


