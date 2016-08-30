<?php

use Illuminate\Http\Request;
use Mockery as m;
use Recca0120\LaravelPayum\Http\Controllers\PaymentController;
use Recca0120\LaravelPayum\Service\Payum as PayumService;

class PaymentControllerTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_common_behaviors()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $controller = new PaymentController();
        $payumService = m::mock(PayumService::class);
        $request = m::mock(Request::class);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $methods = ['authorize', 'capture', 'notify', 'payout', 'refund', 'sync'];

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        foreach ($methods as $method) {
            $exceptedPayumToken = uniqid();
            $payumService->shouldReceive($method)->with($request, $exceptedPayumToken)->andReturn($exceptedPayumToken);
            $this->assertSame($exceptedPayumToken, call_user_func_array([$controller, $method], [$payumService, $request, $exceptedPayumToken]));
        }
    }

    public function test_notify_unsafe()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $controller = new PaymentController();
        $payumService = m::mock(PayumService::class);
        $request = m::mock(Request::class);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $exceptedGatewayName = 'fooGatewayName';
        $payumService->shouldReceive('notifyUnsafe')->with($exceptedGatewayName)->andReturn($exceptedGatewayName);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $this->assertSame($exceptedGatewayName, $controller->notifyUnsafe($payumService, $exceptedGatewayName));
    }
}
