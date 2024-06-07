<?php

declare(strict_types=1);

namespace App\Api\Coze;

use App\Api\AbstractApi;
use App\DTO\HttpResponseDTO;
use Illuminate\Http\Client\ConnectionException;

final class CozeApi extends AbstractApi
{
    protected string $url = 'https://api.coze.com/open_api/v2/';

    public function post(string $method, array $data): HttpCozeResponse
    {
        $this->headers([
            'Authorization' => 'Bearer ' . config('coze.coze_token')
        ]);

        $data['user'] = config('coze.coze_user');
        $data['bot_id'] = config('coze.coze_bot');

        try {
            $cozeResponse = $this->postRequest($method, $data);
        } catch (ConnectionException $e) {
            return new HttpCozeResponse(new HttpResponseDTO($e->getCode(), ''));
        }

        return new HttpCozeResponse(new HttpResponseDTO(
            $cozeResponse->status(),
            $cozeResponse->body()
        ));
    }
}