<?php
declare(strict_types = 1);
namespace Bitmotion\MarketingAutomation\Persona;

class Persona
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $language;

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

    /**
     * @return Persona
     */
    public function withId(int $id): self
    {
        $clonedObject = clone $this;
        $clonedObject->id = $id;

        return $clonedObject;
    }

    /**
     * @return Persona
     */
    public function withLanguage(int $language): self
    {
        $clonedObject = clone $this;
        $clonedObject->language = $language;

        return $clonedObject;
    }
}
