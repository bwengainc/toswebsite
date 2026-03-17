<?php

namespace App\Controller;

use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Manuxi\SuluNewsBundle\Repository\NewsRepository;
use Symfony\Component\HttpFoundation\Request;


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


    /**
     * Show recent posts (latest 5, excluding current one if provided)
     */
public function recent(NewsRepository $repository, Request $request, ?int $excludeId = null): Response
{
    $locale = $request->getLocale();

    $qb = $repository->createQueryBuilder('n')
        ->select('DISTINCT n')
        ->leftJoin('n.translations', 'translation')
        ->where('translation.locale = :locale')
        ->andWhere('translation.published = :published')
        ->andWhere('n.type != :excludedType') // exclude type announcement
        ->setParameter('locale', $locale)
        ->setParameter('published', true)
        ->setParameter('excludedType', 'announcement')
        ->orderBy('translation.authored', 'DESC')
        ->setMaxResults(5);

    if ($excludeId) {
        $qb->andWhere('n.id != :id')->setParameter('id', $excludeId);
    }

    $recentPosts = $qb->getQuery()->getResult();

    return $this->render('news/recent.html.twig', [
        'recentPosts' => $recentPosts,
    ]);
}






}
