<?php

namespace App\Services\Telegram\Commands;

class SpeciesCommand extends BaseCommand
{
    protected static $aliases = ['/species'];
    protected static $description = 'Get Species list';
}