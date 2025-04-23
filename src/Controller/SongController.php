<?php

namespace App\Controller;

use App\Entity\Song;
use App\Repository\SongRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SongController extends AbstractController
{
    #[Route('api/v1/songs', name: 'get_all_songs', methods: ['GET'])]
    public function index(SongRepository $songRepository, SerializerInterface $serializer): JsonResponse
    {
        $data = $songRepository->findAll(); 
        $jsonData = $serializer->serialize($data, 'json');
        return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
    }
    
    #[Route('api/v1/song/{id}', name: 'get_song', methods: ['GET'])]
    public function get(int $id, SongRepository $songRepository, SerializerInterface $serializer): JsonResponse
    {
        $song = $songRepository->find($id);

        if (!$song) {
            return new JsonResponse(['error' => 'Song not found'], Response::HTTP_NOT_FOUND);
        }

        $jsonData = $serializer->serialize($song, 'json');
        return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
    }

    #[Route('api/v1/song', name: 'create_song', methods: ['POST'])]
    public function create(Request $request, UrlGeneratorInterface $urlGenerator, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $song = $serializer->deserialize($request->getContent(), Song::class, 'json');
        $song->setName($song->getName() ?? 'Non Defini');
        $entityManager->persist($song);
        $entityManager->flush();
        
        $jsonData = $serializer->serialize($song, 'json');
        $location = $urlGenerator->generate('get_song', ['id' => $song->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonData, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    #[Route('api/v1/song/{id}', name: 'update_song', methods: ['PUT'])]
    public function update(int $id, Request $request, SongRepository $songRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $song = $songRepository->find($id);

        if (!$song) {
            return new JsonResponse(['error' => 'Song not found'], Response::HTTP_NOT_FOUND);
        }

        $updatedSong = $serializer->deserialize($request->getContent(), Song::class, 'json', ['object_to_populate' => $song]);
        $entityManager->flush();

        $jsonData = $serializer->serialize($updatedSong, 'json');
        return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
    }

    #[Route('api/v1/song/{id}', name: 'delete_song', methods: ['DELETE'])]
    public function delete(int $id, SongRepository $songRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $song = $songRepository->find($id);

        if (!$song) {
            return new JsonResponse(['error' => 'Song not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($song);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
