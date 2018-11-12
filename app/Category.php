<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Note;
use App\Type;

class Category extends Model
{
	protected $fillable = ['name', 'type_id'];
		public function type() {
			return $this->belongsTo(Type::class);
		}
    public function notes() {
    	return $this->hasMany(Note::class);
    }
}
