<?php

namespace App\Services\Telegram\Commands;

class PlanetCommand extends BaseCommand
{
    protected static $aliases = ['/planets'];
    protected static $description = 'Get Planet list';
}