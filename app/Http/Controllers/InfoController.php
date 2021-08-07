<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Info;

class InfoController extends Controller
{
    public function view(){
        return view('welcome');
    }

    public function alldata(){
        $allData = Info::orderBy('id', 'desc')->get();
        return response()->json($allData);
    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required',
            'address' => 'required'
        ]);

        $data = Info::insert([
            'name' => $request->name,
            'address' => $request->address
        ]);
        return response()->json($data);
    }

    public function edit($id){
        $getInfo = Info::find($id);
        return response()->json($getInfo);
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required',
            'address' => 'required',
        ]);

        $data = Info::findOrFail($id)->update([
            'name' => $request->name,
            'address' => $request->address,
        ]);
        return response()->json($data);
    }

    public function delete($id){
        $getInfo = Info::find($id);
        $getInfo->delete();
        return response()->json();
    }

}
