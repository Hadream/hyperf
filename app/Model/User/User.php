<?php

declare (strict_types=1);

namespace App\Model\User;

use App\Model\BaseModel;

/**
 * 用户模型
 * Class User
 * @package App\Model
 */
class User extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mobile', 'username', 'avatar', 'password', 'created_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
}
