<?php
declare(strict_types = 1);
namespace Bitmotion\MarketingAutomation\Cookie;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class Dispatcher implements SingletonInterface
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
     * @var array
     */
    protected $subscribers = [];

    public function __construct(string $cookieName = null, int $cookieLifetime = null)
    {
        $extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['marketing_automation'], ['allowed_classes' => false]);

        $this->cookieName = $cookieName ?: $extensionConfiguration['cookieName'];
        $this->cookieLifetime = $cookieLifetime ?: (int)$extensionConfiguration['lifetime'];
    }

    public function addSubscriber(string $className)
    {
        $this->subscribers[] = $className;
    }

    public function dispatch(array $_, TypoScriptFrontendController $typoScriptFrontendController)
    {
        $oldCookieObject = $newCookieObject = Cookie::createFromGlobals($this->cookieName);

        foreach ($this->subscribers as $subscriber) {
            $object = GeneralUtility::makeInstance($subscriber, $typoScriptFrontendController);
            if (!$object instanceof SubscriberInterface) {
                throw new \RuntimeException('Class ' . $subscriber . ' needs to implement Bitmotion\MarketingAutomation\SubscriberInterface', 1530273364);
            }

            if ($object->needsUpdate($oldCookieObject, $newCookieObject)) {
                $newCookieObject = $object->update($newCookieObject);
            }
        }

        if ($oldCookieObject !== $newCookieObject) {
            $newCookieObject->set($this->cookieLifetime);
        }
    }
}
