@extends('layouts.app')
@section('content')
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Dropdown Example
            <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="#">HTML</a></li>
            <li><a href="#">CSS</a></li>
            <li><a href="#">JavaScript</a></li>
        </ul>
    </div>
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
