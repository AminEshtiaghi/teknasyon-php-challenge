<?php

namespace App\Http\Controllers\MockService;

use App\Exceptions\RateLimitException;
use Illuminate\Http\Request;

class AppleStoreController extends BaseStoreController
{
    public function verification(Request $request)
    {
        $receipt = $request->get('receipt');

        try {
            $result = $this->doDummyVerification($receipt);

            return $this->jsonResponse(
                $this->returnResult($result, self::UT6)
            );

        } catch (RateLimitException $exception) {

            return $this->jsonResponse(
                [
                    'message' => $exception->getMessage(),
                ],
                self::RESPONSE_TOO_MANY_REQUEST
            );

        }

    }
}