<?php

namespace App\Service;

use Symfony\Component\Routing\RouterInterface;

class Routing
{
    private string $_route;
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function setRouteName(string $routeName): void
    {
        $this->_route = $routeName;
    }

    public function getRoute(): string
    {
        return $this->_route;
    }

    public function getRuterOptions(): bool|array
    {
        foreach ($this->router->getRouteCollection() as $key => $item) {
            if ($key == $this->_route) {
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
