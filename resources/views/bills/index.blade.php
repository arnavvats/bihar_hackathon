@extends('layouts.app')
@section('content')
    @foreach($bills as $bill)
        <div class="row">
          <a href="{{action("BillController@show",$bill->id)}}"><h1>Bill No. - {{$bill->id}}</h1>
          </a>
            <div class="col-xs-6">
            {{$bill->description}}
            </div>
            <div class="col-xs-6">
                <img src="images/{{$bill->scanned_copy_path}}">
            </div>
        </div>
    @endforeach
@endsection