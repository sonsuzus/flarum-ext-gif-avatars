<?php

declare(strict_types=1);

namespace Nearata\GifAvatars\User;

use Flarum\User\User;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

class AvatarUploader extends \Flarum\User\AvatarUploader
{
    public function __construct(
        protected Factory $filesystemFactory,
        protected LoggerInterface $logger
    ) {
        parent::__construct($filesystemFactory);
    }

    public function upload(User $user, Image $image): void
    {
        if ($image->mime() !== 'image/gif' || $user->cannot('nearata-gif-avatars.use-gifs')) {
            parent::upload($user, $image);

            return;
        }

        $path = $image->basePath();

        $this->gifsicle($path);

        $avatarPath = Str::random().'.gif';

        $this->removeFileAfterSave($user);
        $user->changeAvatarPath($avatarPath);

        $this->uploadDir->put($avatarPath, @file_get_contents($path));
    }

    private function gifsicle(string $path): void
    {
        $process = Process::fromShellCommandline('gifsicle --version');
        $process->run();

        if (! $process->isSuccessful()) {
            return;
        }

        $process = Process::fromShellCommandline('gifsicle --resize-fit 100x100 "${:path1}" -o "${:path2}"');
        $process->run(null, [
            'path1' => $path,
            'path2' => $path,
        ]);

        if (! $process->isSuccessful()) {
            $this->logger->warning('[nearata/flarum-ext-gif-avatars] :: '.$process->getErrorOutput());

            return;
        }
    }
}