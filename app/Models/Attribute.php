<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = ['name', 'type', 'unit'];
    
    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attrid')->select('attrid', 'value')->distinct();
    }
    

}
