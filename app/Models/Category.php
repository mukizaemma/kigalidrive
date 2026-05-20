<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name','slug','description','image','status'
    ];

    public function hotels(){
       return $this->hasMany(Hotel::class);
    }

    public function trips(){
       return $this->hasMany(Trip::class);
    }

    public function properties()
    {
       return $this->hasMany(Property::class);
    }
}
