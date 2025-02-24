<?php

if (!function_exists('xmlToJson')) {
    function xmlToJson($xmlString)
    {
        // Load the XML string into a SimpleXMLElement object
        $xml = simplexml_load_string($xmlString);

        // Convert the SimpleXMLElement object to a JSON string
        $json = json_encode($xml, JSON_PRETTY_PRINT);

        return $json;
    }
}
if (!function_exists('jsonToXml')) {
    function jsonToXml($jsonString)
    {
        // Decode the JSON string into a PHP array
        $data = json_decode($jsonString, true);

        // Create a new SimpleXMLElement object
        $xml = new SimpleXMLElement('<root/>');

        // Recursive function to convert array to XML
        function arrayToXml($array, &$xml)
        {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    if (is_numeric($key)) {
                        $key = 'item'; // Handle numeric keys (e.g., arrays of objects)
                    }
                    $subnode = $xml->addChild($key);
                    arrayToXml($value, $subnode);
                } else {
                    $xml->addChild($key, htmlspecialchars($value));
                }
            }
        }

        // Convert the array to XML
        arrayToXml($data, $xml);

        // Return the XML as a string
        return $xml->asXML();
    }
}
