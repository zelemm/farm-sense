<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cattle extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = 'cattles';

    protected $casts = [
        'images' => 'array'
    ];

    public function farm() {
        return $this->belongsTo(Farm::class);
    }

    public function mother() {
        return $this->hasOne(Cattle::class, 'id', 'mother_cow');
    }

    public function father() {
        return $this->hasOne(Cattle::class, 'id', 'father_cow');
    }

    public function alerts() {
        return $this->hasMany(CattleAlert::class);
    }

    public function locations() {
        return $this->hasMany(CattleLocation::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('cattles.name', 'like', '%'.$search.'%')
                ->orWhere('cattles.notes', 'like', '%'.$search.'%')
                ->orWhere('cattles.mac_id', 'like', '%'.$search.'%')
                ->orWhere('cattles.arrival', 'like', '%'.$search.'%')
                ->orWhere('cattles.date_of_birth', 'like', '%'.$search.'%')
                ->orWhere('cattles.date_of_death', 'like', '%'.$search.'%')
                ->orWhere('cattles.date_of_sell', 'like', '%'.$search.'%')
                ->orWhere('cattles.breed', 'like', '%'.$search.'%')
                ->orWhere('cattles.sex', 'like', '%'.$search.'%')
                ->orWhere('cattles.casterated', 'like', '%'.$search.'%')
                ->orWhere('cattles.vendor', 'like', '%'.$search.'%')
                ->orWhere('cattles.cattle_type', 'like', '%'.$search.'%');
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('cattles.status', $status);
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

}
