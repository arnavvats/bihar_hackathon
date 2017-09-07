@extends('layouts.app')
@section('content')
  {!! Form::model($invoice,['method'=>'PATCH','action'=>['InvoiceController@update',$invoice->bill_id],'files'=>'true']) !!}
  <div class="form-group">
    {!!Form::label('File','Replace File:')!!}
    {!! Form::file('scanned_copy_id',null,['class'=>'form-control']) !!}
  </div>
  <div class="form-group">
    {!!Form::label('description','Description:')!!}
    {!! Form::textarea('description',null,['class'=>'form-control']) !!}
  </div>
  <div class="form-group">
    {!!Form::submit('Upload Invoices',['class'=>'btn btn-primary','rows'=>3])!!}
  </div>
  {!! Form::close() !!}
  {!! Form::model($invoice,['method'=>'DELETE','action'=>['InvoiceController@destroy',$invoice->bill_id]]) !!}
  <div class="form-group">
    {!!Form::submit('Delete Invoice',['class'=>'btn btn-primary','rows'=>3])!!}
  </div>
  {!! Form::close() !!}

@endsection
