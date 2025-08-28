<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    protected $fillable = [
        'title',
        'description',
        'long_description',
        ];
//    protected $guarded = ['
//    '];
    public  function scopeFilterData($query,$filter,$paginate_number)
    {
        return match ($filter) {
            'completed' => $query->latest()->where('completed', '=', true)->paginate($paginate_number),
            'pending' => $query->latest()->where('completed', '=', false)->paginate($paginate_number),
            'oldest' => $query->oldest()->paginate($paginate_number),
            default => $query->latest()->paginate($paginate_number),
        };
    }

}
