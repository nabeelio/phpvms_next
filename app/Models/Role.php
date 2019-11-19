<?php

namespace App\Models;

use Laratrust\Models\LaratrustRole;

/**
 * @method static where(string $string, $group)
 */
class Role extends LaratrustRole
{
    protected $fillable = [
        'id',
        'name',
        'display_name',
        'read_only',
    ];

    protected $casts = [
        'read_only' => 'boolean',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'display_name' => 'required',
    ];
}
