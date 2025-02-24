<?php

namespace App\Models\Reference;

use App\Models\Lego\Fields\CreatedFieldTrait;
use App\Models\Lego\Fields\UpdatedNullableFieldTrait;
use App\Models\ORM\ORM;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class CurrencyRate extends ORM
{
    use AsSource;
    use Filterable;

    use CreatedFieldTrait;
    use UpdatedNullableFieldTrait;

    protected $table = 'currency_rate';
    protected $fillable = array(
        'currency_id',
        'rate',
    );

    public $timestamps = false;

    protected array $allowedSorts = [
        'id',
        'currency_id',
        'rate',
    ];

    public function getCurrency(): Currency
    {
        return Currency::loadByOrDie($this->currency_id);
    }

    public function setCurrencyID(int $value): void
    {
        $this->currency_id = $value;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $value): void
    {
        $this->rate = $value;
    }
}
