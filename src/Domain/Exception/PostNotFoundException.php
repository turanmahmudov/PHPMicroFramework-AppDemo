<?php

namespace App\Domain\Exception;

class PostNotFoundException extends RepositoryException
{
    public $code = 404;
    public $message = 'The post you requested does not exist.';
}