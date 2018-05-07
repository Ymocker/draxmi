@extends('frontend.layouts.master')

@section('before-styles-end')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.9/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css">
@endsection

@section('content')

<div>
    <a href="card/create" class="btn btn-primary" title="{{ trans('cards.create') }}">{{ trans('cards.create') }}</a>
</div>

<div class="form-group col-xs-2">
    <select class="form-control" id="status-select">
      <option>{{ trans('cards.all') }}</option>
      <option>{{ trans('cards.active') }}</option>
      <option>{{ trans('cards.expired') }}</option>
      <option>{{ trans('cards.deleted') }}</option>
    </select>
</div>

<div class="input-group col-xs-2 date">
    <input name="start" id="start" class="form-control" type="text" value="" readonly="" placeholder="{{ trans('cards.start_day') }}">
    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
</div>

<div class="input-group col-xs-2 date">
    <input name="end" id="end" class="form-control" type="text" value="" readonly="" placeholder="{{ trans('cards.end_day') }}">
    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
</div>

<table id="cardtable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>{{ trans('cards.card_no') }}</th>
            <th>{{ trans('cards.holder') }}</th>
            <th>{{ trans('cards.start_day') }}</th>
            <th>{{ trans('cards.end_day') }}</th>
            <th>{{ trans('cards.verification') }}</th>
            <th>{{ trans('cards.amount') }}</th>
            <th>{{ trans('cards.balance') }}</th>
            <th>{{ trans('cards.status') }}</th>
        </tr>
    </thead>
</table>

@endsection

@section('after-scripts-end')
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#cardtable').DataTable( {
                "ajax": "card/cardlist",
                "language": {
                    "url": "{{ trans('cards.data-table-localisation') }}"
                },
                "columns": [
                    { data: 'id' },
                    { data: 'cholder' },
                    { data: 'start' },
                    { data: 'end_date' },
                    { data: 'vnumber' },
                    { data: 'climit' },
                    { data: 'cbalance', 'searchable': false},
                    { data: 'status'}
                ]
            } );
            
            $('.date').datepicker({
                format: "{{ trans('cards.date-format-js') }}",
            } );
                       
            var table = $('#cardtable').DataTable();
            $('#start').on( 'change', function () {
            table
                .columns( 2 )
                .search( this.value )
                .draw();
            } );
            
            $('#end').on( 'change', function () {
            table
                .columns( 3 )
                .search( this.value )
                .draw();    
            } );
            
            $('#status-select').on( 'change', function () {
                if (this.value == 'All') {
                    table
                        .columns( 7 )
                        .search( '' )
                } else {
                    table
                        .columns( 7 )
                        .search( this.value )
                }
                table.draw();
            } );
        } );

        $('#start').click(function(){
            this.value = '';
            $(this).change();
        });
        $('#end').click(function(){
            this.value = '';
            $(this).change();
        });
    </script>
@endsection
