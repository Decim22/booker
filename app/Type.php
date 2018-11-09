<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Note;
Use App\Category;

class Type extends Model
{
    public function Notes() {
    	return $this->hasMany(Note::class);
    }
    public function Categories() {
    	return $this->hasMany(Category::class);
    }
}
