<?php
declare(strict_types = 1);
namespace Bitmotion\MarketingAutomation\Persona;

class Persona
{
    protected $id = 0;

    protected $language = 0;

    public function __construct(int $id, int $language)
    {
        $this->id = $id;
        $this->language = $language;
    }

    public function isValid(): bool
    {
        return $this->id !== 0;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLanguage(): int
    {
        return $this->language;
    }

    public function withId(int $id): self
    {
        $clonedObject = clone $this;
        $clonedObject->id = $id;

        return $clonedObject;
    }

    public function withLanguage(int $language): self
    {
        $clonedObject = clone $this;
        $clonedObject->language = $language;

        return $clonedObject;
    }
}
