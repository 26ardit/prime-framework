<?php namespace core;

class Router
{
    private $routes = [];
    private $data = [];
    
    public function add($url, $method)
    {
        $this->routes[$url] = $method;
    }

    public function dispatch()
    {
        $server_url = ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $server_url = $server_url ? explode('/', $server_url) : [];

        foreach ($this->routes as $url => $action) {
            
            if ($this->urlMatches($server_url, $url)) {
                
                if (is_callable($action)) {
                    return call_user_func_array($action, $this->data);
                }
                
                $action = explode('@', $action);
                $controller = 'app\\controllers\\'.$action[0];
                $method = $action[1];
                
                return call_user_func_array([new $controller, $method], $this->data);
            }
        }

        Redirect::to(404);
    }

    private function urlMatches($server_url, $router_url)
    {
        $router_url = ltrim($router_url, '/');
        $router_url = $router_url ? explode('/', $router_url) : [];

        $router_count = count($router_url);
        $server_count = count($server_url);

        $optional = (strpos(end($router_url), '?') !== false);

        if ($optional) {
            
            if ($server_count == $router_count - 1 || $server_count == $router_count) {

                for ($i=0; $i < $router_count - 1; $i++) {
                    if ($router_url[$i][0] == '{') {
                        $this->data[] = $server_url[$i];
                    } elseif ($server_url[$i] != $router_url[$i]) {
                        return false;
                    }
                }

                if ($server_count == $router_count) {
                    $this->data[] = $server_url[$server_count - 1];
                }

                return true;
            }

            return false;
        } else {

            if ($server_count == $router_count) {
                
                for ($i=0; $i < $server_count; $i++) {
                    if ($router_url[$i][0] == '{') {
                        $this->data[] = $server_url[$i];
                    } elseif ($server_url[$i] != $router_url[$i]) {
                        return false;
                    }
                }

                return true;
            }

            return false;
        }
    }
}
