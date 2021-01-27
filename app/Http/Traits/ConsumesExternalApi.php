<?php

namespace App\Http\Traits;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use RuntimeException;

trait ConsumesExternalApi
{
    /** @var string $token */
    private $token;

    /** @var string $baseUri */
    private $baseUri = 'http://localhost:8088/api';

    /** @var Client $client */
    private $client;

    /**
     * Initialize method
     *
     * @return void
     */
    private function initialize()
    {
        $this->client = new \GuzzleHttp\Client([
            'verify' => false,
            'allow_redirects' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Send a request to any service
     *
     * @param string $method
     * @param string $requestUrl
     * @param array $formParams
     * @param array $queryParams
     * @return JsonResponse
     */
    private function makeRequest($method, $requestUrl, $formParams = [], $queryParams = [])
    {
        try {
            $this->initialize();

            $requestUrl = $this->baseUri . $requestUrl;

            if (session()->has('token')) {
                $this->setToken(session('token'));
            }

            $options = [
                'query' => $queryParams,
                'json' => $formParams,
            ];

            if (($token = $this->getToken())) {
                $options['headers'] = [
                    'Authorization' => "Bearer {$token}",
                ];
            }

            $response = $this->client->$method($requestUrl, $options);
        } catch (BadResponseException $bad) {
            $response = $bad->getResponse();
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()]);
        } finally {
            return $this->makeResponse($response);
        }
    }

    /**
     * Return JSON response
     *
     * @param mixed $response
     * @return JsonResponse
     */
    private function makeResponse($response)
    {
        $data = json_decode($response->getBody()->getContents());
        $status = $response->getstatusCode();
        $headers = $response->getHeaders();

        return response()->json($data, $status, $headers);
    }

    /**
     * API GET Request
     *
     * @param string $url
     * @param array $queryParams
     * @return JsonResponse
     */
    public function get($url, $queryParams = [])
    {
        return $this->makeRequest('GET', $url, $queryParams);
    }

    /**
     * API POST Request
     *
     * @param string $url
     * @param array $formParams
     * @param array $queryParams
     * @return JsonResponse
     */
    public function post($url, $formParams = [], $queryParams = [])
    {
        return $this->makeRequest('POST', $url, $formParams, $queryParams);
    }

    /**
     * API PUT Request
     *
     * @param string $url
     * @param array $formParams
     * @param array $queryParams
     * @return JsonResponse
     */
    public function put($url, $formParams = [], $queryParams = [])
    {
        return $this->makeRequest('PUT', $url, $formParams, $queryParams);
    }

    /**
     * API PATCH Request
     *
     * @param string $url
     * @param array $formParams
     * @param array $queryParams
     * @return JsonResponse
     */
    public function patch($url, $formParams = [], $queryParams = [])
    {
        return $this->makeRequest('PATCH', $url, $formParams, $queryParams);
    }

    /**
     * API Delete request
     *
     * @param string $url
     * @param array $formParams
     * @param array $queryParams
     * @return JsonResponse
     */
    public function delete($url, $formParams = [], $queryParams = [])
    {
        return $this->makeRequest('DELETE', $url, $formParams, $queryParams);
    }

    /**
     * Set the API authentication token
     *
     * @param mixed $token
     * @return void
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Retrieve token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

}
