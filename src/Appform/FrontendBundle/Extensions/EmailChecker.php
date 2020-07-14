<?php


namespace Appform\FrontendBundle\Extensions;


use WhoisApi\EmailVerifier\Builders\ClientBuilder;

class EmailChecker
{

    public function __construct()
    {
    }

    public function validate($email)
    {
//        $response = file_get_contents('http://apilayer.net/api/check?access_key=6e3290d5734b8ee270a0f86534e82722&email=' . $email . '&smtp=1&format=1');
//        $result = json_decode($response, true);

        $builder = new ClientBuilder();
        $client = $builder->build('at_0vBrWb4YFgkTWgnM6WCTqnCYJ2ocI');
        $result = $client->get($email);

        return $result->smtpCheck;
    }

    public function verify($email)
    {
        $result = json_decode($this->verify($email), true);

        return $result['status'] == 'valid';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://email-checker.p.rapidapi.com/verify/v1?email=" . $email,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "x-rapidapi-host: email-checker.p.rapidapi.com",
                "x-rapidapi-key: c191fca683msh78d34e3f6cd192dp1cf4fbjsnffb75128b755"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new \Exception('Curl has problems' . $err);
        } else {
            return $response;
        }
    }
}


