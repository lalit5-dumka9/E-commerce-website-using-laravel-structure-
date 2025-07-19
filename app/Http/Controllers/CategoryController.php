<?php
namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\imageHandles;
use Illuminate\Support\Str;
class CategoryController extends Controller
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
    return view('admin/category/add',['categories'=>$categories]);
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
* 
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
public function store(Request $req)
{
    $req->validate([
    'name'=> 'required|min:3|max:20',
    'image' => 'required|image'
    ]);

    $img = $this->singleImageHadle($req->file('image'),$req->name,'images/category/');

    $result = Category::create([
    'name'=>$req->name,
    'image'=>$img,
    'parentId'=>$req->parentId,
    'slug'=>Str::slug($req->name,'-'),
    'extra'=>$req->name,
    'status'=>1,
    ]);

    if ($result) {
        return redirect('admin/category/add')->with('success','Category Created Successfully');
    } else {
        return redirect()->back()->with('err','Ohh! Category couldn\'t be created');
    }
}
/**
* Display the specified resource.
*
* @param  \App\Models\Category  $category
* @return \Illuminate\Http\Response
*/
public function show(Category $category)
{
    $categories = Category::where('status',1)->with('getParentCategory')->get();
    // dd(...$categories);
    return view('admin/category/show',['categories'=>$categories]);
}
/**
* Show the form for editing the specified resource.
*
* @param  \App\Models\Category  $category
* @return \Illuminate\Http\Response
*/
public function edit($id)
{
    $category = Category::where('status',1)->find($id);
    $categories = Category::where('status',1)->with('getParentCategory')->get();
    if ($category) {
        return view('admin/category/edit',['category'=>$category,'categories'=>$categories]);
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

    $category = Category::find($req->id);

    if ($category) {
        
        if ($req->hasFile('image')) {
            $img = $this->singleImageHadle($req->file('image'),$req->name,'images/category');
        } else {
            $img = $category->image;
        }

        $category->name = $req->name;
        $category->image = $img;
        $category->parentId = $req->parentId;
        $category->slug = Str::slug($req->name,'-');

        $result = $category->save();

        if ($result) {
            return redirect('admin/category/show')->with('success','Category Is Updated!');
        } else {
            return redirect()->back()->with('err','OOPS! Category is not Updated!');
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
    $getcategory = Category::find($id);
    Category::where('parentId','=',$getcategory->id)->update(['parentId'=>0]);
    $result = $getcategory->delete();

    if ($result) {
        return redirect('admin/category/show')->with('success','Category Is Deleted!');
    } else {
        return redirect()->back()->with('err','OOPS! Category is not Deleted!');
    }
}
}