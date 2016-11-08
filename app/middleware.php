<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

use Cartalyst\Sentinel\Native\Facades\Sentinel;

$app->add(function ($request, $response, $next) {
    $this->view->offsetSet('flash', $this->flash);
    return $next($request, $response);
});

/**
 * Function which check the user authentication on limited-access pages
 * Return an error if the use is not connected
 */
function checkAuthentication($request, $response, $next) {
    if(Sentinel::forceCheck()) {
        return $next($request, $response);
    } else {
        return $response->withRedirect('/need/authentication');
    }
};

/**
 * Function which check if the user is logged 
 * Return an error if the user is connected
 */ 
function checkNoAuthentication($request, $response, $next) {
    if(!Sentinel::forceCheck()) {
        return $next($request, $response);    
    } else { 
        return $response->withRedirect('need/logout');  
    }
    
    return $next($request, $response); 
}; 