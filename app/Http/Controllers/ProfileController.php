<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function adminindex(){
        return view('admin.index');
    }
    public function userindex(){
        return view('user.index');
    }
    public function login(Request $request)
    {
        $profiles=Profile::firstWhere('email', $request->email);
        if($profiles){
            if($request->password==$profiles->password){
            
            
            if($profiles->role=="admin"){
                $request->session()->put('userid',$profiles->id);
                return redirect('adminindex');
                //return redirect('adminindex')->with('userid',$profiles->id)->with('userimage',$profiles->image);
                //return redirect()->route('adminindex',['userid'=>$profiles->id , 'username'=>$profiles->firstname]);
                
            }else if($profiles->role=="user"){
                $request->session()->put('userid',$profiles->id);
                return redirect('userindex');
                //return redirect('userindex')->with('userid',$profiles->id);
                
            }
        }else{
            return redirect('/')->with('messege',"invalid email or password");
        }
        }else{
            return redirect('/')->with('messege',"invalid email or password");
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->firstname=="" || $request->lastname=="" || $request->age=="" || $request->email=="" || $request->password=="" ){
            return redirect('register')->with('messege',"enter all data");
        }
        if($request->age<18){
            //$messege=
            return redirect('register')->with('messege',"must be bigger than 18");
        }
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return redirect('register')->with('messege',"email format error");
        }
        if(strlen($request->password)<8){
            return redirect('register')->with('messege',"password must be more than 8 charcters");
        }
        $filename="";
        if($request->hasFile('image')){
            $file=$request->file('image');
            $extension=$file->getClientOriginalExtension();
            if($extension=="jpg" || $extension=="png"){
                $filename=time() . '.' . $extension;
            $file->move(public_path('photos'),$filename);
            }else{
                return redirect('register')->with('messege',"image extension must be jpg or png");
            }
               
        }
        Profile::create([
            "firstname"=>$request->firstname,
            "lastname"=>$request->lastname,
            "age"=>$request->age,
            "email"=>$request->email,
            "password"=>$request->password,
            "image"=>$filename
        ]);
        return redirect('/');
            
    }

    /**
     * Display the specified resource.
     */
    public function adminprofile($id)
    {
        $profile=Profile::find($id);
        return view('admin.profile',compact('profile'));
    }
    public function userprofile($id)
    {
        $profile=Profile::find($id);
        return view('user.profile',compact('profile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function admineditprofile($id,Request $request)
    {
        
        if($request->firstname=="" || $request->lastname=="" || $request->age=="" || $request->email=="" || $request->password=="" ){
            return redirect()->route('adminupdateprofile',$id)->with('wmessege',"enter all data");
        }
        if($request->age<18){
            //$messege=
            return redirect()->route('adminupdateprofile',$id)->with('wmessege',"age must be bigger than 18");
        }
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('adminupdateprofile',$id)->with('wmessege',"email format invalid");
        }
        if(strlen($request->password)<8){
            return redirect()->route('adminupdateprofile',$id)->with('wmessege',"password must be more than 8 characters");
        }
        $filename="";
        if($request->hasFile('image')){
            $file=$request->file('image');
            $extension=$file->getClientOriginalExtension();
            if($extension=="jpg" || $extension=="png"){
                $filename=time() . '.' . $extension;
            $file->move(public_path('photos'),$filename);
            }else{
                return redirect()->route('adminupdateprofile',$id)->with('wmessege',"image extension must be jpg or png");
            }
               
        }
        $profile=Profile::find($id);
        if($filename==""){
            $profile->update([
            "firstname"=>$request->firstname,
            "lastname"=>$request->lastname,
            "age"=>$request->age,
            "email"=>$request->email,
            "password"=>$request->password
        ]);
        }else{
            $profile->update([
                "firstname"=>$request->firstname,
                "lastname"=>$request->lastname,
                "age"=>$request->age,
                "email"=>$request->email,
                "password"=>$request->password,
                "image"=>$filename
        ]);
        }
        
        return redirect()->route('adminprofile',$id)->with('wmessege',"updated successfully");
    }

    public function usereditprofile($id,Request $request)
    {
        
        if($request->firstname=="" || $request->lastname=="" || $request->age=="" || $request->email=="" || $request->password=="" ){
            return redirect()->route('userupdateprofile',$id)->with('wmessege',"enter all data");
        }
        if($request->age<18){
            //$messege=
            return redirect()->route('userupdateprofile',$id)->with('wmessege',"age must be bigger than 18");
        }
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('userupdateprofile',$id)->with('wmessege',"email format invalid");
        }
        if(strlen($request->password)<8){
            return redirect()->route('userupdateprofile',$id)->with('wmessege',"password must be more than 8 characters");
        }
        $filename="";
        if($request->hasFile('image')){
            $file=$request->file('image');
            $extension=$file->getClientOriginalExtension();
            if($extension=="jpg" || $extension=="png"){
                $filename=time() . '.' . $extension;
            $file->move(public_path('photos'),$filename);
            }else{
                return redirect()->route('userupdateprofile',$id)->with('wmessege',"image extension must be jpg or png");
            }
               
        }
        $profile=Profile::find($id);
        if($filename==""){
            $profile->update([
            "firstname"=>$request->firstname,
            "lastname"=>$request->lastname,
            "age"=>$request->age,
            "email"=>$request->email,
            "password"=>$request->password
        ]);
        }else{
            $profile->update([
                "firstname"=>$request->firstname,
                "lastname"=>$request->lastname,
                "age"=>$request->age,
                "email"=>$request->email,
                "password"=>$request->password,
                "image"=>$filename
        ]);
        }
        
        return redirect()->route('userprofile',$id)->with('wmessege',"updated successfully");
    }
    /**
     * Update the specified resource in storage.
     */
    public function adminupdateprofile($id)
    {
        $profile=Profile::find($id);
        return view('admin.editprofile',compact('profile'));
    }
    
    public function userupdateprofile($id)
    {
        $profile=Profile::find($id);
        return view('user.editprofile',compact('profile'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        //
    }
    public function loginform(){
        return view('auth.login');
    }
    public function logout(Request $request){
        $request->session()->forget('userid');
        return redirect('/');
    } 
}
