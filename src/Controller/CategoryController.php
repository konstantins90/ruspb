<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAllWithPosts(),
        ]);
    }

    #[Route('/{slug}', name: 'app_category_show', methods: ['GET'])]
    public function show(
        Category $category,
        PaginatorInterface $paginator,
        PostRepository $postRepository,
        Request $request
    ): Response
    {
        $pagination = $paginator->paginate(
            $postRepository->findByCategoryQuery($category->getId()),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'pagination' => $pagination
        ]);
    }
}
