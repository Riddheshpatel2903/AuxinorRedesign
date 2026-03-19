<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'product_category',
        'product_name',
        'quantity',
        'message',
        'status',
        'source',
        'admin_notes',
        'replied_at'
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];
}
