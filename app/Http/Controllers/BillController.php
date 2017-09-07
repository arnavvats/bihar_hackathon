<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\File;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $bills = $user->bills()->get();
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
        $user = Auth::user();
        $input = $request->all();

       // return $user;
        if($file  = $request->file('scanned_copy_id')){
            $name = time().$file->getClientOriginalName();
            $file->move('images',$name);
            $input['scanned_copy_path'] = $name;
            $user->bills()->create($input);

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
        $bill = Bill::findOrFail($id);
        return view('bills/show',compact('bill'));
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
        return view('bills/edit',compact('bill'));
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
        $input = $request->all();
        $bill = Bill::find($id);
        if($file  = $request->file('scanned_copy_id')){
            $name = time().$file->getClientOriginalName();
            $file->move('images',$name);
            if(file_exists('images/'.$bill->scanned_copy_path))
            unlink('images/'.$bill->scanned_copy_path);
            $bill['scanned_copy_path'] = $name;
        }
        $bill['description'] = $input['description'];
        $bill->save();

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
        $bill = Bill::find($id);
        if(file_exists('images/'.$bill->scanned_copy_path))
            unlink('images/'.$bill->scanned_copy_path);
        $bill->delete();
    }
}
