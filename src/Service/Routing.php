<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class Routing
{
    public function __construct(
        private RouterInterface $router,
        private RequestStack    $requestStack
    )
    {
    }

    public function getRoute(): string
    {
        return $this->requestStack->getCurrentRequest()->attributes->get('_route');
    }

    public function getRuterOptions(): bool|array
    {
        foreach ($this->router->getRouteCollection() as $key => $item) {
            if ($key == $this->getRoute()) {
                $options = $item->getOptions();

                if (count($options) > 0) {
                    foreach ($options as $i => $option) {
                        if (gettype($i) == 'string') {
                            unset($options[$i]);
                        }
                    }
                    return $options;
                }
            }
        }
        return false;
    }
}
