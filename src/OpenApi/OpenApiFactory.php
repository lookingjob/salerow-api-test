<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Model;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;

/**
 * OpenApiFactory class.
 */
final class OpenApiFactory implements OpenApiFactoryInterface
{
    /**
     * Constructor.
     *
     * @param OpenApiFactoryInterface $decorated
     */
    public function __construct(
        private OpenApiFactoryInterface $decorated
    ) {
    }

    /**
     * @param array $context
     * @return OpenApi
     */
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        $paths = $openApi->getPaths()->getPaths();

        $filteredPaths = new Model\Paths();
        foreach ($paths as $path => $pathItem) {
            if ($path === '/api/users/{id}') {
                continue;
            }
            $filteredPaths->addPath($path, $pathItem);
        }

        return $openApi->withPaths($filteredPaths);
    }
}
