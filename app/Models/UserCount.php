<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCount extends Model
{
    use HasFactory;

    protected $table = 'UserCount'; // Corrected table name
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedtime'; // Updated to use the correct column name for last update timestamp

    protected $fillable = [
        'company_id',
        'company_name',
        'msp',
        'license_issue_date',
        'user_number',
        'createdAt',
        'updatedtime',
        'need_attention',
        'need_reboot',
        'not_compatible',
        'disconnected_devices',
        
        // Add other columns as needed
    ];

    // Add any relationships or additional methods if required
}
    