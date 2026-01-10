<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Recipe;
use DateTimeImmutable;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(private readonly SluggerInterface $slugger) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(('fr_FR'));
        $faker->addProvider(new Restaurant($faker));

        $categories = ['Entrée', 'Plat', 'Dessert', 'Apéritif', 'Boisson'];

        foreach ($categories as $categoryName) {

            $category = new Category();
            $category->setName($categoryName);
            $category->setSlug($this->slugger->slug($categoryName));
            $manager->persist($category);

            $this->addReference($categoryName, $category);
        }

        for ($i = 0; $i < 50; $i++) {

            $recipe = new Recipe();
            $name = $faker->foodName();
            $recipe->setTitle($name);
            $recipe->setSlug($this->slugger->slug($name));
            $recipe->setContent($faker->paragraphs(10, true));
            $recipe->setDuration($faker->numberBetween(10, 120));
            $recipe->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()));
            $recipe->setUpdatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()));
            $recipe->setUser($this->getReference('USER_' . $faker->numberBetween(0, 9)));

            $recipe->setCategory($this->getReference($faker->randomElement($categories)));

            $manager->persist($recipe);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
