<?php

namespace Sifouneaissaio\HasTags\models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    public $timestamps = false;

    protected $columns= [
        'name',
        'type'
    ];
    protected $fillable = [
        'name','type'
    ];

    // public function TaskManagers(){
    //     return $this->belongsToMany(TaskManager::class,'tag_task_manager');
    // }


    public function getColumns(){
        return $this->columns;
    }


    
    public function getDates(){
        return [];
    }


    // morph 
        /**
     * Get all of the posts that are assigned this tag.
     */
    // public function questions()
    // {
    //     return $this->morphedByMany(Question::class, 'tags');
    // }
    

}
