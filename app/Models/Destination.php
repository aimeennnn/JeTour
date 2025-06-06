<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'fasility',
        'latitude',
        'longitude',
        'image',
        'content',
        'category_id',
    ];


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function excurtion(): HasMany
    {
        return $this->hasMany(Excurtion::class, 'destination_id', 'id');
    }

    public function culinary(): HasMany
    {
        return $this->hasMany(Culinary::class, 'culinary_id','id');
    }
    public function ticket(): HasMany
    {
        return $this->hasMany(Ticket::class, 'destination_id','id');
    }
}
