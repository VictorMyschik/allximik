<?php

namespace App\Models;

use App\Models\Lego\Fields\CreatedFieldTrait;
use App\Models\Lego\Fields\DeletedNullableFieldTrait;
use App\Models\Lego\Fields\DescriptionNullableFieldTrait;
use App\Models\Lego\Fields\NameFieldTrait;
use App\Models\Lego\Fields\UpdatedNullableFieldTrait;
use App\Models\ORM\ORM;
use App\Models\Reference\Country;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Hike extends ORM
{
  use AsSource;
  use Filterable;

  use NameFieldTrait;
  use DescriptionNullableFieldTrait;
  use CreatedFieldTrait;
  use UpdatedNullableFieldTrait;
  use DeletedNullableFieldTrait;

  protected $table = 'hike';

  protected array $allowedSorts = [
    'name',
    'description',
    'status',
    'user_id',
    'country_id',
    'public',
    'hike_type_id',
    'public_id',
    'created_at',
    'updated_at',
    'deleted_at',
  ];

  protected $fillable = [
    'name',
    'description',
    'status',
    'user_id',
    'public_id',
    'country_id',
    'hike_type_id',
    'public',
    'public_id',
    'created_at',
    'updated_at',
    'deleted_at',
  ];

  const PUBLIC_YES = 1; // публичный
  const PUBLIC_FOR_ME = 0; // только для меня
  const PUBLIC_PLATFORM = 2; // только для зарегистрированных пользователей

  public static function getPublicList(): array
  {
    return [
      self::PUBLIC_YES      => 'Публичный',
      self::PUBLIC_FOR_ME   => 'Только для меня',
      self::PUBLIC_PLATFORM => 'Только для зарегистрированных пользователей',
    ];
  }

  public static function getPublicDescription(): array
  {
    return [
      self::PUBLIC_YES      => 'Все пользователи могут видеть эту походную программу',
      self::PUBLIC_FOR_ME   => 'Только вы можете видеть эту походную программу',
      self::PUBLIC_PLATFORM => 'Только зарегистрированные пользователи могут видеть эту походную программу',
    ];
  }

  const STATUS_DRAFT = -1;
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

  #region ORM
  public function afterSave(): void
  {
    if (!$this->getPublicId()) {
      $this->setPublicId(crc32($this->id()));
      $this->save_mr();
    }
  }

  #endregion
  public function getStatus(): int
  {
    return $this->status;
  }

  public function getStatusName(): string
  {
    return self::getStatusList()[$this->getStatus()];
  }

  public function setStatus(int $value): void
  {
    $this->status = $value;
  }

  public function getPublic(): int
  {
    return $this->public;
  }

  public function setPublic(int $value): void
  {
    $this->public = $value;
  }

  public function getPublicName(): string
  {
    return self::getPublicList()[$this->getPublic()];
  }

  public function getUser(): User
  {
    return User::findOrFail($this->user_id);
  }

  public function setUserID(int $value): void
  {
    $this->user_id = $value;
  }

  public function getCountry(): Country
  {
    return Country::findOrFail($this->country_id);
  }

  public function setCountryID(int $value): void
  {
    $this->country_id = $value;
  }

  public function getHikeType(): HikeType
  {
    return HikeType::findOrFail($this->hike_type_id);
  }

  public function setHikeTypeID(int $value): void
  {
    $this->hike_type_id = $value;
  }

  public function getPublicId(): ?string
  {
    return $this->public_id;
  }

  public function setPublicId(?string $value): void
  {
    $this->public_id = $value;
  }
}
