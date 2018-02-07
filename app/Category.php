<?php

namespace App;

use App\Product;
use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	use SoftDeletes;

    public $transformer = CategoryTransformer::class;

    protected $fillable = [
    	'name',
    	'description'
    ];

    protected $hidden = [
    	'pivot'
    ];

    protected $dates = ['deleted_at'];

    public function products(){
    	return $this->belongsToMany(Product::class);
    }
}
