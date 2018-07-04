<?php
declare(strict_types = 1);
namespace Bitmotion\MarketingAutomation\Cookie;

class Cookie
{
    /**
     * @var string
     */
    private $cookieName;

    /**
     * @var int
     */
    protected $personaId;

    /**
     * @var int
     */
    protected $lastModified;

    /**
     * @var array
     */
    protected $data;

    public static function createFromGlobals(string $cookieName): Cookie
    {
        $cookieInformation = json_decode(base64_decode($_COOKIE[$cookieName] ?? ''), true) ?: [];

        return new self(
            $cookieName,
            (int)($cookieInformation['persona'] ?? 0),
            (int)($cookieInformation['tstamp'] ?? 0),
            (array)($cookieInformation['data'] ?? [])
        );
    }

    public function __construct(string $cookieName, int $personaId, int $lastModified, array $data)
    {
        $this->cookieName = $cookieName;
        $this->personaId = $personaId;
        $this->lastModified = $lastModified;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getPersonaId(): int
    {
        return $this->personaId;
    }

    /**
     * @return int
     */
    public function getLastModified(): int
    {
        return $this->lastModified;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function withPersonaId(int $personaId): Cookie
    {
        $clonedObject = clone $this;
        $clonedObject->personaId = $personaId;

        return $clonedObject;
    }

    public function withData(array $data): Cookie
    {
        $clonedObject = clone $this;
        $clonedObject->data = array_merge($this->data, $data);

        return $clonedObject;
    }

    public function set(int $lifetime)
    {
        setcookie(
            $this->cookieName,
            base64_encode(json_encode(
                [
                    'persona' => $this->personaId,
                    'data' => $this->data,
                    'tstamp' => time(),
                ]
            )),
            time() + $lifetime,
            '/',
            '',
            false,
            true
        );
    }
}
