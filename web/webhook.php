<?php

use Gitlab\Model\Note;

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

$buildKey   = $_GET['buildKey'];
$branch     = $_GET['branch'];
$resultsUrl = $_GET['resultsUrl'];

fwrite($log, "Build Key: $buildKey\n");
fwrite($log, "Branch: $branch\n");
fwrite($log, "Results URL: $resultsUrl\n");

$client = new \GitLabLink\Client($config['gitLabBaseUrl'], $config['gitLabAuthToken'], $config['gitLabProjectId']);

$mergeRequest = $client->findMergeRequestByBranch($branch);

if (! $mergeRequest) {
    $message = "Merge request not found.\n\n";
    fwrite($log, $message);
    echo $message;
    exit();
}

fwrite($log, "Found Merge Request: " . $mergeRequest->title . PHP_EOL);

$commentBody = "Bamboo Build: $resultsUrl";

/** @var Note $comment */
$comment = $mergeRequest->addComment($commentBody);

fwrite($log, "Added comment: $commentBody \n\n");
fclose($log);
