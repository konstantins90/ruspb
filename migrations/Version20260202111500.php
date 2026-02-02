<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260202111500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add slug to post for SEO-friendly URLs';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->getTable('post');
        $table->addColumn('slug', 'string', ['length' => 255, 'notnull' => false]);
        $table->addIndex(['slug'], 'IDX_POST_SLUG');
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('post');
        $table->dropIndex('IDX_POST_SLUG');
        $table->dropColumn('slug');
    }
}
