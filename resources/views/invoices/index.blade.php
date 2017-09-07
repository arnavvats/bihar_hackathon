@extends('layouts.app')
@section('content')
  @foreach($invoices as $invoice)
    <div class="row">
      <a href="{{action("InvoiceController@show",$invoice->bill_id)}}"><h1>Bill No. - {{$invoice->bill_id}}</h1>
      </a>
      <div class="col-xs-6">
        {{$invoice->description}}
      </div>
      <div class="col-xs-6">
        <img src="images/{{$invoice->scanned_copy_path}}">
      </div>
    </div>
  @endforeach
@endsection