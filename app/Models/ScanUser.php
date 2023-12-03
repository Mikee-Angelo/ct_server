<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'scanned_user_id',
    ]; 
}
