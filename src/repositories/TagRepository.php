<?php

namespace Sifouneaissaio\HasTags\repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Sifouneaissaio\HasTags\models\Tag;
use Sifouneaissaio\HasTags\traits\HasTagsExtrasTrait;

class TagRepository  {

    use HasTagsExtrasTrait;
    
    /**
     * save
     *
     * @param  mixed $user
     * @param  mixed $data
     *
     * @return bool
     */
    public function save(array $data): ? Model {
        
        $model = new Tag();

        $this->save_columns($data,$model);

        return  $model ? $this->get($model->id) : null;
    }

    /**
     * edit
     *
     * @param  mixed $data
     * @param  mixed $id
     *
     * @return bool
     */
    public function edit(array $data,int $id): ?Model {

        $model = $this->get($id);

        $this->save_columns($data,$model,false);

        return  $model ? $this->get($model->id) : null;
    }


      /**
   * list of all 
   *
   * @return Collection
   */
  public function list(): Collection{

    return $this->model()->get();

  }


    /**
     * get
     *
     * @param  mixed $id
     *
     * @return Model
     */
    public function get(int $id): ? Model{

        return $this->model()->where('id',$id)->get()->first();
    }



    public function delete(int $id): bool{

        $model = $this->get($id);

        return !is_null($model) ? $model->delete() : false ;


    }
  
  


    public function listPaginate(bool $is_trashed,int $perpage): ?LengthAwarePaginator
    {
   
        return null;
   
    }





    public function model(){
        return Tag::with([]);
    }

    private function save_columns(&$model,$data,$create=true){

        if($create)
        $model = Tag::create($data);
        else
        $model->update($data);

        return $model;
    }
}
