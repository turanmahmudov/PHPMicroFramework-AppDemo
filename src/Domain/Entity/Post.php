<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use DateTime;
use JsonSerializable;

class Post implements JsonSerializable
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string
     */
    protected string $title;

    /**
     * @var string
     */
    protected string $content;

    /**
     * @var DateTime
     */
    protected DateTime $createdAt;

    /**
     * @var DateTime
     */
    protected DateTime $updatedAt;

    /**
     * @param string $id
     * @param string $title
     * @param string $content
     */
    public function __construct(
        string $id = '',
        string $title = '',
        string $content = ''
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}