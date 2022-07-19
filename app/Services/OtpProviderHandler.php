<?php

namespace App\Services;

use App\Mail\sendEmail;
use Illuminate\Support\Facades\Mail;
use Trez\RayganSms\Facades\RayganSms;

class OtpProviderHandler
{
    public static function sendOtp($phone, $otp)
    {
        $mailData = [
            'title' => 'Mail from Menric',
            'body' => 'your code is ' . $otp,
        ];
        $country_initial = match ($phone->getCountry()) {
            'IR' => RayganSms::sendAuthCode($phone->formatForMobileDialingInCountry('IR'), '* Menric * کد یکبار مصرف ورود شما : ' . $otp . ' میباشد .', false),
            default => Mail::to('daniiialllkhalediii@gmail.com')->send(new sendEmail($mailData)),
        };
    }
}
