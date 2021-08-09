<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class News
 * @package App\Models
 */
class News extends Model
{
    /**
     * Mapping the table on database.
     * @var string
     */
    protected $table = 'news';

    /**
     * The attributes that are mass assignable.
     * @var string[]
     */
    protected $fillable = ["type_id", "title", "description", "body", "img_link"];

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [""];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Type::class);
    }
}
