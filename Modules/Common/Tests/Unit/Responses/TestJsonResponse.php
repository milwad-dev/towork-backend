<?php

use Modules\Common\Responses\JsonResponse;

test('test successResponse is return json response', function () {
    $jsonResponse = (new JsonResponse())->successResponse([]);

    $this->assertEquals($jsonResponse->status(), 200);
    $this->assertEquals($jsonResponse->getContent(), []);

});
