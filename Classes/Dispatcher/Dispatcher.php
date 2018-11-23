<?php
declare(strict_types = 1);
namespace Bitmotion\MarketingAutomation\Dispatcher;

use Bitmotion\MarketingAutomation\Persona\Persona;
use Bitmotion\MarketingAutomation\Storage\Cookie;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Dispatcher implements SingletonInterface
{
    /**
     * @var array
     */
    protected $subscribers = [];

    /**
     * @var array
     */
    protected $listeners = [];

    public function addSubscriber(string $className)
    {
        $this->subscribers[] = $className;
    }

    public function addListener(string $className)
    {
        $this->listeners[] = $className;
    }

    public function dispatch()
    {
        $extensionConfiguration = $this->getExtensionConfiguration();

        $storage = GeneralUtility::makeInstance(Cookie::class, $extensionConfiguration['cookieName'], (int)$extensionConfiguration['cookieLifetime']);
        $data = $storage->read();

        $id = (int)($data[0] ?? 0);
        $language = (int)($data[1] ?? -1);

        $currentPersona = $newPersona = GeneralUtility::makeInstance(Persona::class, $id, $language);

        foreach ($this->subscribers as $subscriber) {
            $object = GeneralUtility::makeInstance($subscriber);
            if (!$object instanceof SubscriberInterface) {
                throw new \RuntimeException('Class ' . $subscriber . ' needs to implement Bitmotion\MarketingAutomation\SubscriberInterface', 1530273364);
            }

            if ($object->needsUpdate($currentPersona, $newPersona)) {
                $newPersona = $object->update($newPersona);
            }
        }

        if ($currentPersona !== $newPersona) {
            $storage->save(
                [
                    $newPersona->getId(),
                    $newPersona->getLanguage(),
                ]
            );
        }

        foreach ($this->listeners as $listener) {
            $ref = null;
            GeneralUtility::callUserFunction($listener, $newPersona, $ref);
        }
    }

    protected function getExtensionConfiguration(): array
    {
        if (class_exists(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)) {
            try {
                return GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('marketing_automation');
            } catch (\Exception $e) {
                return [];
            }
        } else {
            return unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['marketing_automation'], ['allowed_classes' => false]);
        }
    }
}
