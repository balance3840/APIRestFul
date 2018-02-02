<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	use SoftDeletes;

    protected $fillable = [
    	'name',
    	'description'
    ];

    protected $dates = ['deleted_at'];

    public function products(){
    	return $this->belongsToMany(Product::class);
    }
}
