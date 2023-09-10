<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function adminindex()
    {
        $products=Product::get();
        return view('admin.index',compact('products'));
    }

    public function userindex(){
        $products=Product::get();
        return view('user.index',compact('products'));
    }
    public function men(){
        $products=Product::get();
        return view('admin.men',compact('products'));
    }
    public function women(){
        $products=Product::get();
        return view('admin.women',compact('products'));
    }
    public function usermen(){
        $products=Product::get();
        return view('user.men',compact('products'));
    }
    public function userwomen(){
        $products=Product::get();
        return view('user.women',compact('products'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->name=="" || $request->information=="" || $request->gender=="" || $request->price==""){
            return redirect('createproduct')->with('messege',"enter all data");
        }
        $filename="";
        if($request->hasFile('image')){
            $file=$request->file('image');
            $extension=$file->getClientOriginalExtension();
            if($extension=="jpg" || $extension=="png"){
                $filename=time() . '.' . $extension;
            $file->move(public_path('photos'),$filename);
            }else{
                return redirect('createproduct')->with('messege',"image extension must be jpg or png");
            }
        }else{
            return redirect('createproduct')->with('messege',"enter all data");
        }
        Product::create([
            "name"=>$request->name,
            "information"=>$request->information,
            "gender"=>$request->gender,
            "price"=>$request->price,
            "image"=>$filename
        ]);
        //return view('admin.index');
        return redirect()->route('adminindex');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id,Request $request)
    {
        if($request->name=="" || $request->information=="" || $request->gender=="" || $request->price==""){
            return redirect()->route('updateform',$id)->with('messege',"enter all data");
        }
        $filename="";
        if($request->hasFile('image')){
            $file=$request->file('image');
            $extension=$file->getClientOriginalExtension();
            if($extension=="jpg" || $extension=="png"){
                $filename=time() . '.' . $extension;
            $file->move(public_path('photos'),$filename);
            }else{
                return redirect()->route('updateform',$id)->with('messege',"image extension must be jpg or png");
            }
        }
        $products=Product::find($id);
        if($filename==""){
            $products->update([
            "name"=>$request->name,
            "information"=>$request->information,
            "gender"=>$request->gender,
            "price"=>$request->price
        ]);
        }else{
            $products->update([
            "name"=>$request->name,
            "information"=>$request->information,
            "gender"=>$request->gender,
            "price"=>$request->price,
            "image"=>$filename
        ]);
        }
        
        return redirect()->route('adminindex');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $products=Product::find($id);
        return view('admin.update',compact('products'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Product::find($id)->delete();
        return redirect()->route('adminindex');
    }
    public function search(Request $request){
        $products=Product::get();
        $name=$request->name;
        return view('admin.search',compact('products','name'));
    }
    public function usersearch(Request $request){
        $products=Product::get();
        $name=$request->name;
        return view('user.search',compact('products','name'));
    }
}
