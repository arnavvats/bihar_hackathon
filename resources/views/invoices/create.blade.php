@extends('layouts.app')

@section('content')
  {!! Form::open(['method'=>'POST','action'=>['InvoiceController@store',$bill->id],'files'=>'true']) !!}
  <div class="form-group">
    {!!Form::label('File','File:')!!}
    {!! Form::file('scanned_copy_id',null,['class'=>'form-control']) !!}
  </div>
  <div class="form-group">
    {!!Form::label('description','Description:')!!}
    {!! Form::textarea('description',null,['class'=>'form-control']) !!}
  </div>
  <div class="form-group">
    {!!Form::submit('Upload Invoice',['class'=>'btn btn-primary','rows'=>3])!!}
  </div>
  <div class="form-group">
    {!! Form::hidden('bill_id',$bill->id) !!}
  </div>
  {!! Form::close() !!}
@endsection