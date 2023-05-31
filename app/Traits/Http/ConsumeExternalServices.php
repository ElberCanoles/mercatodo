<?php
declare(strict_types=1);

namespace App\Traits\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * @property string baseUrl
 */
trait ConsumeExternalServices
{

    /**
     * @throws GuzzleException
     */
    public function makeRequest(
        string $method,
        string $requestUrl,
        array $queryParams = [],
        array $formParams = [],
        array $headers = [],
        bool $isJsonRequest = false
    )
    {
        $client = new Client([
            'base_uri' => $this->baseUrl,
        ]);

        if (method_exists($this, 'resolveAuthorization')) {
            $this->resolveAuthorization($queryParams, $formParams, $headers);
        }

        $response = $client->request($method, $requestUrl, [
            $isJsonRequest ? 'json' : 'form_params' => $formParams,
            'headers' => $headers,
            'query' => $queryParams,
        ]);

        $response = $response->getBody()->getContents();

        if (method_exists($this, 'decodeResponse')) {
            $response = $this->decodeResponse($response);
        }

        return $response;
    }
}

