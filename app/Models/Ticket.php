<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ticket',
        'price',
        'rules',
        'open',
        'close',
        'type',
        'status',

        'destination_id',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id', 'id');
    }
}
