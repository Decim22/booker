<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\Type;
class Note extends Model
{
	protected $fillable = ['type_id', 'category_id', 'amount', 'note_date', 'comment'];
    public function category() {
    	return $this->belongsTo(Category::class);
    }
    public function type() {
    	return $this->belongsTo(Type::class);
    }
}
