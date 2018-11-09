<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\Type;
class Note extends Model
{
	protected $fillable = ['type', 'category_id', 'amount', 'comment', 'created_at'];
    public function category() {
    	return $this->belongsTo(Category::class);
    }
    public function type() {
    	return $this->belongsTo(Type::class);
    }
}
