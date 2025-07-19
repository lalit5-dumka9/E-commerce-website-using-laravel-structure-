<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Str;
use App\Traits\imageHandles;
class SubadminController extends Controller
{
    use imageHandles;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::get();
        return view('admin/subadmin/add',['roles'=>$roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $req->validate([
            'name'=> 'required|min:2|max:100',
            'email'=> 'required|email',
            'image'=> 'required|image',
            'password'=> 'required|min:6|max:12',
            'role'=> 'required',
        ]);
        $img = $this->singleImageHadle($req->file('image'),$req->name,'images/subadmin/');
        $result = Admin::create([
            'name'=>$req->name,
            'email'=>$req->email,
            'password'=>bcrypt($req->password),
            'image'=>$img,
            'role'=>$req->role,
            'access'=>'bla bla',
            'extra'=>'ffffff',
            'status'=>1,
            'verificationtoken'=>Str::random(12)
        ]);

        if ($result) {
            return redirect('admin/subadmin/add')->with('success','Sub-admin Created Successfully');
        } else {
            return redirect()->back()->with('err','Ohh! Sub-admin couldn\'t be created');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subadmin  $subadmin
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $subadmins = Admin::where('role','!=',0)->with('getRoleDetails')->get();

        return view('admin/subadmin/show',['subadmins'=>$subadmins]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subadmin  $subadmin
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = Role::get();
        $subadmin = Admin::find($id);

        if (!$subadmin) {
           return redirect('/404')->with('err','Ohh! Sub-admin couldn\'t be found'); 
        } else {
            return view('admin/subadmin/edit',['subadmin'=>$subadmin,'roles'=>$roles]);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subadmin  $subadmin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req)
    {
        $req->validate([
            'name'=> 'min:2|max:100',
            'email'=> 'email',
            'image'=> 'image',
            'password'=> 'max:12',
            'role'=> 'required',
        ]);


        $subadmin = Admin::find($req->id);

        $filename = '';

        if ($req->hasFile('image')) {

            $img = $this->singleImageHadle($req->file('image'),$req->name,'images/subadmin/');
            unlink($subadmin->image);
            $filename = $img;
        } else {
             $filename = $subadmin->image;
        }

        if ($req->password=='') {
            $subadmin->name = $req->name;
            $subadmin->email = $req->email;
            $subadmin->image = $filename;
            $subadmin->role = $req->role;
        } else {
            $subadmin->name = $req->name;
            $subadmin->email = $req->email;
            $subadmin->password = bcrypt($req->password);
            $subadmin->image = $filename;
            $subadmin->role = $req->role;
        }

        $result = $subadmin->save();

        if ($result) {
            return redirect('admin/subadmin/show')->with('success','Sub-admin Is Updated!');
        } else {
            return redirect()->back()->with('err','OOPS! Sub-admin is not updated!');
        }
        

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subadmin  $subadmin
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $getsubadmin = Admin::find($id);

        $result = $getsubadmin->delete();

        if ($result) {
            return redirect('admin/subadmin/show')->with('success','Sub-admin Is Deleted!');
        } else {
            return redirect('/404')->with('err','Ohh! Sub-admin couldn\'t be found'); 
        }
    }
}
