<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Note;

class Category extends Model
{
	protected $fillable = ['name'];
    public function notes() {
    	return $this->hasMany(Note::class);
    }
}
