<?php

namespace YouTrackClient\Http\Controllers;

use Illuminate\Http\Request;
use YouTrackClient\YouTrackClient;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class YoutrackDefaultController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected YouTrackClient $client;

    public function __construct(YouTrackClient $client)
    {
        $this->client = $client;
    }

    public function listOrganizations()
    {
        return $this->client->getOrganizations();
    }

    public function listProjects()
    {
        return $this->client->getProjects();
    }

    public function getProject(string $id)
    {
        return $this->client->getProject($id);
    }

    public function listProjectIssues(string $id)
    {
        return $this->client->getIssues("project: $id");
    }

    public function listProjectArticles(string $projectId)
    {
        return $this->client->getProjectArticles($projectId);
    }

    public function getProjectTimeTrackingSettings(string $projectId)
    {
        return $this->client->getProjectTimeTrackingSettings($projectId);
    }

    public function listAgiles()
    {
        return $this->client->getAgiles();
    }

    public function getAgile(string $id)
    {
        return $this->client->getAgile($id);
    }

    public function listIssues(Request $request)
    {
        $query = $request->input('query');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 50);

        return $this->client->getIssues($query, $offset, $limit);
    }

    public function getIssue(string $id)
    {
        return $this->client->getIssue($id);
    }

    public function listUsers()
    {
        return $this->client->getUsers();
    }

    public function listArticles()
    {
        return $this->client->getArticles();
    }

    public function listArticleChild(string $articleId)
    {
        return $this->client->getArticleChild($articleId);
    }

    public function listArticleAttachments(string $articleId)
    {
        return $this->client->getArticleAttachments($articleId);
    }

    public function getArticle(string $id)
    {
        return $this->client->getArticle($id);
    }
}
