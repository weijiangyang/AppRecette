<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function save(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findPublicRecipe(?int $nbRecipes,?Category $category): array
    {
       if($category){
            $query =  $this->createQueryBuilder('r')
                ->where("r.isPublic = 1")
                ->andWhere(":category MEMBER OF r.categories")
                ->setParameter("category", $category)
                ->orderBy('r.createdAt', 'DESC');
       }else{
            $query =  $this->createQueryBuilder('r')
                ->where("r.isPublic = 1")
                
                ->orderBy('r.createdAt', 'DESC');
       }
            
        if ($nbRecipes !== 0 && $nbRecipes !== null) {
            $query->setMaxResults($nbRecipes);
        }

        
        return $query->getQuery()
            ->getResult();
   
    }

    public function findSearcheRecipe(?string $content,?Category $category):array
    {
        $query = $this->createQueryBuilder('r')
            ->where("r.name LIKE :content ")
            ->andwhere("r.isPublic = 1")
            ->andWhere(":category MEMBER OF r.categories")
            ->setParameter('content', '%'.$content.'%')
            ->setParameter("category", $category)
            ->orderBy('r.createdAt', 'DESC');
        return $query->getQuery()
            ->getResult();
    }

//    /**
//     * @return Recipe[] Returns an array of Recipe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recipe
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
