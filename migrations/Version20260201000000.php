<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260201000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial schema: user, category, post, post_category';
    }

    public function up(Schema $schema): void
    {
        // user (reserved word in PostgreSQL – create via raw SQL with quoted name)
        $isPostgres = $this->connection->getDatabasePlatform()->getName() === 'postgresql';
        if ($isPostgres) {
            $this->addSql('CREATE TABLE "user" (id SERIAL PRIMARY KEY, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        } else {
            $user = $schema->createTable('user');
            $user->addColumn('id', 'integer', ['autoincrement' => true]);
            $user->addColumn('email', 'string', ['length' => 180]);
            $user->addColumn('roles', 'json');
            $user->addColumn('password', 'string', ['length' => 255]);
            $user->setPrimaryKey(['id']);
            $user->addUniqueIndex(['email'], 'UNIQ_8D93D649E7927C74');
        }

        // category
        $category = $schema->createTable('category');
        $category->addColumn('id', 'integer', ['autoincrement' => true]);
        $category->addColumn('name', 'string', ['length' => 255]);
        $category->addColumn('slug', 'string', ['length' => 255]);
        $category->addColumn('synonym', 'string', ['length' => 255, 'notnull' => false]);
        $category->addColumn('synonym_de', 'string', ['length' => 255, 'notnull' => false]);
        $category->addColumn('description', 'text', ['notnull' => false]);
        $category->addColumn('is_top', 'boolean', ['notnull' => false]);
        $category->setPrimaryKey(['id']);

        // post
        $post = $schema->createTable('post');
        $post->addColumn('id', 'integer', ['autoincrement' => true]);
        $post->addColumn('name', 'string', ['length' => 255]);
        $post->addColumn('subname', 'string', ['length' => 255, 'notnull' => false]);
        $post->addColumn('address', 'string', ['length' => 255]);
        $post->addColumn('postcode', 'string', ['length' => 5]);
        $post->addColumn('city', 'string', ['length' => 255]);
        $post->addColumn('description', 'text', ['notnull' => false]);
        $post->addColumn('status', 'smallint');
        $post->addColumn('small_image', 'string', ['length' => 255, 'notnull' => false]);
        $post->addColumn('image', 'string', ['length' => 255, 'notnull' => false]);
        $post->addColumn('email', 'string', ['length' => 255, 'notnull' => false]);
        $post->addColumn('phone', 'string', ['length' => 255, 'notnull' => false]);
        $post->addColumn('web', 'string', ['length' => 255, 'notnull' => false]);
        $post->setPrimaryKey(['id']);

        // post_category (ManyToMany join table)
        $postCategory = $schema->createTable('post_category');
        $postCategory->addColumn('post_id', 'integer');
        $postCategory->addColumn('category_id', 'integer');
        $postCategory->setPrimaryKey(['post_id', 'category_id']);
        $postCategory->addIndex(['category_id'], 'IDX_POST_CATEGORY_CATEGORY_ID');
        $postCategory->addForeignKeyConstraint('post', ['post_id'], ['id'], ['onDelete' => 'CASCADE']);
        $postCategory->addForeignKeyConstraint('category', ['category_id'], ['id'], ['onDelete' => 'CASCADE']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('post_category');
        $schema->dropTable('post');
        $schema->dropTable('category');
        if ($this->connection->getDatabasePlatform()->getName() === 'postgresql') {
            $this->addSql('DROP TABLE "user"');
        } else {
            $schema->dropTable('user');
        }
    }
}
