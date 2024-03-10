<?php

namespace Arindam\GsheetAppScript\Traits;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;

trait GsheetAppScriptApis {

    public static function getAllRecords($apiUrl)
    {
        return json_decode(self::executeAPI($apiUrl), true);
    }

    public static function deleteAllRecords($apiUrl)
    {
        $data = array('actionkey' => 'CLEAR');
        return self::executeAPI($apiUrl, array(), 'POST', $data);
    }

    public static function addNewRow($apiUrl, $data = array())
    {
        $data['actionkey'] = 'SAVE';
        return self::executeAPI($apiUrl, array(), 'POST', $data);
    }

    public static function updateHeading($apiUrl, $data = array())
    {
        $data['actionkey'] = 'UPDATE';
        $data['id'] = 1;
        return self::executeAPI($apiUrl, array(), 'POST', $data);
    }

    public static function deleteRow($apiUrl, $data = array())
    {
        $data['actionkey'] = 'DELETE';
        $data['id'] = 1;
        return self::executeAPI($apiUrl, array(), 'POST', $data);
    }

    public static function updateRow($apiUrl, $data = array())
    {
        $data['actionkey'] = 'UPDATE';
        return self::executeAPI($apiUrl, array(), 'POST', $data);
    }

    public static function executeAPI($url = '', $header = array(), $method = 'GET', $data = array())
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            if (strtoupper($method) == 'POST') {
                curl_setopt($ch, CURLOPT_POST, 1);
            }
            if (strtoupper($method) == 'PUT') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            }
            if (strtoupper($method) == 'DELETE') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            }
            if (strtoupper($method) == 'PATCH') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $result = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);
            if ($error) {
                \Log::info("cUrl Error" . $error);
            }
            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
}