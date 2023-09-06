<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Platform\Models\User as Authenticatable;
use Orchid\Screen\AsSource;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
  use AsSource;
  use Filterable;
  use Notifiable;
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  public function getJWTCustomClaims(): array
  {
    return [];
  }

  public static function getTableName(): string
  {
    return 'users';
  }

  public function id(): int
  {
    return $this->id;
  }

  protected $fillable = [
    'name',
    'email',
    'password',
    'permissions',
  ];

  protected $hidden = [
    'password',
    'remember_token',
    'permissions',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'permissions'       => 'array',
    'email_verified_at' => 'datetime',
  ];

  /**
   * The attributes for which you can use filters in url.
   *
   * @var array
   */
  protected $allowedFilters = [
    'id'         => Where::class,
    'name'       => Like::class,
    'email'      => Like::class,
    'updated_at' => WhereDateStartEnd::class,
    'created_at' => WhereDateStartEnd::class,
  ];

  /**
   * The attributes for which can use sort in url.
   *
   * @var array
   */
  protected $allowedSorts = [
    'id',
    'name',
    'email',
    'updated_at',
    'created_at',
  ];
}
