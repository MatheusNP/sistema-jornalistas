<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class TypeFromAnotherJournalistException
 * @package App\Exceptions
 */
class TypeFromAnotherJournalistException extends Exception implements \Throwable
{
    /** @var int  */
    private $status_code = 405;

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json(['exception ' => 'This type is from another journalist.', 'message' => $this->message, 'status_code' => $this->status_code], $this->status_code);
    }
}
