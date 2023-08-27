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
    'hike_type_id',
    'created_at',
    'updated_at',
    'deleted_at',
  ];

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
    return match ($this->getStatus()) {
      self::STATUS_ACTIVE => 'success',
      self::STATUS_CLOSED => 'danger',
      default => 'secondary',
    };
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
}
