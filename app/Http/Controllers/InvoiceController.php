<?php

namespace App\Http\Controllers;

use App\User;
use App\Invoice;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use App\Notifications\InvoiceCreated;
use Illuminate\Http\Request;
use App\Bill;
//use Illuminate\Notifications\Notification;
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
        if($bill->verified == 0){abort(401,'Bill Unverified.');}
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
            $bill = $user->invoices()->create($input)->bill;
            $bill->paid = 1;
            $bill->save();
            Notification::send($user, new InvoiceCreated);

        }
        return redirect(action('BillController@show',$bill->id));
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
            abort(403,'Unauthorized Action');
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
            {abort(403,'Unauthorized Action');}
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
        {abort(403,'Unauthorized Action');}
        if($file  = $request->file('scanned_copy_id')){
            $name = time().$file->getClientOriginalName();
            $file->move('images',$name);
            if(file_exists('images/'.$invoice->scanned_copy_path))
                unlink('images/'.$invoice->scanned_copy_path);
            $invoice['scanned_copy_path'] = $name;
        }
        $invoice['description'] = $input['description'];
        $invoice->save();

        return redirect('/invoice');
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
        abort(403,'Unauthorized Action');
        }
        if(file_exists('images/'.$invoice->scanned_copy_path)) {
            unlink('images/' . $invoice->scanned_copy_path);
            $invoice->delete();
        }
        return redirect('/invoice/index');
    }

    //public function lists(Request $request)

}
