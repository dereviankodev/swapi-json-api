<?php

namespace App\Services\Telegram\Commands;

class VehicleCommand extends BaseCommand
{
    protected static $aliases = ['/vehicles'];
    protected static $description = 'Get Vehicle list';
}