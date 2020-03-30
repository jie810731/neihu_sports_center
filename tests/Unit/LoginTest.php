<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Services\LoginService;


class LoginTest extends TestCase
{
    public function test_get_login_response()
    {

        $login_service=  new LoginService;
        $login_service->login();

        dd('abc');

    }

    public function testBasicTest()
    {
        $exptct = '53662';

        $login_service=  new LoginService;
        $result = $login_service->getLoginAuthenticationCode();

        $this->assertEquals($exptct,$result);
    }
}
