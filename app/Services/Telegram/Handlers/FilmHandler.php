<?php

namespace App\Services\Telegram\Handlers;

use App\Services\Telegram\Repositories\FilmRepository;
use Exception;
use Illuminate\Support\Str;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\TeleBot;

class FilmHandler extends UpdateHandler
{
    private const HANDLER_CLASS_SUFFIX = 'Handler';

    private static ?string $entityName;

    public static function trigger(Update $update, TeleBot $bot): bool
    {
        if (!isset($update->callback_query)) {
            return false;
        }

        static::$entityName = Str::of(class_basename(static::class))
            ->before(static::HANDLER_CLASS_SUFFIX)
            ->plural()
            ->lower();

        return Str::of($update->callback_query->data)
            ->startsWith(static::$entityName);
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        $this->deleteMessage();
        $entity = new FilmRepository($this->update->callback_query->data);
        $request = $entity->getRequest();
        $description = $entity->getDescription(static::$entityName);
        $meta = $entity->getMeta();

        $this->sendMessage([
            'text' => $description,
            'reply_markup' => [
                'inline_keyboard' => $request
            ]
        ]);

        if (is_null($meta)) {

        } else {
            $this->sendMessage([
                'text' => $meta,
                'parse_mode' => 'HTML',
            ]);
        }
    }
}