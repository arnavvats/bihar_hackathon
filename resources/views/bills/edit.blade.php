@extends('layouts.app')
@section('content')
    {!! Form::model($bill,['method'=>'PATCH','action'=>['BillController@update',$bill->id],'files'=>'true']) !!}
    <div class="form-group">
        {!!Form::label('File','Replace File:')!!}
        {!! Form::file('scanned_copy_id',null,['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!!Form::label('description','Description:')!!}
        {!! Form::textarea('description',null,['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!!Form::submit('Upload Bill',['class'=>'btn btn-primary','rows'=>3])!!}
    </div>
    {!! Form::close() !!}
    {!! Form::model($bill,['method'=>'DELETE','action'=>['BillController@destroy',$bill->id]]) !!}
    <div class="form-group">
        {!!Form::submit('Delete Bill',['class'=>'btn btn-primary','rows'=>3])!!}
    </div>
    {!! Form::close() !!}

@endsection