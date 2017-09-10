@extends('layouts.app')
@section('content')
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Sort By
            <span class="caret"></span></button>
        <ul class="dropdown-menu" id="sort">
            <li val="id"><a>Last Created</a></li>
            <li val="updated_at"><a>Last Updated</a></li>
            <li val="paid"><a href="">Paid</a></li>
            <li val="verified"><a>Verified</a></li>
        </ul>
    </div>
    <div id="bills">
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
    </div>
@endsection
@section('js')
    $(document).ready(
    function(){
        $('#sort > li').click(
            function(){
    var pageURL = $(location).attr("href");
    var data =  {
        'type': $(this).attr('val')
    }
    console.log(data);
            $.ajax({
                type:"GET",
                url:pageURL,
                data:data,
                success:function(data){
                    console.log(data);
                    $('#bills').empty();
                    var toAppend = '';
                    for(var i=0;i<data.length;i++){
                    toAppend += '<div class="row"><a href="'+pageURL+'/' + data[i]['id'] + '"><h1>Bill No. - '+data[i]['id']+'</h1></a><div class="col-xs-6">'+data[i]['description']+'</div><div class="col-xs-6"><img src="images/'+ data[i]['scanned_copy_path']+'"</div></div> ';
                    }
                    $('#bills').append(toAppend);
                    console.log(toAppend);
                }

            });
        });
    }
    );
@endsection