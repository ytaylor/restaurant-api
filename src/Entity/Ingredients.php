<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IngredientsRepository")
 */
class Ingredients
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * Many Ingredients have Many Allergens.
     * @ORM\ManyToMany(targetEntity="Allergens")
     * @ORM\JoinTable(name="ingredients_allergens",
     *      joinColumns={@ORM\JoinColumn(name="ingredients_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="allergens_id", referencedColumnName="id")}
     *      )
     */
    private $ingredientsAllergens;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

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
}
