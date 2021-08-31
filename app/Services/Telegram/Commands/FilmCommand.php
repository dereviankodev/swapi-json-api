<?php

namespace App\Services\Telegram\Commands;

class FilmCommand extends BaseCommand
{
    protected static $aliases = ['/films'];
    protected static $description = 'Get Film list';
}