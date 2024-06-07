<?php

namespace App\DTO;

class HttpResponseDTO
{
    public function __construct(
        public int $code,
        public string | bool $response,
        public array $errors = []
    ) {}
}
