<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'type',
        'order_id',
        'gross_amount',
        'payment_type',
        'transaction_id',
        'transaction_time',
        'transaction_status',
        'snap_token',
        'ticket_id',
        'quantity',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }
}