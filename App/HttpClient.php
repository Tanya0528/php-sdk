<?php

class HttpClient {
    public function request(string $url) {
        $opts = array(
            'https' => array(
                'method' => "GET",
                'header' => "Content-type: application/json \r\n"
            )
        );

        $opts = stream_context_create($opts);

        $response = file_get_contents($url, false, $opts);
        if (!$response) {
            exit("Request failed");
        }

        return $response;
        // return json_decode($response, true);
    }
}