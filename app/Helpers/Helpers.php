<?php

if (!function_exists('generateAvatar')) {
    function generateAvatar($fullname)
    {
        $avatarName = urlencode("{$fullname}");
        return "https://ui-avatars.com/api/?name={$avatarName}&background=838383&color=FFFFFF&size=140&rounded=true";
    }
}
