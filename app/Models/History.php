<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = ['orders', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getIncome($data) {
        $price = [];
        foreach($data as $hisData) {
            $price[] = array_reduce(array_map(fn ($el) => $el->amount * $el->price, json_decode($hisData->orders)), fn ($a, $b) => $a + $b);
        };
        return array_reduce($price, fn ($a, $b) => $a + $b);
    }
}
