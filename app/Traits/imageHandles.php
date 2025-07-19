<?php 

namespace App\Traits;
use Illuminate\Support\Str;

trait imageHandles{
    public function singleImageHadle($req,$name,$path)
    {
         $imgname = Str::slug($name,'_').date('Y-M-d').date('h-i-s').'.'.$req->getClientOriginalExtension();

        $image = $req->move(public_path().'/'.$path,$imgname);
        $imgpath = $path.$imgname;
        if($image){
        var_dump($imgpath);
            return $imgpath;
        }else{
            return false;
        }
    }


    public function MultipleImageHadle($req,$name,$path)
    {

        foreach ($req as $image) {

            $imgname = Str::slug($name,'_').Str::random(18).date('Y-M-d').date('h-i-s').'.'.$image->getClientOriginalExtension();

            $imagemove = $image->move(public_path().'/'.$path,$imgname);
            $imgpath = $path.$imgname;

            $filenames[] = $imgpath;

        }

        if (sizeof($filenames)!=0) {
          return json_encode($filenames);
        } else {
          return false;
        }
    }

}