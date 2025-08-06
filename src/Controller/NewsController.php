<?php

namespace App\Controller;

use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends AbstractController
{
    private $mediaManager;

    public function __construct(MediaManagerInterface $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    public function showHeroImage(int $mediaId, string $locale): Response
    {
        $media = $this->mediaManager->getById($mediaId, $locale);
        $url = $media->getUrl();

        return new Response('<img src="' . $url . '" alt="Hero Image">');
    }
}
