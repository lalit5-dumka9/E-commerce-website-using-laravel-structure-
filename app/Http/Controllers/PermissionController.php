<?php

namespace App\Http\Controllers;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    //

    public function index()
    {
        return view('admin/permissions/add');
    }


    public function store(Request $req)
    {
        $permission_type = ['show','add','update','delete'];
        $req->validate([
            'name'=>'required|min:3|max:20'
        ]);

        $check = Permission::where('extra',$req->name)->get();

        if (!$check->isEmpty()) {
            return redirect('admin/permission/add')->with('err','Permission already exists');
        } else {
            
            for ($i=0; $i < 4; $i++) { 
                $result = Permission::create([
                'permission'=>Str::lower($req->name).'-'.$permission_type[$i],
                'status'=> 1,
                'extra'=> $req->name,
            ]);
            }

            if ($result) {
                return redirect('admin/permission/add')->with('success','Permission Created Successfully');
            } else {
                return redirect()->back()->with('err','Ohh! Permission couldn\'t be created');
            }
        }

        
        
    }


    public function show()
    {
        $permissions = Permission::get();
        return view('admin/permissions/show',['permissions'=>$permissions]);
    }

    public function destroy($id)
    {
        $getpermission = Permission::find($id);

        $result = $getpermission->delete();

        if ($result) {
            return redirect('admin/permission/show')->with('success','Permission is Deleted!');
        } else {
            return redirect()->back()->with('err','OOPS! Permission is not Deleted!');
        }
    }
}
