<?php
declare(strict_types = 1);
namespace Bitmotion\MarketingAutomation\Slot;

use Bitmotion\MarketingAutomation\Dispatcher\SubscriberInterface;
use Bitmotion\MarketingAutomation\Persona\Persona;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class LanguageSubscriber implements SubscriberInterface
{
    /**
     * @var int
     */
    protected $languageId;

    public function __construct()
    {
        $this->languageId = (int)GeneralUtility::_GP('L');
    }

    public function needsUpdate(Persona $currentPersona, Persona $newPersona): bool
    {
        if (!$this->isValidLanguageId()) {
            $this->languageId = 0;
        }

        return $this->languageId !== $newPersona->getLanguage();
    }

    public function update(Persona $persona): Persona
    {
        return $persona->withLanguage($this->languageId);
    }

    protected function isValidLanguageId()
    {
        if ($this->languageId === 0) {
            return true;
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_language');

        $count = $queryBuilder->count('uid')
                ->from('sys_language')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($this->languageId, \PDO::PARAM_INT)
                    )
                )
                ->execute()
                ->fetchColumn();

        return $count === 1;
    }
}
