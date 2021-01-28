<?php

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

if (!function_exists('generateAvatar')) {
    /**
     * Generate avatar by user name
     *
     * @param string $fullname
     * @return string
     */
    function generateAvatar($fullname)
    {
        $avatarName = urlencode("{$fullname}");
        return "https://ui-avatars.com/api/?name={$avatarName}&background=838383&color=FFFFFF&size=140&rounded=true";
    }
}

if (!function_exists('handleResponse')) {
    /**
     * Handle API Responses
     *
     * @param ResponseJson $response
     * @param string $route
     * @return RedirectResponse|void
     */
    function handleResponse($response, $route = 'index')
    {
        if ($response->getStatusCode() != 200) {
            $data = $response->getData();
            $redirect = redirect(route($route));

            if (isset($data->errors)) {
                $errors = (array) $data->errors;
                $redirect = $redirect->withInput()->withErrors($errors);
            } else {
                Flash::error($data->message);
                $redirect = back()->withInput();
            }
            throw new HttpResponseException($redirect);
        }
    }
}

if (!function_exists('handleResponseData')) {
    /**
     * Handle Response Data
     *
     * @param mixed $data
     * @param string $route
     * @return RedirectResponse|void
     * @throws HttpResponseException
     */
    function handleResponseData($data, $route = 'index')
    {
        if ($data->success === false) {
            Flash::error($data->message);
            throw new HttpResponseException(redirect(route($route)));
        }
    }
}
