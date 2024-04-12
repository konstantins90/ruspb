<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        PostRepository $postRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $entityManager->getFilters()
            ->enable('status_filter');
        
        $pagination = $paginator->paginate(
            $postRepository->findAllQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('post/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        FileUploader $fileUploader,
        CategoryRepository $categoryRepository
    ): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            if (isset($data['post']['category']['autocomplete'])) {
                $slugger = new AsciiSlugger();
                $categoryName = $data['post']['category']['autocomplete'];
                $slug = $slugger->slug($categoryName);

                
                
                if (intval($categoryName) == $categoryName) {
                    $category = $categoryRepository->findOneBy(
                        ['id' => $slug]
                    );
                } else {
                    $category = $categoryRepository->findOneBy(
                        ['slug' => $slug]
                    );
                }
                
                if (!$category) {
                    $category = new Category();
                    $category->setName($categoryName);
                    $category->setSlug($slug);

                    $entityManager->persist($category);
                    $entityManager->flush();
                }

                $data['post']['category']['autocomplete'] = $category->getId();
                $request->request->set('post', $data['post']);
            }
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setStatus(0);

            $category = $form->get('category')->getData();
            if ($category) {
                $post->addCategory($category);
            }

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $post->setImage($imageFileName);
            }

            $smallImage = $form->get('small_image')->getData();
            if ($smallImage) {
                $smallImageFileName = $fileUploader->upload($smallImage);
                $post->setSmallImage($smallImageFileName);
            }

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        $errors = $form->getErrors(true);

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
            'errors' => $errors,
        ]);
    }

    #[Route('/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
