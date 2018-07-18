<?php
declare(strict_types = 1);
namespace Bitmotion\MarketingAutomation\Slot;

use Bitmotion\MarketingAutomation\Cookie\Cookie;
use Bitmotion\MarketingAutomation\Cookie\SubscriberInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class LanguageSubscriber implements SubscriberInterface
{
    /**
     * @var TypoScriptFrontendController
     */
    protected $typoScriptFrontendController;

    public function __construct(TypoScriptFrontendController $typoScriptFrontendController)
    {
        $this->typoScriptFrontendController = $typoScriptFrontendController;
    }

    public function needsUpdate(Cookie $oldCookie, Cookie $newCookie): bool
    {
        $language = $newCookie->getLanguage();

        return $this->typoScriptFrontendController->sys_language_uid !== $language;
    }

    public function update(Cookie $cookie): Cookie
    {
        return $cookie->withLanguage($this->typoScriptFrontendController->sys_language_uid);
    }
}
