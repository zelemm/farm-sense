<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farm extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'images' => 'array'
    ];

    public function addedBy()
    {
        return User::withTrashed()->find($this->added_by);
    }

    public function users(){
        return $this->hasMany(User::class);
    }

    public function cattles() {
        return $this->hasMany(Cattle::class);
    }

    public function fences() {
        return $this->hasMany(FarmFence::class);
    }

    public function google() {
        return $this->hasMany(FarmGoogle::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('farms.name', 'like', '%'.$search.'%')
                ->orWhere('farms.notes', 'like', '%'.$search.'%')
                ->orWhere('farms.address', 'like', '%'.$search.'%')
                ->orWhere('farms.phone', 'like', '%'.$search.'%');
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('farms.status', $status);
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

}
