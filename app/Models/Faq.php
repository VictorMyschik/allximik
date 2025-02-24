<?php

namespace App\Models;

use App\Models\Lego\Fields\ActiveFieldTrait;
use App\Models\Lego\Fields\CreatedFieldTrait;
use App\Models\ORM\ORM;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Faq extends ORM
{
    use AsSource;
    use Filterable;

    use CreatedFieldTrait;
    use ActiveFieldTrait;

    protected $table = 'faq';
    protected $fillable = array(
        'title',
        'text',
        'active'
    );

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $value): void
    {
        $this->title = $value;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $value): void
    {
        $this->text = $value;
    }
}
