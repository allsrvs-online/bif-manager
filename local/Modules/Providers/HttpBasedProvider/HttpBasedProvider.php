<?php

namespace Local\Modules\Providers\HttpBasedProvider;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Local\Models\Entity\Entity;
use Psr\Log\LoggerInterface;

class HttpBasedProvider
{
    const ONE_PATH = '';
    const MANY_PATH = '';

    protected LoggerInterface $logger;
    protected Client $client;

    public function __construct(LoggerInterface $logger, Client $client)
    {
        $this->logger = $logger;
        $this->client = $client;
    }

    protected function getOneEntity(Request $request): ?Entity
    {
        try {
            $response = $this->client->send($request);
        } catch (GuzzleException $e) {
            $this->logger->error("Failed to fetch entity", ['error' => $e->getMessage()]);
            return null;
        }
        if($response->getStatusCode() !== 200) {
            $this->logger->error("Failed to fetch entity", ['error' => 'Invalid status code']);
            return null;
        }
        $result = json_decode($response->getBody()->getContents(), true);
        $this->logger->debug("Fetched entity", ['entity' => $result]);
        return new Entity(self::ONE_PATH == '' ? $result : $result[self::ONE_PATH]);
    }

    protected function getManyEntities(Request $request): array
    {
        try {
            $response = $this->client->send($request);
        } catch (GuzzleException $e) {
            $this->logger->error("Failed to fetch entities", ['error' => $e->getMessage()]);
            return [];
        }
        if($response->getStatusCode() !== 200) {
            $this->logger->error("Failed to fetch entities", ['error' => 'Invalid status code']);
            return [];
        }
        $result = json_decode($response->getBody()->getContents(), true);
        $this->logger->debug("Fetched entities", ['entities' => $result]);
        $entities = self::MANY_PATH == '' ? $result : $result[self::MANY_PATH];
        if($entities === null) {
            $this->logger->error("Failed to fetch entities", ['error' => 'Invalid JSON']);
            return [];
        }
        return array_map(fn($entity) => new Entity($entity), $entities);
    }

}