<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260202103000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add post_comment table for ratings and comments';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('post_comment');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('post_id', 'integer');
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('rating', 'smallint');
        $table->addColumn('message', 'text');
        $table->addColumn('is_approved', 'boolean', ['default' => false]);
        $table->addColumn('created_at', 'datetime_immutable');
        $table->setPrimaryKey(['id']);
        $table->addIndex(['post_id'], 'IDX_POST_COMMENT_POST');
        $table->addIndex(['is_approved'], 'IDX_POST_COMMENT_APPROVED');
        $table->addForeignKeyConstraint('post', ['post_id'], ['id'], ['onDelete' => 'CASCADE']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('post_comment');
    }
}
