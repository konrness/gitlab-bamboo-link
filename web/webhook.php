<?php

use Gitlab\Api\MergeRequests;
use Gitlab\Model\Note;

require '../vendor/autoload.php';

$config = include '../app/config.php';

$client_token = $_GET['token'] ?: null;
$client_ip    = $_SERVER['REMOTE_ADDR'];

$log = fopen('../log/webhook.log', 'a');
fwrite($log, '['.date("Y-m-d H:i:s").'] ['.$client_ip.']'.PHP_EOL);
fwrite($log, print_r($_GET, true) . PHP_EOL);

// verify token
if ($client_token !== $config['access_token'])
{
    echo "invalid token";
    fwrite($log, "Invalid token [{$client_token}]".PHP_EOL);
    exit(0);
}

// verify IP
if ($client_ip !== $config['client_ip'])
{
    echo "invalid ip";
    fwrite($log, "Invalid ip [{$client_ip}]".PHP_EOL);
    exit(0);
}

$buildKey   = $_GET['buildKey'];
$branch     = $_GET['branch'];
$resultsUrl = $_GET['resultsUrl'];

fwrite($log, "Build Key: $buildKey\n");
fwrite($log, "Branch: $branch\n");
fwrite($log, "Results URL: $resultsUrl\n");

$client = new \GitLabLink\Client($config['gitLabAuthToken']);

$mergeRequest = $client->findMergeRequestByBranch($branch, 2);

if (! $mergeRequest) {
    echo "Merge request not found.";
    exit();
}

echo "Found Merge Request: " . $mergeRequest->title . PHP_EOL;

/** @var Note $comment */
$comment = $mergeRequest->addComment("Bamboo Build: $resultsUrl");
