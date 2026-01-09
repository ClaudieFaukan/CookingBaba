<?php

namespace App\Controller\API;

use App\DTO\PaginationDTO;
use Dom\Entity;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

class RecipesController extends AbstractController
{
    #[Route('/api/recipes', name: 'api_recipes_index', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository,
     #[MapQueryString]
     ?PaginationDTO $paginationDTO)
    {
        $recipes = $recipeRepository->paginate($paginationDTO?->page ?? 1);

        return $this->json($recipes, 200, [], [
            'groups' => 'recipes.index'
        ]);
    }

    #[Route('/api/recipes', name: 'api_recipes_create', methods: ['POST'])]
    public function create(
        Request $request,
        #[MapRequestPayload(acceptFormat: 'json', serializationContext: ['groups' => ['recipes.create']])]
        Recipe $recipe,
        EntityManagerInterface $em,
    ) {

        $recipe->setCreatedAt(new \DateTimeImmutable());
        $recipe->setUpdatedAt(new \DateTimeImmutable());

        $em->persist($recipe);
        $em->flush();

        return $this->json($recipe, 201, [], [
            'groups' => ['recipes.index', 'recipes.show']
        ]);
    }

    #[Route('/api/recipes/{id}', requirements: ['id' => Requirement::DIGITS], name: 'api_recipes_show', methods: ['GET'])]
    public function show(Recipe $recipe)
    {

        return $this->json($recipe, 200, [], [
            'groups' => ['recipes.index', 'recipes.show']
        ]);
    }
}
