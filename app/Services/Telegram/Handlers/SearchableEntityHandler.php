<?php

namespace App\Services\Telegram\Handlers;

use App\Services\Telegram\Repositories\BaseEntityRepository;
use App\Services\Telegram\Repositories\EntityRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

class SearchableEntityHandler extends UpdateHandler
{
    private static Collection $message;

    public static function trigger(Update $update, TeleBot $bot): bool
    {
        if (!isset($update->message) || !isset($update->message->text)) {
            return false;
        }

        static::$message = Str::of($update->message->text)->trim()->lower()->explode(' ', 2);
        $searchableAttributes = collect(BaseEntityRepository::SEARCHABLE_ATTRIBUTES);

        return $searchableAttributes->has(static::$message->first());
    }

    public function handle()
    {
        $command = '/'.static::$message->first().'?filter[field]='.static::$message->last();
        $entity = new EntityRepository($command);
        $text = $entity->getText();
        $inlineKeyboard = $entity->getInlineKeyboard();

        if (Str::contains($text, 'Total: 0,')) {
            $this->sendSticker([
                'sticker' => __('telebot.stickers.not_found')
            ]);
        } else {
            $this->sendSticker([
                'sticker' => __('telebot.stickers.found')
            ]);
        }

        $this->sendMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'inline_keyboard' => $inlineKeyboard
            ]
        ]);
    }
}