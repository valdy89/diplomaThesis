<?php

namespace mod_mapletadp;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MapleAPI
 *
 * @author valusek
 */
class MapleAPI {

    public function call($url, $request, $cookie = false) {
        $requestString = $this->arrayToXML($request);
        $response = $this->simpleCall($url, $requestString->asXML(), $cookie);
        return $this->XMLToArray($response);
    }
    public function simpleCall($url, $request, $cookie = false){
        $urlBase = \mod_mapletadp\helper\MapletaHelper::getConnectionBase(); //todo z konfigurace
        $ch = curl_init($urlBase . $url);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($cookie) {
          
            curl_setopt($ch, CURLOPT_COOKIE, 'JSESSIONID='.$cookie);
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        }

        $response = curl_exec($ch);

        curl_close($ch);
        return $response;
    }

    public function arrayToXML($request) {
        $xml_data = new \SimpleXMLElement('<Request></Request>');

        foreach ($request as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $key = 'item' . $key;
                }
                $subnode = $xml_data->addChild($key);
                array_to_xml($value, $subnode);
            } else {
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
        return $xml_data;
    }

    public function XMLToArray($xmlstring) {
       
        $xml = simplexml_load_string($xmlstring);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        return $array;
    }

}
