<?php

declare(strict_types=1);

namespace App\Services;

use Longman\TelegramBot\Entities\InputMedia\InputMediaPhoto;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

final class TelegramBot
{
    private Telegram $telegram;

    private array $channels = [];

    /**
     * @throws TelegramException
     */
    public function __construct(
        private string $botApiKey,
        private string $botUsername
    ) {
        $this->setBot();
    }

    /**
     * @throws TelegramException
     */
    public function setBot(): void
    {
        $this->telegram = new Telegram($this->botApiKey, $this->botUsername);

        if(! empty($this->channels)) {
            $this->telegram->setCommandConfig('sendtochannel', [
                'your_channel' => $this->channels
            ]);
        }

    }

    /**
     * For webhook
     */
    public function setChannels(array $channels): self
    {
        $this->channels = $channels;

        return $this;
    }

    /**
     * @throws TelegramException
     */
    public function sendToChannel(int|string $channel, string $text, array $images): ServerResponse
    {
        if(! empty($images)) {
            return $this->sendMessageWithPhotos($channel, $text, $images);
        }
        Request::initialize($this->telegram);
        return Request::sendMessage([
            'chat_id' => $channel,
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }

    public function sendMessageWithPhotos(string $channel, string $text, array $images): ServerResponse
    {
        Request::initialize($this->telegram);

        $mediaArray = [];

        foreach ($images as $index => $image) {
            $mediaArray[] = new InputMediaPhoto(
                $index ? ['media' => $image]
                    : ['media' => $image, 'caption' => $text, 'parse_mode' => 'HTML'])
            ;
        }

        return Request::sendMediaGroup([
            'chat_id' => $channel,
            'media' => $mediaArray,
        ]);
    }
}