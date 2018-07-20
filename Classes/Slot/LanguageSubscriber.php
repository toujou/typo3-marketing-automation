<?php
declare(strict_types = 1);
namespace Bitmotion\MarketingAutomation\Slot;

use Bitmotion\MarketingAutomation\Dispatcher\SubscriberInterface;
use Bitmotion\MarketingAutomation\Persona\Persona;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class LanguageSubscriber implements SubscriberInterface
{
    /**
     * @var TypoScriptFrontendController
     */
    protected $typoScriptFrontendController;

    public function __construct(TypoScriptFrontendController $typoScriptFrontendController = null)
    {
        $this->typoScriptFrontendController = $typoScriptFrontendController ?: $GLOBALS['TSFE'];
    }

    public function needsUpdate(Persona $currentPersona, Persona $newPersona): bool
    {
        $language = $newPersona->getLanguage();

        return $this->typoScriptFrontendController->sys_language_uid !== $language;
    }

    public function update(Persona $persona): Persona
    {
        return $persona->withLanguage($this->typoScriptFrontendController->sys_language_uid);
    }
}
