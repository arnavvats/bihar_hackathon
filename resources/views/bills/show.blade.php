@extends('layouts.app')
@section('content')
  <div class="row">
    <h1>Bill No. - {{$bill->id}}</h1>
    </a>
    <div class="col-xs-6">
      {{$bill->description}}
    </div>
    <div class="col-xs-6">
      <img src="{{URL::asset('/images/'.$bill->scanned_copy_path)}}">
    </div>
  </div>
    {!! Form::open(['method'=>'GET','action'=>['BillController@edit',$bill->id]]) !!}
    <div class="form-group">
      {!!Form::submit('Edit Bill',['class'=>'btn btn-primary','rows'=>3])!!}
    </div>
  {!! Form::close() !!}
@endsection