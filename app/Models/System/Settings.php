<?php

namespace App\Models\System;

use App\Models\Lego\Fields\ActiveFieldTrait;
use App\Models\Lego\Fields\CreatedFieldTrait;
use App\Models\Lego\Fields\DescriptionNullableFieldTrait;
use App\Models\Lego\Fields\NameFieldTrait;
use App\Models\Lego\Fields\UpdatedNullableFieldTrait;
use App\Models\ORM\ORM;
use Illuminate\Support\Facades\Cache;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Settings extends ORM
{
  use AsSource;
  use Filterable;

  use ActiveFieldTrait;
  use NameFieldTrait;
  use DescriptionNullableFieldTrait;
  use CreatedFieldTrait;
  use UpdatedNullableFieldTrait;

  protected $table = 'settings';

  #region ORM
  public function afterSave(): void
  {
    $this->flush();
  }

  public function afterDelete(): void
  {
    $this->flush();
  }

  private function flush(): void
  {
    Cache::forget('setup_full_list');
  }

  #endregion

  public function getCategory(): ?string
  {
    return $this->category;
  }

  public function setCategory(?string $value): void
  {
    $this->category = $value;
  }

  public function getCodeKey(): string
  {
    return $this->code_key;
  }

  public function setCodeKey(string $value): void
  {
    $this->code_key = $value;
  }

  public function getValue(): ?string
  {
    return $this->value;
  }

  public function setValue(?string $value): void
  {
    $this->value = $value;
  }

  /**
   * Return list of settings with full attributes. Key is 'code_key' field. Cached.
   */
  public static function getSettingList(): array
  {
    return Cache::rememberForever('setup_full_list', function (): array {
      $out = [];
      foreach (self::all() as $item) {
        $out[$item->code_key] = $item->getAttributes();
      }

      return $out;
    });
  }

  /**
   * Return active setting VALUE. If Setting is not active - return null. Cached.
   */
  public static function getSetting(string $key): ?string
  {
    if (!array_key_exists($key, self::getSettingList())) {
      return null;
    }

    if (!self::getSettingList()[$key]['active']) {
      return null;
    }

    return self::getSettingList()[$key]['value'];
  }

  public static function loadAdminEmailToNotify(): ?string
  {
    return self::getSetting('admin_email');
  }

  public static function loadMaxFileSize(): string
  {
    return self::getSetting('max_upload_file_size');
  }
}
