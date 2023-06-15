<?php

if (! function_exists('statusMessage')) {
    function statusMessage($status, $message) {
        return ['status' => $status, 'message' => $message];
    }
}