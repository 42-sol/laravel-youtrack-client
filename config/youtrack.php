<?php

return [

    /**
     * Array of entities settings, where:
     *
     * <entityName> - YT entity name in camelCase
     *   - fields - comma separated list of fields to retrieve in list queries
     *   - detailFields - comma separated list of fields to retrieve in single-record queries.
     *                    If not set will use "fields" options
     *   - class - class name to convert data
     */
    'mappings' => [
        'organization' => [
            'fields' => 'id,name',
            'class' => \YouTrackClient\Types\Organization::class
        ],

        'project' => [
            'fields' => 'id,shortName,name,organization(id),iconUrl',
            'class' => \YouTrackClient\Types\Project::class
        ],

        'article' => [
            'fields' => 'id,idReadable,summary,project(shortName),childArticles(id),parentArticle(id),ordinal',
            'detailFields' => 'created,updated,id,idReadable,reporter(name),summary,project(shortName),content,childArticles(id),parentArticle(id),ordinal',
            'class' => \YouTrackClient\Types\Article::class
        ],

        'attachment' => [
            'fields' => 'id,name,created,updated,size,mimeType,extension,url',
            'class' => \YouTrackClient\Types\Attachment::class
        ],

        'agile' => [
            'fields' => 'id,name,projects(id,shortName)',
            'detailFields' => 'id,name,columnSettings(field(id,name)'
                .',columns(presentation,isResolved,ordinal,fieldValues(id,name)))',
            'class' => \YouTrackClient\Types\Agile::class
        ],

        'issue' => [
            'fields' => 'id,idReadable,created,resolved,summary'
                .',customFields(id,name,value(id,name,localizedName,presentation))'
                .',projectFields(id)',
            'detailFields' => 'id,idReadable,created,resolved,summary,description,usesMarkdown'
                .',customFields(id,name,value(id,name,localizedName,presentation))'
                .',comments(author(name,avatarUrl),attachments(name,url,thumbnailURL),created,text,usesMarkdown)'
                .',attachments(name,url,thumbnailURL)',
            'class' => \YouTrackClient\Types\Issue::class
        ],

        'projectTimeTrackingSettings' => [
            'fields' => 'enabled,estimate(field(id,name,localizedName),emptyFieldText)'
                .',timeSpent(field(id,name,localizedName),emptyFieldText)'
                .',workItemTypes(name)',
            'class' => \YouTrackClient\Types\ProjectTimeTrackingSettings::class
        ],

        'user' => [
            'fields' => 'id,fullName,avatarUrl',
            'class' => \YouTrackClient\Types\User::class
        ]
    ]
];
