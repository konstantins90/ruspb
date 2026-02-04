<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostComment>
 *
 * @method PostComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostComment[]    findAll()
 * @method PostComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostComment::class);
    }

    /**
     * @return PostComment[]
     */
    public function findApprovedForPost(Post $post): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.post = :post')
            ->andWhere('c.isApproved = :approved')
            ->setParameter('post', $post)
            ->setParameter('approved', true)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Durchschnittsbewertung und Anzahl (nur genehmigte) pro Post.
     *
     * @param int[] $postIds
     * @return array<int, array{average: float, count: int}>
     */
    public function getAverageRatingsForPosts(array $postIds): array
    {
        if ($postIds === []) {
            return [];
        }

        $conn = $this->getEntityManager()->getConnection();
        $ids = implode(',', array_map('intval', $postIds));
        $sql = "SELECT post_id, AVG(rating) AS average, COUNT(*) AS count
                FROM post_comment
                WHERE post_id IN ($ids) AND is_approved = true
                GROUP BY post_id";
        $result = $conn->executeQuery($sql)->fetchAllAssociative();

        $map = [];
        foreach ($postIds as $id) {
            $map[$id] = ['average' => 0.0, 'count' => 0];
        }
        foreach ($result as $row) {
            $map[(int) $row['post_id']] = [
                'average' => (float) $row['average'],
                'count' => (int) $row['count'],
            ];
        }

        return $map;
    }
}
