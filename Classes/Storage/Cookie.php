<?php
declare(strict_types = 1);
namespace Bitmotion\MarketingAutomation\Storage;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3\CMS\Extbase\Security\Exception\InvalidArgumentForHashGenerationException;
use TYPO3\CMS\Extbase\Security\Exception\InvalidHashException;

class Cookie
{
    /**
     * @var string
     */
    protected $cookieName;

    /**
     * @var int
     */
    protected $cookieLifetime;

    /**
     * @var HashService
     */
    protected $hashService;

    public function __construct(string $cookieName, int $cookieLifetime, HashService $hashService = null)
    {
        $this->cookieName = $cookieName;
        $this->cookieLifetime = $cookieLifetime;

        $this->hashService = $hashService ?: GeneralUtility::makeInstance(HashService::class);
    }

    public function read(): array
    {
        try {
            $data = $this->hashService->validateAndStripHmac($_COOKIE[$this->cookieName] ?? '');
        } catch (InvalidArgumentForHashGenerationException $exception) {
            $data = '';
        } catch (InvalidHashException $exception) {
            $data = '';
        }

        return explode('.', rtrim($data, '.'));
    }

    public function save(array $data)
    {
        setcookie(
            $this->cookieName,
            $this->hashService->appendHmac(implode('.', $data) . '.'),
            time() + $this->cookieLifetime,
            '/',
            '',
            false,
            true
        );
    }
}
