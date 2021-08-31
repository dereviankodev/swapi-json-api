<?php

namespace App\Services\Telegram\Commands;

class PeopleCommand extends BaseCommand
{
    protected static $aliases = ['/people'];
    protected static $description = 'Get People list';
}