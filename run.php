<?php

use Gitlab\Model\Note;

require 'vendor/autoload.php';

$config = include 'app/config.php';

$client = new \GitLabLink\Client($config['gitLabBaseUrl'], $config['gitLabAuthToken'], $config['gitLabProjectId']);

$mergeRequest = $client->findMergeRequestByBranch("release/8.0/feature/gitlab-tests", 2);

if ($mergeRequest) {
    echo "Found Merge Request: " . $mergeRequest->title . PHP_EOL;

    /** @var Note $comment */
    $comment = $mergeRequest->addComment("Test Comment from Script 1");

    echo $comment->body;
}