<?php

namespace YouTrackClient;

use Illuminate\Support\Facades\Route;
use YouTrackClient\Http\Controllers\YoutrackDefaultController;

class YouTrackRoutes
{
    public static function apply()
    {
        Route::get('/projects', [YoutrackDefaultController::class, 'listProjects']);
        Route::get('/projects/{id}', [YoutrackDefaultController::class, 'getProject']);
        Route::get('/projects/{id}/issues', [YoutrackDefaultController::class, 'listProjectIssues']);
        Route::get('/projects/{id}/articles', [YoutrackDefaultController::class, 'listProjectArticles']);
        Route::get(
            '/projects/{id}/timeTrackingSettings',
            [YoutrackDefaultController::class, 'getProjectTimeTrackingSettings']
        );

        Route::get('/agiles', [YoutrackDefaultController::class, 'listAgiles']);
        Route::get('/agiles/{id}', [YoutrackDefaultController::class, 'getAgile']);

        Route::get('/issues', [YoutrackDefaultController::class, 'listIssues']);
        Route::get('/issues/{id}', [YoutrackDefaultController::class, 'getIssue']);

        Route::get('/organizations', [YoutrackDefaultController::class, 'listOrganizations']);

        Route::get('/users', [YoutrackDefaultController::class, 'listUsers']);

        Route::get('/articles', [YoutrackDefaultController::class, 'listArticles']);
        Route::get('/articles/{id}', [YoutrackDefaultController::class, 'getArticle']);
        Route::get('/articles/{id}/childArticles', [YoutrackDefaultController::class, 'listArticleChild']);
    }
}
