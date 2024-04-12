<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategorySearchType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        CategoryRepository $categoryRepository
    ): Response
    {
        $form = $this->createForm(CategorySearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->get('category')->getData();
            return $this->redirectToRoute('app_category_show', ['slug' => $category->getSlug()]);
        }

        $topCategories = $categoryRepository->findForHome();
    
        return $this->render('home/index.html.twig', [
            'form' => $form,
            'topCategories' => $topCategories
        ]);
    }
}
