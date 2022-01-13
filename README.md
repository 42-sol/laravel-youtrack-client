## Installation

### Publish packages resources

This will create `config/youtrack.php` configuration file.

```shell
php artisan vendor:publish --provider="YouTrackClient\Providers\YouTrackClientProvider"
```

### [Optional] Include service provider in your app
If you want use `YouTrackClient` dependency injection, will insert library service provider 
`YouTrackClient\Providers\YouTrackClientProvider::class` in your `config/app.php` file:

```php
'providers' => [
    // other service providers
    YouTrackClient\Providers\YouTrackClientProvider::class,
]
```

### Configure

After publishing package resources you can edit configuration in `config/youtrack.php` file.

To set your YouTrack instance add to `.env` variables:
```dotenv
YT_BASE_URL=https://youtrack.example.com/api           # required
# YT_HUB_URL=https://youtrack.example.com/hub/api/rest # optional
YT_TOKEN="youtrack access token"                       # required
```

### Default routing

If needed, use `YouTrackClient\YouTrackRoutes::apply();` to make default library routes, e.g.:

```php
/**
 * This will make routes:
 *  - /yt/projects
 *  - /yt/issues
 *  - ...
 */
Route::prefix('/yt')->group(function () {
    YouTrackClient\YouTrackRoutes::apply();
});
```

Routes:
* `/projects` - get all projects
* `/projects/{id}` - get detailed project information  by id
* `/projects/{id}/issues` - get project issues
* `/projects/{id}/timeTrackingSettings` - get project time tracking settings
* `/agiles` - get all agile board
* `/agiles/{id}` - get detailed agile information by id
* `/issues?query=<filter>&offset=0&limit=50` - get issues by filter
* `/issues/{id}` - get detailed issue information by id
* `/organizations` - get all organizations
* `/users` - get all users

## Usage

### Manually

```php
use YouTrackClient\YouTrackClient;

$client = new YouTrackClient([
    'baseUrl' => 'https://youtrack.example.com/api',
    'hubUrl' => 'https://youtrack.example.com/hub/api/rest', // optional
    'token' => '<youtrack access token>'
]);

$client->getProjects();
```

### With dependency injection:

```php
use YouTrackClient\YouTrackClient;

class YoutrackTestController extends Controller
{
    private YouTrackClient $client;

    // YouTrackClient will be injected here
    public function __construct(YouTrackClient $client)
    {
        $this->client = $client;
    }

    public function getProjects() {
        return $this->client->getProjects();
    }
}
```
