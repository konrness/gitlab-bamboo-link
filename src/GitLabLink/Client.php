<?php

namespace GitLabLink;

use Gitlab\Api\MergeRequests;
use Gitlab\Api\Projects;
use Gitlab\Model\MergeRequest;
use Gitlab\Model\Project;
use GitLabLink\HttpClient\Listener\LogListener;

class Client
{
    /**
     * @var \Gitlab\Client
     */
    private $client;

    /**
     * @var Project
     */
    private $project;

    public function __construct($baseUrl, $authToken, $projectId, $debug = false)
    {
        // load all GitLab Merge Requests
        $this->client = new \Gitlab\Client($baseUrl);

        // add logger
        if ($debug) {
            $this->client->getHttpClient()->addListener(new LogListener(), 10);
        }

        $this->client->authenticate($authToken, \Gitlab\Client::AUTH_URL_TOKEN);

        /** @var Projects $projectApi */
        $projectApi = $this->client->api('projects');
        $projectData = $projectApi->show($projectId);

        $this->project = Project::fromArray($this->client, $projectData);

    }

    /**
     * @param string $branch
     * @return MergeRequest
     */
    public function findMergeRequestByBranch($branch)
    {
        $mergeRequests = $this->project->mergeRequests(1, 100, MergeRequests::STATE_OPENED);

        foreach ($mergeRequests as $mergeRequest) {
            /** @var MergeRequest $mergeRequest */

            if ($mergeRequest->source_branch == $branch) {
                return $mergeRequest;
            }
        }

        return null;
    }
}