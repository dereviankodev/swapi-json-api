<?php

namespace App\Services\Telegram\Handlers;

use App\Services\Telegram\Repositories\BaseEntityRepository;
use App\Services\Telegram\Repositories\EntityRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

class SearchableEntityHandler extends UpdateHandler
{
    public const SEARCHABLE_URI_START_WITH = 'search';

    public static function trigger(Update $update, TeleBot $bot): bool
    {
        if (!isset($update->callback_query) || !isset($update->callback_query->data)) {
            return false;
        }

        return Str::of($update->callback_query->data)->trim('/')
            ->startsWith(static::SEARCHABLE_URI_START_WITH);
    }

    public function handle()
    {
        $entity = Str::of($this->update->callback_query->data)->remove(static::SEARCHABLE_URI_START_WITH)->trim('/');
        $text = 'Enter to search a '
            .implode(' or ', BaseEntityRepository::SEARCHABLE_ATTRIBUTES[(string) $entity])
            .' from the list of '.$entity;
        $this->sendMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'force_reply' => true,
                'input_field_placeholder' => $text
            ]
        ]);
    }
}