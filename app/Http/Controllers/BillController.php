<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use App\Notifications\NewBill;
use Illuminate\Http\Request;
use App\Bill;
//use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\File;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Enforce middleware.
     */
    public function __construct()
    {
        $this->middleware('biller', ['only' => ['create', 'store', 'edit', 'delete']]);
       // $this->middleware('Lists',['only'=>'index']);
    }
    public function index(Request $request)
    {
        session()->flash('status', 'Task was successful!');
        $user = Auth::user();

        if($request->ajax()&&
            $request['type']) {
            $type = $request['type'];
            if($user->hasRole('Biller')) {
                $bills = $user->bills()->orderBy($type, 'desc')->get();
            }
            else
            {
                $bills = Bill::orderBy($type,'desc')->get();
            }
            return $bills;
        }
        if($user->hasRole('Biller'))
        {$bills = $user->bills()->get();}
        else if($user->hasRole('Payer'))
        {$bills=Bill::all();}
        return view('bills/index',compact('bills'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bills/create');
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

       // return $user;
        if($file  = $request->file('scanned_copy_id')){
            $name = time().$file->getClientOriginalName();
            $file->move('images',$name);
            $input['scanned_copy_path'] = $name;
            $id = $user->bills()->create($input)->id;
            $users = User::role('Payer')->where('verified',1)->get();
            Notification::send($users, new NewBill);
        }
        return redirect()->action(
            'BillController@show',['id'=> $id]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bill = Bill::findOrFail($id);
        $user = Auth::user();

        if($bill->user_id==$user->id || $user->hasRole('Payer')){
             return view('bills/show',compact('bill'));
        }

        return redirect()->back();

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $bill = Bill::findOrFail($id);
        if (Auth::user()->id == $bill->user_id) {
            return view('bills/edit', compact('bill'));
        }
        return redirect()->back();
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
        $bill = Bill::find($id);
        if(Auth::user()->id!=$bill->user_id)
        {abort(403, 'Unauthorized action.');}
        if($file  = $request->file('scanned_copy_id')){
            $name = time().$file->getClientOriginalName();
            $file->move('images',$name);
            if(file_exists('images/'.$bill->scanned_copy_path))
            unlink('images/'.$bill->scanned_copy_path);
            $bill['scanned_copy_path'] = $name;
        }
        $bill['description'] = $input['description'];
        $bill->save();

        return redirect('/bills');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bill = Bill::find($id);
        if(Auth::user()->id!=$bill->user_id)
        {
            if(file_exists('images/'.$bill->scanned_copy_path))
                unlink('images/'.$bill->scanned_copy_path);
            $bill->delete();
            session()->flash('status', 'Task was successful!');
            return redirect()->back();
        }

         abort(403, 'Unauthorized action.');
    }
    public function  verify($id){
            if(Auth::user()->hasRole('Payer')){
                $bill = Bill::find($id);
                $bill->verified = 1;
                $bill->save();
                return redirect()->back();
            }
            abort(100,'Unauthorized access.');
    }

}
