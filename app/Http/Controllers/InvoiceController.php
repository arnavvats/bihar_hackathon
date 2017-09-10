<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('payer', ['only' => ['index','create', 'store', 'edit', 'delete']]);
        $this->middleware('Lists',['only'=>'index']);
    }
    public function index(Request $request)
    {
        $user = Auth::user();
        $type = $request['type'];
        $invoices = $user->invoices()->get()->sortBy($type);
        return view('invoices/index',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $bill = Bill::find($id);
        return view('invoices/create',compact('bill'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'description'=>'required|min:20|max:250',
            'scanned_copy_id'=>'required|max:500|mimes:png,jpg'
        ]);
        $user = Auth::user();
        $input = $request->all();
        if($file  = $request->file('scanned_copy_id')){
            $name = time().$file->getClientOriginalName();
            $file->move('images',$name);
            $input['scanned_copy_path'] = $name;
            $user->invoices()->create($input);

        }
        return redirect('/home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $invoice = Bill::findOrFail($id)->invoice;
        if(!($invoice->user_id==$user->id||$invoice->bill->user_id==$user->id)){
            return redirect('/home');
        }
        return view('invoices/show',compact('invoice'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $invoice = Bill::findOrFail($id)->invoice;
        if(Auth::user()->id!=$invoice->user_id)
            {return redirect('/home');}
        return view('invoices/edit',compact('invoice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'description'=>'min:20|max:250',
            'scanned_copy_id'=>'max:500|mimes:png,jpg'
        ]);
        $input = $request->all();
        $invoice = Bill::find($id)->invoice;
        if(Auth::user()->id!=$invoice->user_id)
        {return redirect('/home');}
        if($file  = $request->file('scanned_copy_id')){
            $name = time().$file->getClientOriginalName();
            $file->move('images',$name);
            if(file_exists('images/'.$invoice->scanned_copy_path))
                unlink('images/'.$invoice->scanned_copy_path);
            $invoice['scanned_copy_path'] = $name;
        }
        $invoice['description'] = $input['description'];
        $invoice->save();

        return redirect('/home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Bill::find($id)->invoice;
        if(Auth::user()->id!=$invoice->user_id){
        return redirect('/home');
        }
        if(file_exists('images/'.$invoice->scanned_copy_path)) {
            unlink('images/' . $invoice->scanned_copy_path);
            $invoice->delete();
        }
        return redirect('/invoice/index');
    }

    //public function lists(Request $request)

}
