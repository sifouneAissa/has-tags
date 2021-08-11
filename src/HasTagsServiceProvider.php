<?php 

namespace Sifouneaissaio\HasTags;

use Illuminate\Support\ServiceProvider;
use Sifouneaissaio\HasTags\repositories\TagRepository;

class HasTagsServiceProvider extends ServiceProvider {



    public function register(){
        
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        app()->bind('TagRepositoryFacade', function(){  //Keep in mind this "check" must be return from facades accessor
            return new TagRepository();
        });
    }



    // public function boot(){

    // }
}