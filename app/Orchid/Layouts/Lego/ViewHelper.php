<?php

namespace App\Orchid\Layouts\Lego;

use Orchid\Screen\Fields\ViewField;

class ViewHelper
{
    public static function space(): ViewField
    {
        return ViewField::make('')->view('admin.space');
    }
}
