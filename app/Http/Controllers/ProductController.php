<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brands;
use App\Models\Product_size;
use Illuminate\Http\Request;
use App\Traits\imageHandles;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    use imageHandles;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('status','=',1)->get();

        return view('/admin/product/add',['categories'=>$categories]);
    }

    public function getBrands($id)
    {
        $brands = Brands::where('category','=',$id)->get();

        return $brands; 
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
         // dd($req);
        $x = $req->validate([
            'title'=>'required|min:2',
            'category'=>'required',
            'description'=>'required',
            'features'=>'required',
            'price'=>'required',
            'discount'=>'required',
            'quantity'=>'required',
            'imageThumb'=>'required|image',
            'image'=>'required',
        ]);

        //colors global variable to hold the color values
        $colors = '';

        //this is to get the json decoded product images
        $productImages = $this->MultipleImageHadle($req->file('image'),$req->title,'/images/products/');
        $productThumb = $this->singleImageHadle($req->file('imageThumb'),$req->title,'/images/thumbnails/');

        #creating a product unique id to make a foreign key so that it can be sorted out easily
        $productUid = Str::random(6).date('YMd').date('his'); 

        //checking for the color and sizes inputs to make them available in both product and size table
        if ($req->colorName !='') {
            
            $colors = json_encode($req->colorName);

            //now we have to store the sizes based on color to a different table

            foreach ($req->colorName as $color) {
                $clrx = $color.'Price';
                 // dd($req);
                        // var_dump('bbbbb');
                for ($i=0; $i < 5; $i++) { 
                    if($req->$clrx[$i]!=0){
                        // var_dump($req->$clrx[$i]);
                        $cc = $req->file($color.'File');
                        // var_dump($cc[$i]);

                       //  $cc = $req->files($color.'File');
                       //  // dd($cc);
                        $sizeImage = $this->singleImageHadle($cc[$i],$color,'/images/productSizes/')[$i];
                         // var_dump('--------------------------------------');
                         // var_dump(gettype($sizeImage));
                         
                        Product_size::create([
                            'size'=>$req->$color[$i],
                            'image'=>$sizeImage,
                            'product'=>$productUid,
                            'color'=>$color,
                            'price'=>$req->$clrx[$i],
                       ]);
                    }
                    // if ($req->coloredSizesPrice[$i]!=0) {
                    //     dd($req->file('coloredSizesFile'));
                    //     $sizeImage = $this->singleImageHadle($req->file('coloredSizesFile')[$i],$req->colorName,'/images/productSizes/');
                    //     dd($sizeImage);
                    //    Product_size::create([
                    //         'size'=>$req->coloredSizes[$i],
                    //         'image'=>$sizeImage,
                    //         'product'=>$productUid,
                    //         'color'=>$color,
                    //         'price'=>$req->coloredSizesPrice[$i],
                    //    ]);
                    // }
                }
            }
        } else {
            $colors = null;
        }

        dd($colors);
        

        // $result = Product::create([
        //     'title',
        //     'category',
        //     'brand',
        //     'cupon',
        //     'details',
        //     'features',
        //     'Price',
        //     'discount',
        //     'quantity',
        //     'thumbnail',
        //     'images',
        //     'slug',
        //     'extra',
        //     'status',
        // ]);

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
