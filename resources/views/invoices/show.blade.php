@extends('layouts.app')
@section('content')
  <div class="row">
    <h1>Bill No. - {{$invoice->bill_id}}</h1>
    </a>
    <div class="col-xs-6">
      {{$invoice->description}}
    </div>
    <div class="col-xs-6">
      <img src="images/{{$invoice->scanned_copy_path}}">
    </div>
  </div>
  {!! Form::open(['method'=>'GET','action'=>['InvoiceController@edit',$invoice->bill_id]]) !!}
  <div class="form-group">
    {!!Form::submit('Edit Invoice',['class'=>'btn btn-primary','rows'=>3])!!}
  </div>
  {!! Form::close() !!}
@endsection