<?php

declare(strict_types=1);

namespace Nearata\GifAvatars\User;

use Flarum\Foundation\AbstractServiceProvider;
use Flarum\User\Command\UploadAvatarHandler;

class UserServiceProvider extends AbstractServiceProvider
{
    public function boot(): void
    {
        $this->container
            ->when(UploadAvatarHandler::class)
            ->needs(\Flarum\User\AvatarUploader::class)
            ->give(AvatarUploader::class);
    }
}