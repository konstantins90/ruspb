<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\PostComment;
use App\Form\PostCommentType;
use App\Form\PostType;
use App\Repository\CategoryRepository;
use App\Repository\PostCommentRepository;
use App\Repository\PostRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
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
        PostCommentRepository $commentRepository,
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

        $averageRatings = $this->getAverageRatingsForPagination($pagination, $commentRepository);

        return $this->render('post/index.html.twig', [
            'pagination' => $pagination,
            'averageRatings' => $averageRatings,
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
            $tokenId = $form->getConfig()->getOption('csrf_token_id') ?? $form->getName();
            $tokenValue = $data['post']['_token'] ?? null;
            if ($tokenValue && $this->isCsrfTokenValid($tokenId, $tokenValue)
                && isset($data['post']['category']['autocomplete'])) {
                $slugger = new AsciiSlugger();
                $categoryName = $data['post']['category']['autocomplete'];
                $slug = $slugger->slug($categoryName);

                if (ctype_digit((string) $categoryName)) {
                    $category = $categoryRepository->find((int) $categoryName);
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
            $post->setSlug($this->slugifyPost($post));

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

    #[Route('/{id}', name: 'app_post_show', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function show(
        Request $request,
        Post $post
    ): Response
    {
        return $this->redirectToRoute('app_post_show_slug', [
            'id' => $post->getId(),
            'slug' => $this->slugifyPost($post),
        ], Response::HTTP_MOVED_PERMANENTLY);
    }

    #[Route('/{id}-{slug}', name: 'app_post_show_slug', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function showSlug(
        Request $request,
        Post $post,
        string $slug,
        EntityManagerInterface $entityManager,
        PostCommentRepository $commentRepository
    ): Response
    {
        $expectedSlug = $this->slugifyPost($post);
        if ($slug !== $expectedSlug) {
            return $this->redirectToRoute('app_post_show_slug', [
                'id' => $post->getId(),
                'slug' => $expectedSlug,
            ], Response::HTTP_MOVED_PERMANENTLY);
        }

        $comment = new PostComment();
        $commentForm = $this->createForm(PostCommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setPost($post);
            $comment->setIsApproved(false);
            $comment->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Vielen Dank! Ваш комментарий будет опубликован после проверки.');

            return $this->redirectToRoute('app_post_show_slug', [
                'id' => $post->getId(),
                'slug' => $expectedSlug,
            ]);
        }

        $approvedComments = $commentRepository->findApprovedForPost($post);

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'commentForm' => $commentForm,
            'comments' => $approvedComments,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Post $post,
        EntityManagerInterface $entityManager,
        FileUploader $fileUploader
    ): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug($this->slugifyPost($post));
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

    private function slugifyPost(Post $post): string
    {
        $slugger = new AsciiSlugger();
        return strtolower((string) $slugger->slug((string) $post->getName()));
    }

    /**
     * @param PaginationInterface<Post> $pagination
     * @return array<int, array{average: float, count: int}>
     */
    private function getAverageRatingsForPagination(PaginationInterface $pagination, PostCommentRepository $commentRepository): array
    {
        $items = $pagination->getItems();
        if (!is_array($items)) {
            return [];
        }
        $ids = array_map(fn (Post $post) => $post->getId(), $items);
        return $commentRepository->getAverageRatingsForPosts($ids);
    }
}
