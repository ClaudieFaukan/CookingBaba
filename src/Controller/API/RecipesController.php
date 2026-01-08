<?php
namespace App\Controller\API;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class RecipesController extends AbstractController
{
    #[Route('/api/recipes', name: 'api_recipes_index', methods: ['GET'])]
    public function index(Request $request, RecipeRepository $recipeRepository,SerializerInterface $serializer)
    {
        $recipes = $recipeRepository->paginate($request->query->getInt('page', 1));

        return $this->json($recipes,200,[],[
            'groups' => 'recipes.index'
        ]);
    }
    #[Route('/api/recipes/{id}', requirements: ['id' => Requirement::DIGITS], name: 'api_recipes_show', methods: ['GET'])]
    public function show(Recipe $recipe)
    {
       
        return $this->json($recipe,200,[],[
            'groups' => ['recipes.index', 'recipes.show']
        ]);
    }

}