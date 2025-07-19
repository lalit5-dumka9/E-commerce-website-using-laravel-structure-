<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::get();
        $permissions = Permission::get();

        return view('admin/role/add',['roles'=>$roles,'permissions'=>$permissions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name'=> 'required|min:2|max:20',
            'role'=> 'required'
        ]);

        $result = Role::create([
            'name'=>$req->name,
            'role'=>$req->role,
            'permissions'=> json_encode($req->access),
            'status'=>1,
        ]);

        if ($result) {
            return redirect('admin/role/add')->with('success','Role Created Successfully');
        } else {
            return redirect()->back()->with('err','Ohh! Role couldn\'t be created');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $roles = Role::get();

        return view('admin/role/show',['roles'=>$roles]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($role)
    {
        $role = Role::find($role);
        $roles = Role::get();
        $permissions = Permission::get();

        if ($role) {
            return view('admin/role/edit',['srole'=>$role,'roles'=>$roles,'permissions'=>$permissions]);
        } else {
            return redirect('/admin/404')->with('err','OOPPS! No Matching resource Found!');
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req)
    {
        $role = Role::find($req->id);
        $role->name = $req->name;
        $role->permissions = json_encode($req->access);
        $role->role = $req->role;   

        $result = $role->save();

        if ($result) {
            return redirect('admin/role/show')->with('success','Role Is Updated!');
        } else {
            return redirect()->back()->with('err','OOPS! Role is not updated!');
        }
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($role)
    {
        $getrole = Role::find($role);

        $result = $getrole->delete();

        if ($result) {
            return redirect('admin/role/show')->with('success','Role Is Deleted!');
        } else {
            return redirect()->back()->with('err','OOPS! Role is not Deleted!');
        }
    }
}
