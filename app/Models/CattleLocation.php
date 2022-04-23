<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CattleLocation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function cattle() {
        return $this->belongsTo(Cattle::class);
    }
}
