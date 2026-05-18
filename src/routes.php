<?php

return [
    '~^$~'                         => [\MyProject\Controllers\MainController::class, 'main'],
    '~^hello/(.*)$~'               => [\MyProject\Controllers\MainController::class, 'sayHello'],
    '~^bye/(.*)$~'                 => [\MyProject\Controllers\MainController::class, 'sayBye'],
    '~^articles/(\d+)$~'           => [\MyProject\Controllers\ArticlesController::class, 'show'],
    '~^article/(\d+)/edit$~'       => [\MyProject\Controllers\ArticleController::class, 'edit'],
    '~^articles/(\d+)/comments$~'  => [\MyProject\Controllers\CommentsController::class, 'add'],
    '~^comments/(\d+)/edit$~'      => [\MyProject\Controllers\CommentsController::class, 'edit'],
];