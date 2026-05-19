<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\BlogCategory;

class Blog extends Model
{
    use HasFactory;
    
    protected $table = 'blogs';
    
    protected $primaryKey = 'id';
    
    protected $fillable = ['category_id', 'title', 'slug', 'content', 'featured_image', 'status', 'published_at', 'created_by', 'meta_title', 'meta_description', 'kaywords'];
    
    public function createdBy () 
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function category ()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
}
