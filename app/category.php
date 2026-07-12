<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class category extends Model
{
    protected $table="category";
    public $timestamps=false;

    protected $fillable = ['category_name', 'slug', 'category_image'];

    public static function boot()
    {
        parent::boot();

        static::retrieved(function ($category) {
            // This runs when model is fetched
            // Example: auto-generate slug if missing
            if (!$category->slug) {
                $category->slug = Str::slug($category->category_name);
            }
        });

        static::creating(function ($category) {
            // Only set slug if empty
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->category_name);
            }
        });

        static::updating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->category_name);
            }
        });
    }

    public function products()
    {
        return $this->hasMany('App\product', 'category', 'id');
    }
    public function getSlugAttribute()
    {
        return $this->attributes['slug'] ?? Str::slug($this->category_name);
    }

    public function subcategories()
    {
        return $this->hasMany(subcategory::class, 'category', 'id');
    }
}
