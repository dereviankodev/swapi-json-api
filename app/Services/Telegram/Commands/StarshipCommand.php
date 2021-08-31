<?php

namespace App\Services\Telegram\Commands;

class StarshipCommand extends BaseCommand
{
    protected static $aliases = ['/starships'];
    protected static $description = 'Get Starship list';
}