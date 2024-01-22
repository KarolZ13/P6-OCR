<?php

namespace App\Service;

use App\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Picture;

class MediaTrick extends AbstractController
{

    // Récupération des information de/des image(s) ajoutée(s) dans la base de donnée puis stocker le/les fichier(s) dans le répertoire assets/img.
    public function handlePictures($pictures, $trick, $entityManager)
    {
        
        foreach ($pictures as $newPictureFile) {
            $newPicture = new Picture();
            $newPicture->setTrick($trick);
            $newPicture->setName($newPictureFile->getClientOriginalName());
            $trick->addPicture($newPicture);
    
            $entityManager->persist($newPicture);
            $newPictureFile->move('assets/img', $newPictureFile->getClientOriginalName());
        }
    }

    // Récupération des information de/des vidéo(s) ajoutée(s) dans la base de donnée
    public function handleVideos($videos, $trick, $entityManager)
        {
            foreach ($videos as $video) {
                $url = $video->getName();
        
                if (strpos($url, 'https://') === 0 && !$trick->getVideo()->contains($video)) {
                    $newVideo = new Video();
                    $newVideo->setName($url);
                    $trick->addVideo($newVideo);
                    $newVideo->setTrick($trick);
                    
                    $entityManager->persist($newVideo);
                }
            }
        }


        // Récupération de/des image(s)/vidéo(s) ajoutée(s) avec un compteur.
        public function getTrickMedia($collection, $limit, $isVideo = false)
        {
            $mediaNames = [];
            $count = 0;
        
            foreach ($collection as $media) {
                $mediaNames[] = $media->getName();
                $count++;
        
                if ($count >= $limit) {
                    break;
                }
            }
        
            return $isVideo ? $this->extractYouTubeVideoIds($mediaNames) : $mediaNames;
        }
        
        // Récupération de l'ID des vidéos Youtube 
        public function extractYouTubeVideoIds(array $videoUrls)
        {
            $videoIds = [];
        
            foreach ($videoUrls as $url) {
                $videoId = $this->extractYouTubeVideoId($url);
                if ($videoId) {
                    $videoIds[] = $videoId;
                }
            }
        
            return $videoIds;
        }


        // Récupération de l'ID d'une vidéo Youtube 
        public function extractYouTubeVideoId(string $url): ?string
        {
            $parsedUrl = parse_url($url);
            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $query);
                if (isset($query['v'])) {
                    return $query['v'];
                }
            }
    
            return null;
        }
    }