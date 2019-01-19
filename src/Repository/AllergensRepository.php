<?php

namespace App\Repository;

use App\Entity\Allergens;
use App\Entity\Dishes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Allergens|null find($id, $lockMode = null, $lockVersion = null)
 * @method Allergens|null findOneBy(array $criteria, array $orderBy = null)
 * @method Allergens[]    findAll()
 * @method Allergens[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllergensRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Allergens::class);
    }

    /**
     * @return Dishes[] Returns an array of Dishes objects
     */

    public function findDishesByAllergen($allergen)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        select distinct dishes.name 
from dishes
inner join dishes_ingredients on dishes.id = dishes_ingredients.dishes_id 
inner join ingredients on dishes_ingredients.ingredients_id = ingredients.id
inner join ingredients_allergens on ingredients_allergens.ingredients_id = ingredients.id
inner join allergens on ingredients_allergens.allergens_id = allergens.id
where allergens.name = :allergen';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['allergen' => $allergen]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    /*
    public function findOneBySomeField($value): ?Allergens
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
