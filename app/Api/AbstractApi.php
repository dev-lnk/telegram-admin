<?php

namespace App\Api;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class AbstractApi
{
    protected string $url = '';

    protected array $headers = [];

    protected array $options = [];

    /**
     * @throws ConnectionException
     */
    protected function postRequest(string $method, array $data): Response
    {
        $url = $this->url . $method;

        return Http::timeout(1800)
            ->when(
                $this->headers,
                fn($http) => $http->withHeaders($this->headers)
            )
            ->when(
                $this->options,
                fn($http) => $http->withOptions($this->options)
            )
            ->post($url, $data);
    }


    /**
     * @throws ConnectionException
     */
    protected function getRequest(string $method, array $data = []): Response
    {
        $url = $this->url . $method;

        return Http::timeout(1800)
            ->when(
                $this->headers,
                fn($http) => $http->withHeaders($this->headers)
            )
            ->when(
                $this->options,
                fn($http) => $http->withOptions($this->options)
            )
            ->timeout(3600)
            ->get($url, $data);
    }

    public function headers(array $headers): static
    {
        $this->headers = $headers;

        return $this;
    }

    public function options(array $headers): static
    {
        $this->options = $headers;

        return $this;
    }
}