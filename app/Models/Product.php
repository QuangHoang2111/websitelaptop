<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'costprice',
        'regularprice',
        'saleprice',
        'stocks',
        'categoryid',
        'brandid',
        'isfeatured',
        'shortdescription',
        'description',
        'image',
        'images',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryid'); 
    }

   public function getFinalPriceAttribute()
    {
    return ($this->saleprice && $this->saleprice > 0)
        ? $this->saleprice
        : $this->regularprice;
    }

    public function getIsOnSaleAttribute()
    {
    return $this->saleprice && $this->saleprice > 0;
    }

    public function attributeValues()
    {
    return $this->hasMany(AttributeValue::class, 'productid');
    }

      public function brand()
    {
        return $this->belongsTo(Brand::class, 'brandid');
    }

}
