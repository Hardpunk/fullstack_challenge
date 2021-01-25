<?php

namespace App\Http\Traits;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Http;

trait ConsumesExternalApi
{
    /**
     * Send a request to any service
     * @return json
     */
    private function makeRequest($method, $requestUrl, $formParams = [], $queryParams = [])
    {
        try {
            $client = new Client([
                'verify' => false,
                /* 'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ], */
            ]);

            $requestUrl = 'http://localhost:8088/api' . $requestUrl;

            /* if (method_exists($this, 'resolveAuthorization')) {
            $this->resolveAuthorization($queryParams, $formParams);
            } */

            $request = $client->$method($requestUrl, [
                'query' => $queryParams,
                'json' => $formParams,
            ]);

            $response = json_decode($request->getBody()->getContents());
            $statusCode = $request->getstatusCode();
            $headers = $request->getHeaders();
        } catch (ClientException $ex) {
            $response = json_decode($ex->getResponse()->getBody()->getContents());
            $statusCode = $ex->getResponse()->getStatusCode();
            $headers = $ex->getResponse()->getHeaders();
        } finally {
            return response()->json($response, $statusCode, $headers);
        }
    }

    public function get($url, $queryParams = [])
    {
        return $this->makeRequest('GET', $url, $queryParams);
    }

    public function post($url, $formParams, $queryParams = [])
    {
        return $this->makeRequest('POST', $url, $formParams, $queryParams);
    }

    public function put($url, $formParams, $queryParams = [])
    {
        return $this->makeRequest('PUT', $url, $formParams, $queryParams);
    }

    public function patch($url, $formParams, $queryParams = [])
    {
        return $this->makeRequest('PATCH', $url, $formParams, $queryParams);
    }

    public function delete($url, $formParams, $queryParams = [])
    {
        return $this->makeRequest('DELETE', $url, $formParams, $queryParams);
    }
}
