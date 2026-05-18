<?php

return [
    '~^$~'           => [\MyProject\Controllers\MainController::class, 'main'],
    '~^hello/(.*)$~' => [\MyProject\Controllers\MainController::class, 'sayHello'],
    '~^bye/(.*)$~'   => [\MyProject\Controllers\MainController::class, 'sayBye'],
];
