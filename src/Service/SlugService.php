<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class SlugService
{
    private EntityManagerInterface $entityManager;

    // Constructeur de la classe, injection de l'EntityManager
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Méthode pour créer un slug unique à partir d'une chaîne
    public function createUniqueSlug(string $string, string $entityClass, int $entityId = null): string
{
    // Nettoie la chaîne et la convertit en un slug de base
    $slug = $this->sanitize($string);
    
    // Vérifie si le slug de base est vide, s'il l'est, utilise un slug par défaut
    if (empty($slug)) {
        $slug = 'undefined-slug';
    }

    // Suffixe pour rendre le slug unique
    $suffix = 1;
    while ($this->slugExists($slug, $entityClass, $entityId)) {
        // Ajoute un numéro de suffixe au slug et vérifie à nouveau s'il est unique
        $slug = $this->sanitize($string) . '-' . $suffix++;
    }

    // Retourne le slug unique généré
    return $slug;
}

    // Méthode pour nettoyer une chaîne et la transformer en slug
    private function sanitize(string $string): string
    {
        // Remplace les espaces et les caractères non alphanumériques par des tirets
        $slug = preg_replace('/[^a-z0-9]+/i', '-', trim(strtolower($string)));
        
        // Supprime les tirets au début et à la fin du slug
        return trim($slug, '-');
    }

    // Méthode pour vérifier si un slug existe déjà dans une entité
    private function slugExists(string $slug, string $entityClass, int $entityId = null): bool
    {
        // Récupère le référentiel de l'entité spécifiée
        $repository = $this->entityManager->getRepository($entityClass);
        
        // Recherche une entité ayant le même slug
        $entity = $repository->findOneBy(['slug' => $slug]);

        // Si aucune entité n'est trouvée, le slug est unique
        if (!$entity) {
            return false;
        }

        // Si l'entité a une méthode getId, vérifie si l'entité a un ID différent de celui spécifié
        if (method_exists($entity, 'getId')) {
            return $entityId !== null && $entity->getId() !== $entityId;
        }

        // Si l'entité ne possède pas de méthode getId, génère une exception
        throw new \Exception('The entity does not have a getId method.');
    }
}
