<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Database\Factories\EmailVerifyCodeFactory;

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

    /**
     * Set factory.
     *
     * @return EmailVerifyCodeFactory
     */
    public static function factory(): EmailVerifyCodeFactory
    {
        return new EmailVerifyCodeFactory();
    }
}
