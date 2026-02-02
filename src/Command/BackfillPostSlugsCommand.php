<?php

namespace App\Command;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[AsCommand(
    name: 'app:posts:backfill-slugs',
    description: 'Generate missing slugs for existing posts.'
)]
class BackfillPostSlugsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PostRepository $postRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $slugger = new AsciiSlugger();
        $posts = $this->postRepository->findAll();
        $updated = 0;

        foreach ($posts as $post) {
            if (!$post instanceof Post) {
                continue;
            }

            if ($post->getSlug()) {
                continue;
            }

            $baseSlug = strtolower((string) $slugger->slug((string) $post->getName()));
            if ($baseSlug === '') {
                $baseSlug = 'post';
            }

            $slug = $baseSlug;
            $existing = $this->postRepository->findOneBy(['slug' => $slug]);
            if ($existing && $existing->getId() !== $post->getId()) {
                $slug = $baseSlug . '-' . $post->getId();
            }

            $post->setSlug($slug);
            $updated++;
        }

        if ($updated > 0) {
            $this->entityManager->flush();
        }

        $output->writeln(sprintf('Slugs updated: %d', $updated));

        return Command::SUCCESS;
    }
}
