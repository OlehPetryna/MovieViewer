<?php

return [
    "db" => [
        "host" => "",
        "db" => "",
        "user" => "",
        "password" => "",
    ],
    "dbConnection" => [
        "class" => "app\\base\\Connection"
    ],
    "router" => [
        "rules" => [
            "show-details" => "site/detailed-view",
            "edit-movie" => "site/edit-view",
            "save-movie" => "site/save-movie",
            "add-movie" => "site/add-movie-view",
            "import-movie" => "site/import-file",
            "delete-movie" => "site/delete-movie",
            "render-movies" => "site/render-movies",
            "/" => "site/index",
        ]
    ],
    "debug" => true,
    "viewPath" => __DIR__."/../view/",
];