<?php

require '../vendor/autoload.php';

$config = include '../app/config.php';

$client_token = $_GET['token'] ?: null;
$client_ip    = $_SERVER['REMOTE_ADDR'];

$log = fopen(__DIR__ . '/../log/webhook.log', 'a');
fwrite($log, '['.date("Y-m-d H:i:s").'] ['.$client_ip.']'.PHP_EOL);

// verify token
if ($client_token !== $config['access_token'])
{
    echo "invalid token";
    fwrite($log, "Invalid token [{$client_token}]".PHP_EOL);
    exit(0);
}

// verify IP
if (isset($config['client_ip']) && $client_ip !== $config['client_ip'])
{
    echo "invalid ip";
    fwrite($log, "Invalid ip [{$client_ip}]".PHP_EOL);
    exit(0);
}

$event = $_SERVER['HTTP_X_GITLAB_EVENT'];
fwrite($log, "Event Type: $event\n");

$json = file_get_contents('php://input');
$data = json_decode($json, true);

fwrite($log, "Event Data: $json\n");

switch ($event) {
    case "Merge Request Hook":
        if ($data['object_attributes']['action'] == 'open') {
            // @todo Create bamboo plan branch
        }

        break;
    case "Note Hook":


        break;
}

echo 'done';
fclose($log);
