<?php

namespace App\PowerAll;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

class Client
{
    protected ?GuzzleClient $client = null;

    protected string $apiVersion = 'v1';

    protected string $username;

    protected string $password;

    protected array $config;

    protected string $baseUrl = 'https://webshop.powerall.io/api';

    /**
     * @param string $username
     * @param string $password
     * @param array $config
     */
    public function __construct(string $username, string $password, array $config = [])
    {
        $this->username = $username;
        $this->password = $password;

        if(isset($config['api_version'])) {
            $this->apiVersion = $config['api_version'];
        }

        $this->config = $config;
    }


    /**
     * @param array $productCodes
     * @param string $customerId
     * @param int|null $orderType
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function products(array $productCodes = [], string $customerId = '', ?int $orderType = null): ResponseInterface
    {
        $args = [
            'query' => [
                'customerId' => $customerId,
                'orderType' => $orderType,
                'productCodes' => implode(',', $productCodes)
            ]
        ];

        if(!$productCodes) {
            return $this->client()->get($this->createUrl('/products/all'), $args);
        }
        return $this->client()->get('/products', $args);
    }

    /**
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function productGroups(): ResponseInterface
    {
        return $this->client()->get($this->createUrl('/productgroups'));
    }

    /**
     * @param string $path
     * @return string
     */
    private function createUrl(string $path): string
    {
        return $this->baseUrl.'/'.$this->apiVersion.$path;
    }

    /**
     * @return GuzzleClient
     */
    public function client(): GuzzleClient
    {
        if(!$this->client) {
            $this->client = new GuzzleClient(['auth' => [
                $this->username, $this->password
            ]]);
        }
        return $this->client;
    }
}