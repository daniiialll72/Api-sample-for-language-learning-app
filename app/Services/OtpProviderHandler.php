<?php

namespace App\Services;

use App\Mail\sendEmail;
use Illuminate\Support\Facades\Mail;
use Trez\RayganSms\Facades\RayganSms;

class OtpProviderHandler
{
    public static function sendOtp($phone, $otp)
    {
        switch ($phone->getCountry()) {
            case 'IR':
                RayganSms::sendAuthCode($phone->formatForMobileDialingInCountry('IR'), '* Menric * کد یکبار مصرف ورود شما : ' . $otp . ' میباشد .', false);
                break;
            default:
                $mailData = [
                    'title' => 'Mail from Menric',
                    'body' => 'your code is ' . $otp,
                ];
                Mail::to('daniiialllkhalediii@gmail.com')->send(new sendEmail($mailData));
        };
    }
}
