<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvaildIPException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request)
    {
        return \response('Invalid IP address' , 400);
    }
}
