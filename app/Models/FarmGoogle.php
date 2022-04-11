<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmGoogle extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function farm() {
        return $this->belongsTo(Farm::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('farm_googles.label', 'like', '%'.$search.'%')
                ->orWhere('farm_googles.organisation_id', 'like', '%'.$search.'%')
                ->orWhere('farm_googles.client_id', 'like', '%'.$search.'%')
                ->orWhere('farm_googles.client_secret', 'like', '%'.$search.'%');
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('farm_googles.status', $status);
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
