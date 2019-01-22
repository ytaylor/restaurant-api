<?php

namespace App\DataFixtures;

use App\Entity\Allergens;
use App\Entity\Ingredients;
use App\Entity\Dishes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\ArrayCollection;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {


        //Insert allergens
        for ($i = 1; $i < 200; $i++) {
                $allergen = new  Allergens();
                $allergen->setName("Allergen".$i);
                $this->addReference('allergen'.$i, $allergen);
                $manager->persist($allergen);
                $manager->flush();
        }

        //Insert ingredients
        for ($j = 1; $j < 50; $j++) {
            $ingredient = new Ingredients();
            $ingredient->setName("Ingredient" . $j);
            for ($k = 1; $k < 10; $k++) {
                $allergen = $this->getReference('allergen'.$k);
                $ingredient->addAllergenToIngredient($allergen);
            }
            $this->addReference('ingredient'.$j, $ingredient);
            $manager->persist($ingredient);
            $manager->flush();
        }

        //Insert dishes
        for ($i = 1; $i < 100; $i++) {
            $dishes = new Dishes();
            $dishes->setName('dishes ' . $i);
            $dishes->setPrice(mt_rand(10, 100));
            $dishes->setDescription('description' . $i);
            $dishes->setCalories('calories' . $i);

            for ($j = 1; $j < 10; $j++) {
                $ingredient =  $this->getReference('ingredient'.$j);
                $dishes->addIngredientsToDishes($ingredient);
            }
            $manager->persist($dishes);
            $manager->flush();
        }
    }
}
