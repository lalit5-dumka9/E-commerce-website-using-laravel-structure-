<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\imageHandles;
use Illuminate\Support\Str;
class BrandsController extends Controller
{
    use imageHandles;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $categories = Category::where('status','!=',false)->get();
        return view('admin/brand/add',['categories'=>$categories]);
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
        // dd($req->category);
        $req->validate([
        'category'=> 'required|integer',
        'name'=> 'required|min:3|max:20',
        'image' => 'required|image'
        ]);



        $img = $this->singleImageHadle($req->file('image'),$req->name,'images/brand/');

        $result = Brands::create([
        'name'=>$req->name,
        'image'=>$img,
        'category'=>$req->category,
        'slug'=>Str::slug($req->name,'-'),
        'extra'=>$req->name,
        'status'=>1,
        ]);

        if ($result) {
            return redirect('admin/brand/add')->with('success','Brand Created Successfully');
        } else {
            return redirect()->back()->with('err','Ohh! Brand couldn\'t be created');
        }
    }

   public function show(Category $category)
    {
        $brands = Brands::where('status',1)->with('getCategory')->get();
        return view('admin/brand/show',['brands'=>$brands]);
    }
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Category  $category
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $categories = Category::where('status',1)->get();
        $brand = Brands::find($id);
        if ($categories && $brand) {
            return view('admin/brand/edit',['categories'=>$categories,'brand'=>$brand]);
        } else {
            return redirect('/admin/404')->with('err','OOPPS! No Matching resource Found!');
        }
    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Category  $category
    * @return \Illuminate\Http\Response
    */
    public function update(Request $req)
    {
        $img = '';

        $brand = Brands::find($req->id);

        if ($brand) {
            
            if ($req->hasFile('image')) {
                $img = $this->singleImageHadle($req->file('image'),$req->name,'images/brand');
            } else {
                $img = $brand->image;
            }

            $brand->name = $req->name;
            $brand->image = $img;
            $brand->category = $req->category;
            $brand->slug = Str::slug($req->name,'-');

            $result = $brand->save();

            if ($result) {
                return redirect('admin/brand/show')->with('success','Brand Is Updated!');
            } else {
                return redirect()->back()->with('err','OOPS! Brand is not Updated!');
            }

        } else {
            return redirect('/admin/404')->with('err','OOPPS! No Matching resource Found!');
        }
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Category  $category
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $getbrand = Brands::find($id);
        $result = $getbrand->delete();

        if ($result) {
            return redirect('admin/brand/show')->with('success','Brand Is Deleted!');
        } else {
            return redirect()->back()->with('err','OOPS! Brand is not Deleted!');
        }
    }
}
