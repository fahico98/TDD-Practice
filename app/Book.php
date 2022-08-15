<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = [];

    public function setAuthorIdAttribute($author)
    {
        $author = Author::firstOrCreate([ "name" => $author ]);
        $this->attributes["author_id"] = $author->id;
    }
}
