<?php

namespace App\Http\Controllers;
use App\Models\skinmodel;
use App\Models\User;
use Illuminate\Http\Request;


class skincontroller extends Controller
{
    public function skinmodel(){
        $skinData = skinmodel::all();
        return response()->json($skinData);
        }

    public function addSkin(Request $req,$id){
        $newSkin=new skinmodel;
        $newSkin->date=$req->date;
        $newSkin->user_id = $id;
        $newSkin->bodypart=$req->bodypart;
        $newSkin->since=$req->since;
        $newSkin->symptom=$req->symptom;
        $newSkin->cancertype=$req->cancertype;
        $newSkin->accu=$req->accu;
        $result=$newSkin->save();
        if ('result'){
            return ["result"=>"data has been saved"];
        }
        else{
            return ["result"=>"canceled"];
        }
    }

    public function deleteskin ($id){
        $deleteSkin=skinmodel::find($id);
        $result=$deleteSkin->delete();
        if($result){
            return ["result"=>"record has been deleted"];
        }
        else {
            return ["result"=>"failed"];
        }
    }
}
