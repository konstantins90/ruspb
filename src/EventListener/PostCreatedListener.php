<?php
namespace App\EventListener;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Post::class)]
class PostCreatedListener
{
    private ChatterInterface $chatter;

    public function __construct(ChatterInterface $chatter)
    {
        $this->chatter = $chatter;
    }

    public function postPersist(Post $post, LifecycleEventArgs $event): void
    {
        $message = (new ChatMessage('New Post Created!'))
            ->transport('telegram')
            ->subject('Новый пост создан');

        $this->chatter->send($message);
    }
}
