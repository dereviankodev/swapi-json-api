<?php

namespace App\Services\Telegram\Commands;

use App\Services\Telegram\Repositories\PeopleRepository;
use Exception;
use Illuminate\Support\Str;
use WeStacks\TeleBot\Handlers\CommandHandler;

class PeopleCommand extends CommandHandler
{
    private const HANDLER_CLASS_SUFFIX = 'Command';

    protected static $aliases = ['/people'];
    protected static $description = 'Send "/people" to get all the people';
    private static ?string $entityName;

    /**
     * @throws Exception
     */
    public function handle()
    {
        static::$entityName = Str::of(class_basename(static::class))
            ->before(static::HANDLER_CLASS_SUFFIX)
            ->plural()
            ->lower();
        $entity = new PeopleRepository(static::$entityName);
        $request = $entity->getRequest();
        $description = $entity->getDescription(static::$entityName);
        $meta = $entity->getMeta();

        $this->sendMessage([
            'text' => $description,
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'inline_keyboard' => $request
            ]
        ]);

//        if (is_null($meta)) {
//
//        } else {
//            $this->sendMessage([
//                'text' => $meta,
//                'parse_mode' => 'HTML',
//            ]);
//        }
    }
}