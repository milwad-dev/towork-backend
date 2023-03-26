<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerifyCode extends Model
{
    use HasFactory;

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

    public static function factory(): ResetCodePasswordFactory
    {
        return new ResetCodePasswordFactory();
    }
}
