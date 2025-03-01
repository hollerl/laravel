<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

}
