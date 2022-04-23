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
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('cattle_locations.longitude', 'like', '%'.$search.'%')
                ->orWhere('cattle_locations.latitude', 'like', '%'.$search.'%')
                ->orWhere('cattle_locations.green_zone', 'like', '%'.$search.'%')
                ->orWhere('cattle_locations.purple_zone', 'like', '%'.$search.'%')
                ->orWhere('cattle_locations.red_zone', 'like', '%'.$search.'%')
                ->orWhere('cattle_locations.orange_zone', 'like', '%'.$search.'%')
                ->orWhere('cattle_locations.grazing', 'like', '%'.$search.'%')
                ->orWhere('cattle_locations.standing', 'like', '%'.$search.'%')
                ->orWhere('cattle_locations.sitting', 'like', '%'.$search.'%');
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

}
