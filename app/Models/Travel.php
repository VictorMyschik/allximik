<?php

namespace App\Models;

use App\Models\Lego\Fields\CreatedFieldTrait;
use App\Models\Lego\Fields\DeletedNullableFieldTrait;
use App\Models\Lego\Fields\DescriptionNullableFieldTrait;
use App\Models\Lego\Fields\NameFieldTrait;
use App\Models\Lego\Fields\UpdatedNullableFieldTrait;
use App\Models\ORM\ORM;
use App\Models\Reference\Country;
use Illuminate\Support\Facades\Cache;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Travel extends ORM
{
  use AsSource;
  use Filterable;

  use NameFieldTrait;
  use DescriptionNullableFieldTrait;
  use CreatedFieldTrait;
  use UpdatedNullableFieldTrait;
  use DeletedNullableFieldTrait;

  protected $table = 'travel';

  protected array $allowedSorts = [
    'name',
    'description',
    'status',
    'user_id',
    'country_id',
    'public',
    'travel_type_id',
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
    'travel_type_id',
    'visible_kind',
    'public_id',
    'created_at',
    'updated_at',
    'deleted_at',
  ];

  const VISIBLE_KIND_PUBLIC = 2; // публичный
  const VISIBLE_KIND_FOR_ME = 0; // только для меня
  const VISIBLE_KIND_PLATFORM = 1; // только для зарегистрированных пользователей

  public static function getVisibleKindList(): array
  {
    return [
      self::VISIBLE_KIND_PUBLIC   => 'Публичный',
      self::VISIBLE_KIND_FOR_ME   => 'Только для меня',
      self::VISIBLE_KIND_PLATFORM => 'Только для зарегистрированных пользователей',
    ];
  }

  public static function getVisibleKindDescription(): array
  {
    return [
      self::VISIBLE_KIND_PUBLIC   => 'Все пользователи могут видеть эту походную программу',
      self::VISIBLE_KIND_FOR_ME   => 'Только вы можете видеть эту походную программу',
      self::VISIBLE_KIND_PLATFORM => 'Только зарегистрированные пользователи могут видеть эту походную программу',
    ];
  }

  const STATUS_DRAFT = -1;
  const STATUS_ACTIVE = 1;
  const STATUS_ARCHIVED = 2;

  public static function getStatusList(): array
  {
    return [
      self::STATUS_DRAFT    => 'Черновик',
      self::STATUS_ACTIVE   => 'Активный',
      self::STATUS_ARCHIVED => 'Архивный',
    ];
  }

  private const STORAGE_PATH = 'files/travel_images';

  #region ORM
  public function canView(?User $me = null): bool
  {
    if (self::VISIBLE_KIND_PUBLIC === $this->getVisibleKind()) {
      return true;
    }

    if ($me) {
      if ($me->id() === $this->user_id || self::VISIBLE_KIND_PLATFORM === $this->getVisibleKind()) {
        return true;
      }
    }

    return false;
  }

  public function canEdit(?User $me = null): bool
  {
    if (!$this->canView($me)) {
      return false;
    }

    // Authorised user only
    if (!$me) {
      return false;
    }

    if ($me->id() !== $this->user_id) {
      return false;
    }

    return true;
  }

  public function afterSave(): void
  {
    if (!$this->getPublicId()) {
      $this->setPublicId(crc32($this->id()));
      $this->save_mr();
    }
  }

  public function flush(): void
  {
    Cache::forget('travel_image_main_' . $this->id());
    Cache::forget('travel_image_list_' . $this->id());
    Cache::forget('travel_image_full_list_' . $this->id());
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

  public function getVisibleKind(): int
  {
    return $this->visible_kind;
  }

  public function getVisibleKindName(): string
  {
    return self::getVisibleKindList()[$this->getVisibleKind()];
  }

  public function setVisibleKind(int $value): void
  {
    if (!array_key_exists($value, self::getVisibleKindList())) {
      throw new \InvalidArgumentException('Invalid visible kind');
    }

    $this->visible_kind = $value;
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

  public function getTravelType(): TravelType
  {
    return TravelType::findOrFail($this->travel_type_id);
  }

  public function setTravelTypeID(int $value): void
  {
    $this->travel_type_id = $value;
  }

  public function getPublicId(): ?string
  {
    return $this->public_id;
  }

  public function setPublicId(?string $value): void
  {
    $this->public_id = $value;
  }

  public function getDirNameForImages(): string
  {
    return self::STORAGE_PATH;
  }

  public function getFullImagesList(): array
  {
    return Cache::rememberForever('travel_image_full_list_' . $this->id(), function () {
      return TravelImage::where('travel_id', $this->id())->get()->all();
    });
  }

  public function getMainImage(): ?string
  {
    return Cache::rememberForever('travel_image_main_' . $this->id(), function () {
      return TravelImage::where('travel_id', $this->id())->where('kind', TravelImage::KIND_MAIN)->value('name');
    });
  }

  public function getImagesList(): array
  {
    return Cache::rememberForever('travel_image_list_' . $this->id(), function () {
      return TravelImage::where('travel_id', $this->id())->where('kind', TravelImage::KIND_LIST)->get()->all();
    });
  }
}
