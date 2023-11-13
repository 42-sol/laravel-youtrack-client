<?php

namespace YouTrackClient;

use YouTrackClient\Types\Agile;
use YouTrackClient\Types\Article;
use YouTrackClient\Types\Issue;
use YouTrackClient\Types\Organization;
use YouTrackClient\Types\Project;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use YouTrackClient\Types\ProjectTimeTrackingSettings;
use YouTrackClient\Types\User;

class YouTrackClient
{
    protected string $baseUrl;
    protected string $hubUrl;

    private string $token;

    public function __construct(array $options)
    {
        $this->baseUrl = Arr::get($options, 'baseUrl');
        $this->hubUrl = Arr::get($options, 'hubUrl');
        $this->token = Arr::get($options, 'token');
    }

    /**
     * Get organizations
     *
     * @return Organization[]
     */
    public function getOrganizations(): array
    {
        $query = [
            'fields' => $this->getTypeFields('organization')
        ];

        return $this
            ->get('/admin/organizations', $query)
            ->collect()
            ->mapInto($this->getTypeClass('organization'))
            ->toArray();
    }

    /**
     * Get projects
     *
     * @link https://www.jetbrains.com/help/youtrack/devportal/resource-api-admin-projects.html#get_all-Project-method Get All Projects
     *
     * @param int $offset lets you set a number of returned entities to skip before returning the first one.
     * @param int $limit lets you specify the maximum number of entries that are returned in the response.
     *
     * @return Project[]
     */
    public function getProjects(int $offset = 0, int $limit = 5065550): array
    {
        $query = [
            'fields' => $this->getTypeFields('project'),
            '$skip' => $offset,
            '$top' => $limit
        ];

        return $this
            ->get('/admin/projects', $query)
            ->collect()
            ->mapInto($this->getTypeClass('project'))
            ->toArray();
    }

    /**
     * Get project detailed description
     *
     * @link https://www.jetbrains.com/help/youtrack/devportal/operations-api-admin-projects.html#get-Project-method Read a Specific Project
     *
     * @param string $id
     * @return Project
     */
    public function getProject(string $id): Project
    {
        $query = [
            'fields' => $this->getTypeDetailedFields('project')
        ];

        $data = $this
            ->get("/admin/projects/{$id}", $query)
            ->json();

        $class = $this->getTypeClass('project');
        return new $class($data);
    }

    /**
     * Get project time tracking settings
     *
     * @link https://www.jetbrains.com/help/youtrack/devportal/resource-api-admin-projects-projectID-timeTrackingSettings.html Get Project Time Tracking Settings
     *
     * @param string $projectId
     * @return ProjectTimeTrackingSettings
     */
    public function getProjectTimeTrackingSettings(string $projectId): ProjectTimeTrackingSettings
    {
        $query = [
            'fields' => $this->getTypeDetailedFields('projectTimeTrackingSettings')
        ];

        $data = $this
            ->get("/admin/projects/{$projectId}/timeTrackingSettings", $query)
            ->json();

        $class = $this->getTypeClass('projectTimeTrackingSettings');
        return new $class($data);
    }

    /**
     * Get Articles in a Project
     *
     * @link https://www.jetbrains.com/help/youtrack/devportal/resource-api-admin-projects-projectID-articles.html
     *
     * @return Article[]
     */
    public function getProjectArticles(string $projectId, int $offset = 0, int $limit = 5065550): array {
        $query = [
            'fields' => $this->getTypeFields('article'),
            '$skip' => $offset,
            '$top' => $limit
        ];

        return $this
            ->get("/admin/projects/{$projectId}/articles", $query)
            ->collect()
            ->mapInto($this->getTypeClass('article'))
            ->toArray();
    }

    /**
     * Get issues
     *
     * @link https://www.jetbrains.com/help/youtrack/devportal/resource-api-issues.html#get_all-Issue-method Read a List of Issues
     * @link https://www.jetbrains.com/help/youtrack/incloud/?Search-and-Command-Attributes Search Query Reference
     *
     * @param ?string $query search query
     * @param int $offset lets you set a number of returned entities to skip before returning the first one.
     * @param int $limit lets you specify the maximum number of entries that are returned in the response.
     * @return Issue[]
     */
    public function getIssues(string $query = null, int $offset = 0, int $limit = 5065550): array {
        $query = [
            'fields' => $this->getTypeFields('issue'),
            'query' => $query,
            '$skip' => $offset,
            '$top' => $limit
        ];

        return $this
            ->get('/issues', $query)
            ->collect()
            ->mapInto($this->getTypeClass('issue'))
            ->toArray();
    }

    /**
     * Get detailed issue description
     *
     * @link https://www.jetbrains.com/help/youtrack/devportal/operations-api-issues.html#get-Issue-method Read a Specific Issue
     *
     * @param string $id
     * @return Issue
     */
    public function getIssue(string $id): Issue
    {
        $query = [
            'fields' => $this->getTypeDetailedFields('issue')
        ];

        $data = $this
            ->get("/issues/{$id}", $query)
            ->json();

        $class = $this->getTypeClass('issue');
        return new $class($data);
    }

    /**
     * Update issue
     *
     * @link https://www.jetbrains.com/help/youtrack/devportal/operations-api-issues.html#update-Issue-method Update a Specific Issue
     *
     * @param string $id issue id
     * @param array $body issue fields to update
     * @param bool $mute mute notifications
     * @return Issue
     */
    public function updateIssue(string $id, array $body, bool $mute = false): Issue
    {
        $result = $this
            ->post(
                "/issues/${id}",
                $body,
                [
                    'fields' => $this->getTypeDetailedFields('issue'),
                    'muteUpdateNotifications' => $mute ? 'true' : 'false'
                ]
            )
            ->json();

        if (Arr::has($result, 'error')) {
            return $result;
        }

        $class = $this->getTypeClass('issue');
        return new $class($result);
    }

    /**
     * Get agiles
     *
     * @link https://www.jetbrains.com/help/youtrack/devportal/resource-api-users.html#get_all-User-method Read a List of Agiles
     *
     * @return Agile[]
     */
    public function getAgiles(): array
    {
        $query = [
            'fields' => $this->getTypeFields('agile')
        ];

        return $this
            ->get('/agiles', $query)
            ->collect()
            ->mapInto($this->getTypeClass('agile'))
            ->toArray();
    }

    /**
     * Get detailed agile description by id
     *
     * @link https://www.jetbrains.com/help/youtrack/devportal/operations-api-agiles.html#get-Agile-method Read a Specific Agile
     *
     * @param string $id
     * @return Agile
     */
    public function getAgile(string $id): Agile
    {
        $query = [
            'fields' => $this->getTypeDetailedFields('agile')
        ];

        $data = $this
            ->get("/agiles/{$id}", $query)
            ->json();

        $class = $this->getTypeClass('agile');
        return new $class($data);
    }

    /**
     * Get users list
     *
     * @link https://www.jetbrains.com/help/youtrack/devportal/resource-api-users.html#get_all-User-method Read a List of Users
     *
     * @return User[]
     */
    public function getUsers(): array {
        $query = [
            'fields' => $this->getTypeFields('user')
        ];

        return $this
            ->get('/users', $query)
            ->collect()
            ->mapInto($this->getTypeClass('user'))
            ->toArray();
    }

    /**
     * Get articles
     *
     * @link https://www.jetbrains.com/help/youtrack/devportal/resource-api-articles.html#get_all-Article-method Get All articles
     *
     * @return Article[]
     */
    public function getArticles(): array {
        $query = [
            'fields' => $this->getTypeFields('article'),
        ];

        return $this
            ->get('/articles', $query)
            ->collect()
            ->mapInto($this->getTypeClass('article'))
            ->toArray();
    }

    public function getArticleChild(string $articleId, int $offset = 0, int $limit = 50): array {
        $query = [
            'fields' => $this->getTypeFields('article'),
            '$skip' => $offset,
            '$top' => $limit
        ];

        return $this
            ->get("/articles/{$articleId}/childArticles", $query)
            ->collect()
            ->mapInto($this->getTypeClass('article'))
            ->toArray();
    }

    public function getArticleAttachments(string $articleId, int $offset = 0, int $limit = 500): array {
        $query = [
            'fields' => $this->getTypeFields('attachment'),
            '$skip' => $offset,
            '$top' => $limit
        ];

        return $this
            ->get("/articles/{$articleId}/attachments", $query)
            ->collect()
            ->mapInto($this->getTypeClass('attachment'))
            ->toArray();
    }

    /**
     * Get articles detailed description
     *
     * @link https://www.jetbrains.com/help/youtrack/devportal/resource-api-articles.html#Article-supported-fields
     *
     * @param string $id
     * @return Project
     */
    public function getArticle(string $id): Article
    {
        $query = [
            'fields' => $this->getTypeDetailedFields('article')
        ];

        $data = $this
            ->get("/articles/{$id}", $query)
            ->json();

        $class = $this->getTypeClass('article');
        return new $class($data);
    }

    /**
     * Execute command to list of issues
     *
     * Usage: $this->runCommand('Fixed', [['idReadable' => 'DM-3']]);
     *
     * @link https://www.jetbrains.com/help/youtrack/devportal/api-usecase-commands.html Apply Commands to Issues
     *
     * @param string $command command query to execute
     * @param array $issues array of issues to apply command
     * @return mixed
     */
    public function runCommand(string $command, array $issues): mixed
    {
        return $this
            ->post(
                "/commands",
                [
                    "query" => $command,
                    "issues" => $issues
                ]
            )
            ->json();
    }

    /**
     * Make request to Youtrack API
     *
     * @param string $uri request uri
     * @param array $query query params
     * @return Response
     */
    protected function get(string $uri, array $query = []): Response
    {
        return Http::baseUrl($this->baseUrl)
            ->withToken($this->token)
            ->get($uri, $query);
    }

    /**
     * Make request to Hub API
     *
     * @param string $uri request uri
     * @param array $query query params
     * @return Response
     */
    protected function getHub(string $uri, array $query = []): Response
    {
        return Http::baseUrl($this->hubUrl)
            ->withToken($this->token)
            ->get($uri, $query);
    }

    /**
     * Make post request to Youtrack API
     *
     * @param string $uri request uri
     * @param array $body data
     * @param array $query query params
     * @return Response
     */
    protected function post(string $uri, array $body, array $query = []): Response
    {
        return Http::baseUrl($this->baseUrl)
            ->withToken($this->token)
            ->withOptions([
                'query' => $query
            ])
            ->post($uri, $body);
    }

    protected function getTypeFields(string $type) {
        return config("youtrack.mappings.$type.fields");
    }

    protected function getTypeDetailedFields(string $type) {
        return
            config("youtrack.mappings.$type.detailFields")
            ?? $this->getTypeFields($type);
    }

    protected function getTypeClass(string $type) {
        return config("youtrack.mappings.$type.class");
    }
}
