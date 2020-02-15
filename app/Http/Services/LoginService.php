<?php

namespace App\Http\Services;

use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Facades\Storage;

class LoginService
{
    public function getLoginResponse(){
        
    }
    
    public function getLoginAuthenticationCode()
    {
        $result = '';
        $ocr = new TesseractOCR();
        $test = Storage::get('test_number.gif');
        $size = Storage::size('test_number.gif');
        $ocr->imageData($test, $size)->psm(6);
        $ocr_result = $ocr->run();

        $ocr_results = str_split($ocr_result, 1);

        foreach($ocr_results as  $ocr_result){
            if (is_numeric($ocr_result)){
                $result .= $ocr_result;
            }
        }

        return $result;
    }
};
