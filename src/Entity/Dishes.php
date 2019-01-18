<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DishesRepository")
 */
class Dishes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * Many Dishes have Many Ingredients.
     * @ORM\ManyToMany(targetEntity="Ingredients")
     * @ORM\JoinTable(name="dishes_ingredients",
     *      joinColumns={@ORM\JoinColumn(name="dishes_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="ingredients_id", referencedColumnName="id")}
     *      )
     */
    private $dishesIngredients;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $calories;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCalories(): ?string
    {
        return $this->calories;
    }

    public function setCalories(string $calories): self
    {
        $this->calories = $calories;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function __construct()
    {
        $this->dishesIngredients = new ArrayCollection();
    }
    /**
     * Get ingredientos if dishes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIngredientsToDishes() {
        return  $this->dishesIngredients;
    }

    /**
     * Add ingredients to dishes
     *
     * @param Ingredients $ingredients
     *
     * @return Ingredients
     */
    public function addIngredientsToDishes(Ingredients $ingredients)
    {
        $this->dishesIngredients[] = $ingredients;
        return $this;
    }

    /**
     * Remove ingredients to dishes
     *
     * @param Ingredients $ingredients
     *
     * @return Ingredients
     */
    public function removeIngredientsToDishes(Ingredients $ingredients)
    {
        if (!$this->dishesIngredients->contains($ingredients)) {
            return;
        }
        $this->dishesIngredients->removeElement($ingredients);
    }
}
