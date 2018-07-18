<?php
declare(strict_types = 1);
namespace Bitmotion\MarketingAutomation\Cookie;

use TYPO3\CMS\Core\Utility\GeneralUtility;

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
    protected $language;

    public static function createFromGlobals(string $cookieName): Cookie
    {
        $cookieInformation = GeneralUtility::trimExplode('.', $_COOKIE[$cookieName] ?? '');

        return new self(
            $cookieName,
            (int)($cookieInformation[0] ?? 0),
            (int)($cookieInformation[1] ?? -1)
        );
    }

    public function __construct(string $cookieName, int $personaId, int $language)
    {
        $this->cookieName = $cookieName;
        $this->personaId = $personaId;
        $this->language = $language;
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
    public function getLanguage(): int
    {
        return $this->language;
    }

    public function withPersonaId(int $personaId): Cookie
    {
        $clonedObject = clone $this;
        $clonedObject->personaId = $personaId;

        return $clonedObject;
    }

    public function withLanguage(int $language): Cookie
    {
        $clonedObject = clone $this;
        $clonedObject->language = $language;

        return $clonedObject;
    }

    public function set(int $lifetime)
    {
        setcookie(
            $this->cookieName,
            implode(
                '.',
                [
                    $this->personaId,
                    $this->language,
                ]
            ),
            time() + $lifetime,
            '/',
            '',
            false,
            true
        );
    }
}
