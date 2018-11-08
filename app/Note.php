<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
class Note extends Model
{
	protected $fillable = ['type', 'category_id', 'amount', 'comment', 'created_at'];
    public function category() {
    	return $this->belongsTo(Category::class);
    }
}
