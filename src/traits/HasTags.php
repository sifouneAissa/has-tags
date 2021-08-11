<?php

namespace Sifouneaissaio\HasTags\traits;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Sifouneaissaio\HasTags\facades\TagRepositoryFacade;
use Sifouneaissaio\HasTags\models\Tag;

trait HasTags {

    // use RepositoriesExtrasTrait;
    
    
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * attach a tag to this model 
     * 
     */

    public function attachTag($tag){
        if(is_integer($tag)) $tag = TagRepositoryFacade::get($tag); 
        if(!is_object($tag)) return null;
        $res = $this->tags()->save($tag);
        return $res ? $this : null;
    }


    public function detachTag($tag) {

        if(is_integer($tag)) $tag = TagRepositoryFacade::get($tag);
        
        if(!is_object($tag)) return null;

        $res  = $this->tags()->detach($tag);
        
        return $res ? $this : null;

    }

    public function attachTags($tags){
        foreach ($tags as $tag) {
            $res = $this->attachTag($tag);
            if(!$res) break; 
        }

        return $this->load('tags');
     }

    public function detachTags($tags){

        foreach ($tags as $tag) {
            $res = $this->detachTag($tag);
            if(!$res) break;
        }

        return $this->load('tags');
    }

    public static function searchByTag($tags,String $like=null,array $with=[],$model=null,$paginate=12){
        $wherekey = 'name';
        
        if(!$model) $model = self::model($model);

        $res = $model->whereHas('tags',function ($q) use ($tags,$wherekey,$like){
            if($tags){
            $tags = collect($tags)->map(function ($tag) use ($wherekey){
                if(is_array($tag)) return $tag[$wherekey];
                if(is_object($tag)) return $tag->$wherekey;
                return $tag;
            });

            $q->whereIn($wherekey,$tags);
            
            }
            if($like)
            $q->where(DB::raw('concat(" ",'.$wherekey.'," ")'), 'like', '%'.$like.'%');
        
        })->with($with)->paginate($paginate);

        return $res;
    }


    public function editTag($data,$id){
        return TagRepositoryFacade::edit($data,$id);
    }


    public function deleteTag($id){
        return TagRepositoryFacade::delete($id);
    }


    public function storeTag($data){
        $data['type'] = static::class;
        // save the new tag
        $tag = TagRepositoryFacade::save($data);
        // attach this new tag with this model
        $tag = $this->attachTag($tag);
        
        return $tag;
    }


    public function storeTags($data){
        // try to save all the tags if exception return null 
        try {
            foreach ($data as $tag) {
                $res  = $this->storeTag($tag);
                if(!$res) break;
            }
        } catch (Exception $e) {
            return null;
        }
        $model = $this->load('tags');
        
        return $model;
    }


    public function editTags($data){
        try{
            foreach($data as $tag){
                $res = $this->editTag($tag,$tag['id']);
                if(!$res) break;
            }
        }catch(Exception $e){
            return null;
        }

        $model = $this->load('tags');

        return $model;
    }


    public function deleteTags($data){
        try{
            foreach($data as $tag){
                $res = $this->deleteTag($tag);
                if(!$res) break;
            }
        }catch(Exception $e){
            return null;
        }

        $model = $this->load('tags');

        return $model;
    }

    
    public function linkTags($data){
        $res= $this;
        if(Arr::has($data,'tags')){
            $tags = $data['tags'];
            $res = $this->storeTags($tags);
        }

        return $res;
    }

}