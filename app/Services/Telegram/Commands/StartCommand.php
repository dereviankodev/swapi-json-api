<?php

namespace App\Services\Telegram\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;

class StartCommand extends CommandHandler
{
    protected static $aliases = ['/start'];
    protected static $description = 'Greetings';

    public function handle()
    {
        $this->sendMessage([
            'text' => __('telebot.command.start.text', ['name' => $this->update->user()->first_name]),
            'reply_markup' => [
                'keyboard' => $this->getKeyboard(),
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
                'input_field_placeholder' => __('telebot.command.start.input_field_placeholder'),
            ]
        ]);

        $this->sendSticker([
            'sticker' => 'CAACAgIAAxkBAAIFRWEwxDiEIIUuq5dbRrBrIXG54ErmAAL1AgACnNbnCgM_eoMQLg5vIAQ'
        ]);
    }

    private function getKeyboard(): array
    {
        return [
            [
                ['text' => __('telebot.command.start.keyboard.people')],
                ['text' => __('telebot.command.start.keyboard.films')],
            ],
            [
                ['text' => __('telebot.command.start.keyboard.planets')],
                ['text' => __('telebot.command.start.keyboard.species')],
            ],
            [
                ['text' => __('telebot.command.start.keyboard.starships')],
                ['text' => __('telebot.command.start.keyboard.vehicles')],
            ],
        ];
    }
}