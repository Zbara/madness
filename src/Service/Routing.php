<?php

namespace App\Service;

use Symfony\Component\Routing\Router;

class Routing
{
    private Router $router;
    private string $_route;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function setRouteName(string $routeName): void
    {
        $this->_route = $routeName;
    }

    public function getRuterOptions(): bool|array
    {
        foreach ($this->router->getRouteCollection() as $key => $item) {
            if ($key == $this->_route) {
                $options = $item->getOptions();

                foreach ($options as $i => $option) {
                    if (gettype($i) == 'string') {
                        unset($options[$i]);
                    }
                }
                return $options;
            }
        }
        return false;
    }
}
