<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $table = 'attributevalues'; 

    protected $fillable = [
        'productid',
        'attrid',
        'value'
    ];

    public function attribute()
    {
    return $this->belongsTo(Attribute::class, 'attrid');
    }

}
