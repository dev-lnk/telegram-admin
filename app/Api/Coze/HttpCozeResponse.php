<?php

declare(strict_types=1);

namespace App\Api\Coze;

use App\DTO\HttpResponseDTO;

final class HttpCozeResponse
{
    public function __construct(protected HttpResponseDTO $responseDTO) {}

    /**
     * @throws CozeApiException
     */
    public function content()
    {
        if($this->responseDTO->code !== 200) {
            throw new CozeApiException('Ошибка обращения к серверу, код ответа: '.$this->responseDTO->code);
        }

        if(is_bool($this->responseDTO->response)) {
            throw new CozeApiException('Ошибка обращения к серверу');
        }

        if(empty($this->responseDTO->response)) {
            throw new CozeApiException('Ошибка обращения к серверу, пустой ответ');
        }

        if(! json_validate($this->responseDTO->response)) {
            throw new CozeApiException('Ошибка обращения к серверу, некорректный ответ ' . $this->responseDTO->response);
        }

        return json_decode($this->responseDTO->response, true);
    }
}