<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Storage;
use Log;
use Symfony\Component\HttpClient\HttpClient;
use thiagoalessio\TesseractOCR\TesseractOCR;

class LoginService
{
    public function login()
    {
        $cookie = $this->getLoginAuthenticationCodeImageCookie();
        Log::info("cookie = $cookie");
        $authentication_code = $this->getLoginAuthenticationCode('AuthenticationCodeImage.gif');
        Log::info("image code = $authentication_code");
        $client = HttpClient::create(['headers' => [
            'Cookie' => "ASP.NET_SessionId=$cookie",
        ]]);

        $response = $client->request('POST', 'https://scr.cyc.org.tw/tp12.aspx?Module=login_page&files=login', [
            'body' => [
                'loginid' => 'F125495672',
                'loginpw' => 'leebig0211',
                'Captcha_text' => $authentication_code,
            ],
        ]);

        $is_login_success = $this->getLoginSuccess($response->getContent());

        return $is_login_success;
    }

    public function getLoginResponseCookie()
    {
        $cookie = '';
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://scr.cyc.org.tw/tp12.aspx?module=login_page&files=login');
        $set_cookie = $response->getHeaders()['set-cookie'][0];

        $re = '/SessionId=([^;]*)/m';

        preg_match_all($re, $set_cookie, $matches, PREG_SET_ORDER, 0);

        if ($matches) {
            $cookie = $matches[0][1];
        }
        return $cookie;
    }

    public function getLoginAuthenticationCodeImageCookie()
    {
        $cookie = '';
        $client = HttpClient::create();

        $response = $client->request('GET', 'https://scr.cyc.org.tw/NewCaptcha.aspx', []);

        $set_cookie = $response->getHeaders()['set-cookie'][0];

        $re = '/SessionId=([^;]*)/m';

        preg_match_all($re, $set_cookie, $matches, PREG_SET_ORDER, 0);

        if ($matches) {
            $cookie = $matches[0][1];
        }

        $contents = $response->getContent();

        Storage::put("AuthenticationCodeImage.gif", $contents);

        return $cookie;
    }

    public function getLoginAuthenticationCode($image_name)
    {
        $result = '';
        $ocr = new TesseractOCR();
        $test = Storage::get($image_name);
        $size = Storage::size($image_name);

        $ocr->imageData($test, $size)->psm(6);
        $ocr_result = $ocr->run();

        $ocr_results = str_split($ocr_result, 1);

        foreach ($ocr_results as $ocr_result) {
            if (is_numeric($ocr_result)) {
                $result .= $ocr_result;
            }
        }

        return $result;
    }

    public function getLoginSuccess($response_content)
    {
        $is_login_success = true;
        $re = '/(1,,|2,驗證碼錯誤,)/m';

        preg_match_all($re, $response_content, $matches, PREG_SET_ORDER, 0);

        if ($matches) {
            $is_login_success = false;
        }

        return $is_login_success;
    }
};
