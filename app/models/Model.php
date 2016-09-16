<?php namespace app\models;

use core\DB;

class Model extends DB
{
    protected $connection;
    protected $table;
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $perPage = 15;

    // Todo: implement relationships function
    // belongsTo
    // hasOne
    // hasMany
    // belongsToMany
    // hasManyThrough
}
