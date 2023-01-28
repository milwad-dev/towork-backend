<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class ResetCodePassword extends Model
{
    /**
     * Fillable columns.
     *
     * @var string[]
     */
    protected $fillable = ['email', 'code', 'created_at'];

    /**
     * Set timestamps.
     *
     * @var bool
     */
    public $timestamps = false;
}
