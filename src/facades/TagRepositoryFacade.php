<?php
namespace Sifouneaissaio\HasTags\facades;

use Illuminate\Support\Facades\Facade;

class TagRepositoryFacade extends Facade {



    protected static function getFacadeAccessor()
    {   
        // dd('check');
        // get a instance of service
        return 'TagRepositoryFacade';
    }

}