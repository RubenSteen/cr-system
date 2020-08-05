<?php

namespace Tests\Feature\RouteTests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * @test
     */
    public function every_route_is_registered_in_the_protectedRoutesProvider_method()
    {
        $routeCollection = $this->getLaravelRoutes();

        $jsonRoutes = $this->getJsonRoutes();

        foreach ($routeCollection as $method => $routes) {
            foreach ($routes as $url => $data) {
                $ignoredRoutesArrayByMethod = $this->search($jsonRoutes['ignoredRoutes'], 'method', $method);

                $ignoredRoutesArrayByMethodAndUrl = $this->search($ignoredRoutesArrayByMethod, 'url', $url);

                if (count($ignoredRoutesArrayByMethodAndUrl) === 1) {
                    continue;
                } elseif (count($ignoredRoutesArrayByMethodAndUrl) > 1) {
                    throw new \Exception("Route URL '$url' / Method '$method' - has too many registered in the ignoredRoutes");
                }

                $routesArrayByMethod = $this->search($jsonRoutes['routes'], 'method', $method);

                $routesArrayByMethodAndUrl = $this->search($routesArrayByMethod, 'url', $url);

                if (count($routesArrayByMethodAndUrl) === 0) {
                    throw new \Exception("Route URL '$url' / Method '$method' - does not exist");
                } elseif (count($routesArrayByMethodAndUrl) > 1) {
                    throw new \Exception("Route URL '$url' / Method '$method' - has too many registered in the routes");
                }

                $this->assertCount(1, $routesArrayByMethodAndUrl);
            }
        }
    }

    /**
     * @test
     * @dataProvider protectedRoutesProvider
     */
    public function guests_are_prevented_by_unauthenticated_exception($method, $url, $authenticated = true, $status = null)
    {
        $this->assertGuest();

        if ($authenticated === true) {
            $this->$method($url)->assertRedirect('login');
        } else {
            $this->$method($url)->assertStatus($status ?: 200);
        }
    }

    public function protectedRoutesProvider()
    {
        return $this->getJsonRoutes()['routes'];
    }

    private function getJsonRoutes()
    {
        return json_decode(file_get_contents(__DIR__.'/Routes.json'), true);
    }

    private function getLaravelRoutes()
    {
        $seenRoutes = [];

        foreach (\Route::getRoutes() as $routeCollection) {
            foreach ($routeCollection->methods as $method) {
                if ($method === 'HEAD') {
                    continue;
                }

                // Create methods collection
                if (! array_key_exists($method, $seenRoutes)) {
                    $seenRoutes[$method] = [];
                }

                if (isset($seenRoutes[$method][$routeCollection->uri])) {
                    throw new \Exception("Route URL '$routeCollection->uri' already exists in the method array '$method'");
                }

                $seenRoutes[$method][$routeCollection->uri] = $routeCollection->action;
            }
        }

        return $seenRoutes;
    }

    private function search($array, $key, $value)
    {
        $results = [];

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, $this->search($subarray, $key, $value));
            }
        }

        return $results;
    }
}
