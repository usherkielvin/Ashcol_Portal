<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get tickets with this status
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'status_id');
    }

    /**
     * Get the default status
     */
    public static function getDefault()
    {
        return static::where('is_default', true)->first();
    }
}

