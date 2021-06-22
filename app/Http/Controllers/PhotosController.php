<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photos;
use App\Models\Photo_topics;
use Illuminate\Support\Facades\Validator;

class PhotosController extends Controller
{
    //
    public function show(Request $request){
        $photos = Photos::get()
        ->map(function($data){
            $data->url = $this->urlPath($data->parent_path,$data->path);
            return $data;
        });

        if($photos==null||$photos->count()<1){
            return response()->json([
                'res'=> false,
                'code'=> '400',
                'status'=>'Bad Request',
                'message' => 'No data'
            ],400);
        }
        return response()->json([
            'res'=> true,
            'code'=> '200',
            'status'=>'Ok',
            'data' => $photos
        ],200);
    }


    public function showByTopic(Request $request, $topic){
               
        $photos = $this->getByTopicAndSize($request->width,$request->height,$topic);
        
        if($photos==null||$photos->count()<1){
            $photos = $this->getByTopic($topic);
        }
       
        if($photos==null||$photos->count()<1){
            return response()->json([
                'res'=> false,
                'code'=> '400',
                'status'=>'Bad Request',
                'message' => 'No data'
            ],400);
        }
        return response()->json([
            'res'=> true,
            'code'=> '200',
            'status'=>'Ok',
            'data' => $photos
        ],200);
    }

    public function showRandom(Request $request){

        $validator = Validator::make($request->all(), [
            'lim' => 'required|integer|between:1,50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'res'=>false,
                'code'=> '400',
                'status'=>'Bad Request',
                'message'=>'Set a limit (1-50). Use lim parameter'
            ]);
        }

       
        $photos = Photos::inRandomOrder()
        ->limit($request->lim)
        ->get()
        ->map(function($data){
            $data->url = $this->urlPath($data->parent_path,$data->path);
            return $data;
        });
       // return $photos;
        if($photos==null || $photos->count()<1){
            return response()->json([
                'res'=>false,
                'code'=> '400',
                'status'=>'Bad Request',
                'message'=> 'No data'
            ]);
        }
        return response()->json([
            'res'=>true,
            'code'=> '200',
            'status'=>'Ok',
            'message'=> $photos
        ]);
    }

    private function getByTopic($topic){
        $photos =  Photo_topics::join('photos','photo_topics.photo_id','=','photos.id')
        ->join('topics','photo_topics.topic_id','=','topics.id')
        ->select('photos.*')
        ->where('topics.topic',$topic)
        ->get()
        ->map(function($data){
            $data->url = $this->urlPath($data->parent_path,$data->path);
            return $data;
        });
        return $photos;
    }

    private function getByTopicAndSize($width,$height,$topic){
        $data = null;

        if($width !=null && $height==null){
            $data =  Photo_topics::join('photos','photo_topics.photo_id','=','photos.id')
            ->join('topics','photo_topics.topic_id','=','topics.id')
            ->select('photos.*')
            ->where('topics.topic',$topic)
            ->where('photos.width',$width)
            ->get()
            ->map(function($data){
                $data->url = $this->urlPath($data->parent_path,$data->path);
                return $data;
            });
        }

        if($width ==null && $height!=null){
            $data =  Photo_topics::join('photos','photo_topics.photo_id','=','photos.id')
            ->join('topics','photo_topics.topic_id','=','topics.id')
            ->select('photos.*')
            ->where('topics.topic',$topic)
            ->where('photos.height',$height)
            ->get()
            ->map(function($data){
                $data->url = $this->urlPath($data->parent_path,$data->path);
                return $data;
            });
        }

        if($width!=null && $height!=null){
            $data =  Photo_topics::join('photos','photo_topics.photo_id','=','photos.id')
            ->join('topics','photo_topics.topic_id','=','topics.id')
            ->select('photos.*')
            ->where('topics.topic',$topic)
            ->where('photos.width',$width)
            ->where('photos.height',$height)
            ->get()
            ->map(function($data){
                $data->url = $this->urlPath($data->parent_path,$data->path);
                return $data;
            });
        }
       
        
        return $data;
    }

    private function urlPath($parent_path,$path){
        $url = '';
        if($parent_path=='local'){
            $url = 'https://local-ip.com/'.$path;
        }

        if($parent_path=='cloudinary'){
            $url = 'https://cloudinary.com/'.$path;
        }

        return $url;

    }

}
