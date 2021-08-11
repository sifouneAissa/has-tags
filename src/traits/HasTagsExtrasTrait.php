<?php


namespace Sifouneaissaio\HasTags\traits;

use Illuminate\Support\Arr;

trait HasTagsExtrasTrait

{

    protected $one_item_condition = [
        'id'
    ];


    public  function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
    /**
     * getAccordingTo
     *
     * @param  mixed $extra
     * @return void
     */
    public function getAccordingTo($extra = null)
    {
        $res = $this->model();
        $get_first = false;
        if (!$extra)
            return $this->list();

        if (Arr::has($extra, 'where') && $extra['where'])
            $res = $res->where($extra['where']);




        if (Arr::has($extra, 'whereIn') && $extra['whereIn']) {
            $whereIn = $extra['whereIn'];

            if ($this->isAssoc($whereIn)) {
                $key = $whereIn['key'];
                $value = $whereIn['value'];
                $res = $res->whereIn($key, $value);
            } else if (!$this->isAssoc($whereIn)) {
                foreach ($whereIn as $item) {

                    $key = $item['key'];
                    $value = $item['value'];
                    $res = $res->whereIn($key, $value);
                }
            }
        }

        if (Arr::has($extra, 'whereNotIn') && $extra['whereNotIn']) {
            $whereNotIn = $extra['whereNotIn'];
            $key = $whereNotIn['key'];
            $value = $whereNotIn['value'];
            $res = $res->whereNotIn($key, $value);
        }
        if (Arr::has($extra, 'whereHas') && $extra['whereHas']) {
            
            $whereHas = $extra['whereHas'];
            
            if ($this->isAssoc($whereHas))
                $res = $this->whereHasModel($whereHas, $res);
            else
                foreach ($whereHas as $whereH)
                    $res = $this->whereHasModel($whereH, $res);
        }

        if (Arr::has($extra, 'with') && $extra['with']) {
            $with  = $extra['with'];

            $relation = $with['relation'];
            $res = $res->with([
                $relation => function ($q) use ($with) {
                    $whereIn = $with['whereIn'];
                    $where = $with['where'];

                    if ($whereIn) {
                        $key = $whereIn['key'];
                        $q->whereIn($key, $whereIn['value']);
                    }
                    if ($where) {
                        $q->where($where);
                    }
                }
            ]);
        }

        if (Arr::has($extra, 'orWhereNull') && $extra['orWhereNull']) {
            $res = $res->orWhereNull($extra['orWhereNull']);
        }

        if (Arr::has($extra, 'orWhereIn') && $extra['orWhereIn']) {
            $orWhereIn = $extra['orWhereIn'];
            if ($this->isAssoc($orWhereIn)) {
                $key = $orWhereIn['key'];
                $value = $orWhereIn['value'];
                $res = $res->orWhereIn($key, $value);
            } else if (!$this->isAssoc($orWhereIn)) {
                foreach ($orWhereIn as $item) {
                    $key = $item['key'];
                    $value = $item['value'];
                    $res = $res->orWhereIn($key, $value);
                }
            }
        }

        if (Arr::has($extra, 'distinct') && $extra['distinct']) {
            $distinct = $extra['distinct'];
            $keys = $distinct['keys'];
            $res = $res->select($keys)->distinct();
        }


        if (Arr::has($extra, 'builder') && $extra['builder']) {
            return $res;
        }



        if (Arr::has($extra, 'paginate') && $extra['paginate']) {
            return $res->paginate($extra['paginate']);
        }

        // get the first one
        if (Arr::has($extra, 'where') && $extra['where'])
            foreach ($this->one_item_condition as $value)
                foreach ($extra['where'] as $array)
                    if (in_array($value, $array) || Arr::has($extra, 'first') && $extra['first']) $get_first = true;

        if ($get_first)
            return $res->get()->first();



        return $res->get();
    }


    public  function whereHasModel($whereHas, $res)
    {
        $relation = $whereHas['relation'];

        $res = $res->whereHas($relation, function ($q) use ($whereHas) {
            $this->whereHasModelInside($whereHas,$q);    
        });

        return $res;
    }


    public  function whereHasModelInside($whereHas,$q){
        // dd($whereHas);
        $whereIn =key_exists('whereIn',$whereHas) ? $whereHas['whereIn'] : null;
        
        $where = key_exists('where',$whereHas) ? $whereHas['where'] : null;

        $S_whereHas = key_exists('whereHas',$whereHas) ? $whereHas['whereHas'] : null;
        
        if ($whereIn)
            if ($this->isAssoc($whereIn)) {
                $key = $whereIn['key'];
                $q->whereIn($key, $whereIn['value']);
            } else {
                foreach ($whereIn as $value) {
                    $key = $value['key'];
                    $q->whereIn($key, $value['value']);
                }
            }

        if ($where) {
            $q->where($where);
        }
        
        // if ($like) {

        //     $key = $like['key'];
        //     $value = $like['value'];

        //     $q->where(DB::raw('concat(" ",'.$key.'," ")'), 'like', '%'.$value.'%');
        
        // }


        if(!$S_whereHas) return ;
        return $this->whereHasModel($S_whereHas,$q);
    }


    public function getAccourdingToWithModel($model,$extra= null){
        
        $res = $model;

        $get_first = false;
        

        
        if (Arr::has($extra, 'where') && $extra['where'])
            $res = $res->where($extra['where']);

        if (Arr::has($extra, 'whereIn') && $extra['whereIn']) {
            
            $whereIn = $extra['whereIn'];

            if ($this->isAssoc($whereIn)) {
                $key = $whereIn['key'];
                $value = $whereIn['value'];
                $res = $res->whereIn($key, $value);
            } else if (!$this->isAssoc($whereIn)) {
                foreach ($whereIn as $item) {

                    $key = $item['key'];
                    $value = $item['value'];
                    $res = $res->whereIn($key, $value);
                }
            }
        }

        if (Arr::has($extra, 'whereNotIn') && $extra['whereNotIn']) {
            $whereNotIn = $extra['whereNotIn'];
            $key = $whereNotIn['key'];
            $value = $whereNotIn['value'];
            $res = $res->whereNotIn($key, $value);
        }

        if (Arr::has($extra, 'whereHas') && $extra['whereHas']) {
            
            $whereHas = $extra['whereHas'];

            if ($this->isAssoc($whereHas)){
                $res = $this->whereHasModel($whereHas, $res);
            }
            else
                foreach ($whereHas as $whereH)
                    $res = $this->whereHasModel($whereH, $res);
        }

        if (Arr::has($extra, 'with') && $extra['with']) {
            $with  = $extra['with'];

            $relation = key_exists('relation',$with) ? $with['relation'] : null;
            if($relation)
            $res = $res->with([
                $relation => function ($q) use ($with) {
                    $whereIn = $with['whereIn'];
                    $where = $with['where'];

                    if ($whereIn) {
                        $key = $whereIn['key'];
                        $q->whereIn($key, $whereIn['value']);
                    }
                    if ($where) {
                        $q->where($where);
                    }
                }
            ]);
            // if with camme in the original format [ relation1, relation2, relation3 , ... ]
            else if(is_array($with))$res = $res->with($with);
        }

        if (Arr::has($extra, 'orWhereNull') && $extra['orWhereNull']) {
            $res = $res->orWhereNull($extra['orWhereNull']);
        }

        if (Arr::has($extra, 'orWhereIn') && $extra['orWhereIn']) {
            $orWhereIn = $extra['orWhereIn'];
            if ($this->isAssoc($orWhereIn)) {
                $key = $orWhereIn['key'];
                $value = $orWhereIn['value'];
                $res = $res->orWhereIn($key, $value);
            } else if (!$this->isAssoc($orWhereIn)) {
                foreach ($orWhereIn as $item) {
                    $key = $item['key'];
                    $value = $item['value'];
                    $res = $res->orWhereIn($key, $value);
                }
            }
        }

      
        if (Arr::has($extra, 'distinct') && $extra['distinct']) {
            $distinct = $extra['distinct'];
            $keys = $distinct['keys'];
            $res = $res->select($keys)->distinct();
        }

        

        if (Arr::has($extra, 'builder') && $extra['builder']) {
            return $res;
        }


        if (Arr::has($extra, 'paginate') && $extra['paginate']) {
            return $res->paginate($extra['paginate']);
        }



        // get the first one
        if (Arr::has($extra, 'where') && $extra['where'])
            foreach ($this->one_item_condition as $value)
                foreach ($extra['where'] as $array)
                    if (in_array($value, $array) || Arr::has($extra, 'first') && $extra['first']) $get_first = true;

        if ($get_first)
            return $res->get()->first();



        return $res->get();
    }
}
