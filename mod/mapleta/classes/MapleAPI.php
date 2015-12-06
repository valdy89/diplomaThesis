<?php

namespace mod_mapleta;

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

    public function call($url, $request) {

        $requestString = $this->arrayToXML($request);

        $urlBase = 'https://muni.mapleta.com/muni/';
        $ch = curl_init($urlBase . $url);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString->asXML());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        return $this->XMLToArray($response);
    }

    public function arrayToXML($request) {
        $xml_data = new \SimpleXMLElement('<Request></Request>');

        foreach ($request as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $key = 'item' . $key; //dealing with <0/>..<n/> issues
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
