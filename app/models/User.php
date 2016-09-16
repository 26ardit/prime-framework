<?php namespace app\models;

class User extends Model
{
    // Default table name is `{model_name} + s`
    // you can change it like:
    // protected $table = 'different_table';

    // dummy
    public function getFirst()
    {
        $user = $this->where(['id', 1])->first();

        return $user;
    }
}
