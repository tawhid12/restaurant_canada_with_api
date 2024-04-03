<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FestivalReg extends Model
{
    use HasFactory;
    protected $fillable = ['fullName','email', 'mobile', 'ticket_number'];
}
