<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Type
 * @package App\Models
 */
class Type extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var string[]
     */
    protected $fillable = ["name"];

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function journalist(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Journalist::class);
    }
}
