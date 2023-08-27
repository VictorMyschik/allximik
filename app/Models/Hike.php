<?php

namespace App\Models;

use App\Models\Lego\Fields\CreatedFieldTrait;
use App\Models\Lego\Fields\DeletedNullableFieldTrait;
use App\Models\Lego\Fields\DescriptionNullableFieldTrait;
use App\Models\Lego\Fields\NameFieldTrait;
use App\Models\Lego\Fields\UpdatedNullableFieldTrait;
use App\Models\ORM\ORM;

class Hike extends ORM
{
  use NameFieldTrait;
  use DescriptionNullableFieldTrait;
  use CreatedFieldTrait;
  use UpdatedNullableFieldTrait;
  use DeletedNullableFieldTrait;

  protected $table = 'hike';

  protected $fillable = [
    'name',
    'description',
    'status',
    'user_id',
    'country_id',
    'hike_type_id',
    'created_at',
    'updated_at',
    'deleted_at',
  ];

  const STATUS_DRAFT = 0;
  const STATUS_ACTIVE = 1;
  const STATUS_CLOSED = 2;

  public static function getStatusList(): array
  {
    return [
      self::STATUS_DRAFT  => 'Черновик',
      self::STATUS_ACTIVE => 'Активный',
      self::STATUS_CLOSED => 'Закрытый',
    ];
  }

  public function getStatus(): int
  {
    return $this->status;
  }

  public function getStatusName(): string
  {
    return self::getStatusList()[$this->getStatus()];
  }

  public function getStatusColor(): string
  {
    switch ($this->getStatus()) {
      case self::STATUS_ACTIVE:
        return 'success';
      case self::STATUS_CLOSED:
        return 'danger';
      default:
        return 'secondary'; //self::STATUS_DRAFT
    }
  }

  public function setStatus(int $value): void
  {
    $this->status = $value;
  }

  public function getUser(): User
  {
    return User::findOrFail($this->user_id);
  }

  public function setUserID(int $value): void
  {
    $this->user_id = $value;
  }
}
