<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class PostsMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $posts = $this->table('posts', ['id' => false]);
        $posts
            ->addColumn('id', 'uuid', ['null' => false])
            ->addColumn('title', 'string', ['limit' => 50, 'null' => false])
            ->addColumn('content', 'text', ['null' => true])
            ->addTimestamps()
            ->addIndex(['id'], ['unique' => true, 'name' => 'id_index']);
        $posts->create();
    }
}

