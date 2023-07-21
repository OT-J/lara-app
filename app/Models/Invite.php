<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'company_id',
        'employee_name',
        'state'
    ];
    public function admin()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
