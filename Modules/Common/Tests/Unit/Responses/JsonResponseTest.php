<?php

use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Bootstrap\RegisterFacades;
use Modules\Common\Responses\JsonResponseFacade;

test('test successResponse is return json response', function () {
    $jsonResponse = JsonResponseFacade::successResponse([]);
// TODO: Complete this
    $this->assertEquals($jsonResponse->status(), 200);
    $this->assertEquals($jsonResponse->getContent(), []);

});
