<?php

namespace Local\Modules\Providers\EntityProvider;

use GuzzleHttp\Psr7\Request;
use Local\Models\Entity\Entity;
use Local\Modules\Providers\HttpBasedProvider\HttpBasedProvider;

class EntityProvider extends HttpBasedProvider
{

    public function getOne(Request $request): ?Entity
    {
        return $this->getOneEntity($request);
    }

    public function getMany(Request $request): array
    {
        return $this->getManyEntities($request);
    }
}