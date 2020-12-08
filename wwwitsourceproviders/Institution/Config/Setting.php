<?php

namespace ITSourceProviders\Institution\Config;

class Setting {

    //TODO
    private static $base = 'https://institution.itsourceproviders.com';
    private static $credentials = array();

    public static function setCredentials($path) {
        self::$credentials = json_decode(file_get_contents($path), true);
    }

    public static function get(
            $link,
            $parameters
    ) {
        try {
            $parameters['institution_id'] = Setting::$credentials['institution_id'];
            $payload = array(
                'institution_id' => $parameters['institution_id']
            );
            $jwt = \Firebase\JWT\JWT::encode($payload, Setting::$credentials['key'], 'RS256');
            $client = new \GuzzleHttp\Client([
                'base_uri' => Setting::$base
            ]);
            $response = $client->request('GET', '/v1.1/private' . $link, [
                'headers' => [
                    'TOKEN' => $jwt
                ],
                'query' => $parameters
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            $success = intval($data[0]['success']);
            if ($success == 0) {
                $messages = '';
                for ($i = 0; $i < count($data[0]['messages']); $i++) {
                    $message = $data[0]['messages'][$i];
                    foreach ($message as $key => $value) {
                        $messages .= $key . ' : ' . $value . ',';
                    }
                }
                $messages = substr($messages, 0, -1);
                throw new \Exception($messages);
            } else {
                $msg = array();
                return new \ITSourceProviders\Institution\Query\SResponse($data[0]['id'], $data[0]['messages'], $data);
            }
        } catch (\Exception $exception) {
            $responseBody = $exception->getResponse()->getBody(true);
            $err = json_decode($responseBody, true);
            $messages = '';
            for ($i = 0; $i < count($err[0]['messages']); $i++) {
                $message = $err[0]['messages'][$i];
                foreach ($message as $key => $value) {
                    $messages .= $key . ' : ' . $value . ',';
                }
            }
            $messages = substr($messages, 0, -1);
            throw new \Exception($messages);
        }
    }

}
