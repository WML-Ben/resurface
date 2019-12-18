<?php

/** v-2017-10-05
 *
 *  Added array key: actionPrefix and corresponding function: getActionPrefix
 *
 *  Added functions:
 *      getRoutes($methods, $filter, $excludedNames)               -> return assoc array: route_name => route_uri.
 *      getRouteUri($routeName, $methods, $filter, $excludedNames) -> return uri (url w/o domain) or false if route_name does not exist.
 */
trait ActionTrait
{
    public function getAction()
    {
        $currentRoute = \Route::getCurrentRoute();

        $previousUrl = \URL::previous();

        if (strpos($previousUrl, asset('/')) === 0) {
            $previousRouteName = app('router')->getRoutes()->match(app('request')->create($previousUrl))->getName();
        } else {
            $previousRouteName = null;
        }

        if ($query = \Request::query()) {
            $arr = [];
            foreach ($query as $key => $value) {
                $arr[] = $key . '=' . $value;
            }
            $queryString = implode('&', $arr);
        } else {
            $queryString = '';
        }

        $uri = explode('\\', $currentRoute->getActionName());
        $action = $uri[count($uri) - 1];
        $parameters = $currentRoute->parameters();

        if (!empty($parameters)) {
            $parameterName = $currentRoute->parameterNames()[0];
            $actionParameterId = (!empty($parameters[$parameterName]->id)) ? $parameters[$parameterName]->id : null;
        } else {
            $actionParameterId = null;
        }
        list($controller, $function) = explode('@', $action);

        return [
            'actionPrefix'      => trim($currentRoute->action['prefix'], '/'),
            'actionController'  => str_replace('controller', '', strtolower($controller)),
            'actionFunction'    => strtolower($function),
            'actionParameterId' => $actionParameterId,
            'actionQuery'       => $query,
            'actionQueryString' => $queryString,
            'routeName'         => $currentRoute->getName(),
            'previousUrl'       => $previousUrl,
            'previousRouteName' => $previousRouteName,
        ];
    }

    public function getActionController()
    {
        $result = $this->getAction();

        return $result['actionController'];
    }

    public function getActionFunction()
    {
        $result = $this->getAction();

        return $result['actionFunction'];
    }

    public function getActionRouteName()
    {
        $result = $this->getAction();

        return $result['routeName'];
    }

    public function getActionPrefix()
    {
        $result = $this->getAction();

        return $result['actionPrefix'];
    }

    public function isAction($controller, $function)
    {
        $action = $this->getAction();

        if (is_array($function)) {
            return ($action['actionController'] == strtolower($controller) && in_array($action['actionFunction'], $function));
        } else {
            return ($action['actionController'] == strtolower($controller) && $action['actionFunction'] == strtolower($function));
        }
    }

    /**
     * @param string $methods : ALL, GET, POST, PATCH, DELETE
     * @param string $filter : null -> all routes, '/' -> no prefix routes other
     * @return array
     */
    public function getRoutes($methods = 'GET', $filter = '/', $excludedNames = ['login', 'logout', 'reset_form', ''])
    {
        $routes = \Route::getRoutes();

        $patterns = [
            '/\{([^\}].*?)\}/',
            '/\/\/+/',
            '/\/$/',
        ];
        $replacements = [
            '',
            '/',
            '',
        ];

        $arr = [];

        foreach ($routes as $route) {
            if (($routeName = $route->getName()) && !in_array($routeName, $excludedNames)) {
                if ((empty($filter)) || ($filter == '/' && empty(trim($route->action['prefix'], '/'))) || (!empty($filter) && $filter != '/' && (strpos($route->uri, $filter) !== false))) {
                    if ($methods == 'ALL' || (count(array_intersect($route->methods, (array)$methods)))) {
                        if ($route->uri == '/') {
                            $arr[$routeName] = $route->uri;
                        } else {
                            $arr[$routeName] = preg_replace($patterns, $replacements, $route->uri);
                        }
                    }
                }
            }
        }

        return $arr;
    }

    public function getRouteUri($routeName, $methods = 'GET', $filter = '/', $excludedNames = ['login', 'logout', 'reset_form', ''])
    {
        $routes = $this->getRoutes($methods, $filter, $excludedNames);

        return $routes[$routeName] ?? false;
    }

}
