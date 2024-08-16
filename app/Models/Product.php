<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Atributos que se pueden asignar masivamente
    protected $fillable = ['name', 'description', 'price', 'category_id'];

    // Relación con el modelo Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
