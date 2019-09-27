<?php

/*
 * This File is part of KREST is a Restful service extension for SugarCRM
 * 
 * Copyright (C) 2015 AAC SERVICES K.S., DOSTOJEVSKÉHO RAD 5, 811 09 BRATISLAVA, SLOVAKIA
 * 
 * you can contat us at info@spicecrm.io
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

if (!function_exists('curl_file_create')) {

    function curl_file_create($filename, $mimetype = '', $postname = '') {
        return "@$filename;filename="
                . ($postname ? : basename($filename))
                . ($mimetype ? ";type=$mimetype" : '');
    }

}

$KRESTManager->registerExtension('cardreader', '1.0');

$app->post('/cardreader', function () use ($app, $KRESTManager) {
    global $sugar_config;
    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

// 1. Send image to Cloud OCR SDK using processImage call
// 2.	Get response as xml
// 3.	Read taskId from xml
// To create an application and obtain a password,
// register at http://cloud.ocrsdk.com/Account/Register
// More info on getting your application id and password at
// http://ocrsdk.com/documentation/faq/#faq3
// Name of application you created
    $applicationId = 'spicecrm';
// Password should be sent to your e-mail after application was created
    $password = 'P1K2SPM1YFLmwPvjpA4TrR/t';


    $postBody = json_decode($_POST, true);
    $filebas64 = $postBody['card'];

    $url = 'http://cloud.ocrsdk.com/processBusinessCard';

    // change for windows or Linux
    $directory = str_replace('KREST\scopes', '', dirname(__FILE__));
    $directory = str_replace('KREST/scopes', '', $directory);

    $filename = str_replace('\\', '/', $directory . $sugar_config['upload_dir'] . create_guid() . '.jpg');

    file_put_contents($filename, base64_decode($filebas64));

// Send HTTP POST request and ret xml response
    $curlHandle = curl_init();
    curl_setopt($curlHandle, CURLOPT_URL, $url);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlHandle, CURLOPT_USERPWD, "$applicationId:$password");
    curl_setopt($curlHandle, CURLOPT_POST, 1);
    curl_setopt($curlHandle, CURLOPT_USERAGENT, "PHP Cloud OCR SDK Sample");
    curl_setopt($curlHandle, CURLOPT_FAILONERROR, true);
    $post_array = array(
        "file" => curl_file_create($filename, 'image/jpeg', 'card')
    );
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $post_array);

    $response = curl_exec($curlHandle);

    unlink($filename);

    if ($response == FALSE) {
        $errorText = curl_error($curlHandle);
        curl_close($curlHandle);
        die($errorText);
    }
    $httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
    curl_close($curlHandle);
// Parse xml response
    $xml = simplexml_load_string($response);
    if ($httpCode != 200) {
        if (property_exists($xml, "message")) {
            die($xml->message);
        }
        die("unexpected response " . $response);
    }
    $arr = $xml->task[0]->attributes();
    $taskStatus = $arr["status"];
    if ($taskStatus != "Queued") {
        die("Unexpected task status " . $taskStatus);
    }

// Task id
    $taskid = $arr["id"];

// 4. Get task information in a loop until task processing finishes
// 5. If response contains "Completed" staus - extract url with result
// 6. Download recognition result (text) and display it
    $url = 'http://cloud.ocrsdk.com/getTaskStatus';
    $qry_str = "?taskid=$taskid";
// Check task status in a loop until it is finished
// Note: it's recommended that your application waits
// at least 2 seconds before making the first getTaskStatus request
// and also between such requests for the same task.
// Making requests more often will not improve your application performance.
// Note: if your application queues several files and waits for them
// it's recommended that you use listFinishedTasks instead (which is described
// at http://ocrsdk.com/documentation/apireference/listFinishedTasks/).
    while (true) {
        sleep(5);
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url . $qry_str);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_USERPWD, "$applicationId:$password");
        curl_setopt($curlHandle, CURLOPT_USERAGENT, "PHP Cloud OCR SDK Sample");
        curl_setopt($curlHandle, CURLOPT_FAILONERROR, true);
        $response = curl_exec($curlHandle);
        $httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        curl_close($curlHandle);

// parse xml
        $xml = simplexml_load_string($response);
        if ($httpCode != 200) {
            if (property_exists($xml, "message")) {
                die($xml->message);
            }
            die("Unexpected response " . $response);
        }
        $arr = $xml->task[0]->attributes();
        $taskStatus = $arr["status"];
        if ($taskStatus == "Queued" || $taskStatus == "InProgress") {
// continue waiting
            continue;
        }
        if ($taskStatus == "Completed") {
// exit this loop and proceed to handling the result
            break;
        }
        if ($taskStatus == "ProcessingFailed") {
            die("Task processing failed: " . $arr["error"]);
        }
        die("Unexpected task status " . $taskStatus);
    }
// Result is ready. Download it
    $url = $arr["resultUrl"];
    $curlHandle = curl_init();
    curl_setopt($curlHandle, CURLOPT_URL, $url);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
// Warning! This is for easier out-of-the box usage of the sample only.
// The URL to the result has https:// prefix, so SSL is required to
// download from it. For whatever reason PHP runtime fails to perform
// a request unless SSL certificate verification is off.
    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($curlHandle);
    curl_close($curlHandle);

// Let user donwload rtf result
    echo json_encode(array('vcard' => $response));
});
