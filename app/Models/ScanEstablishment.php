<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanEstablishment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'establishment_id'
    ];

    protected $hidden = [
        'establishment_id',
        'user_id'
    ];

    /**
     * Get the establishment that owns the ScanEstablishment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function establishment()
    {
        return $this->belongsTo(Establishment::class, 'establishment_id');
    }
}
