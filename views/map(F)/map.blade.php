@extends('frontend.layouts.master')

@section('after-styles-end')
<style>
    #map {
        width: 600px;
        height: 500px;
    }
</style>
@section('javascript')
    @include('frontend.map.mapjs')
@endsection

@section('content')
    <div id="location">
        <select id="country_id">
            <option value="0">- {{ trans('map.sel-country') }} -</option>
            @foreach ($countries as $country)
                <option value="{{ $country['id'] }}">{{ $country['country'] }}</option>
            @endforeach
        </select>
        <select id="state_id">
            <option value="0">- {{ trans('map.sel-state') }} -</option>
        </select>
        <select id="city_id">
            <option value="0">- {{ trans('map.sel-city') }} -</option>
        </select>
    </div>
    <div id="map"></div>
@endsection
