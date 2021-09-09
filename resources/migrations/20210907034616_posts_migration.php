<?php

use Phoenix\Migration\AbstractMigration;

class PostsMigration extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('posts')
            ->addColumn('id', 'uuid', ['null' => false])
            ->addColumn('title', 'string', ['length' => 50, 'null' => false])
            ->addColumn('content', 'text', ['null' => true])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->addIndex(['id'], 'unique', '', 'id_index')
            ->create();
    }

    protected function down(): void
    {
        $this->table('posts')
            ->drop();
    }
}
