<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class SlugService
{
    private EntityManagerInterface $entityManager;

    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    
    public function createUniqueSlug(string $string, string $entityClass, int $entityId = null): string
{
    
    $slug = $this->sanitize($string);
    
    
    if (empty($slug)) {
        $slug = 'undefined-slug';
    }

    
    $suffix = 1;
    while ($this->slugExists($slug, $entityClass, $entityId)) {
        
        $slug = $this->sanitize($string) . '-' . $suffix++;
    }

    
    return $slug;
}

    
    private function sanitize(string $string): string
    {
        
        $slug = preg_replace('/[^a-z0-9]+/i', '-', trim(strtolower($string)));
        
        return trim($slug, '-');
    }

    
    private function slugExists(string $slug, string $entityClass, int $entityId = null): bool
    {
       
        $repository = $this->entityManager->getRepository($entityClass);
        

        $entity = $repository->findOneBy(['slug' => $slug]);

    
        if (!$entity) {
            return false;
        }

        if (method_exists($entity, 'getId')) {
            return $entityId !== null && $entity->getId() !== $entityId;
        }

        throw new \Exception('The entity does not have a getId method.');
    }
}
