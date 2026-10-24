<?php

declare(strict_types=1);

namespace Nearata\GifAvatars;

use Flarum\Extend;
use Nearata\GifAvatars\User\UserServiceProvider;

return [
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\ServiceProvider)
        ->register(UserServiceProvider::class),
];