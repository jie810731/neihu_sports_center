<?php

namespace Tests\Feature;

use App\Http\Services\LoginService;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_get_login_response()
    {
        $login_service = new LoginService;
        $result = $login_service->login();

        $this->assertTrue($result);

    }
}
