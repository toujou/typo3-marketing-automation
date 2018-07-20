<?php
defined('TYPO3_MODE') or die();

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['determineId-PreProcessing']['marketing_automation'] =
        \Bitmotion\MarketingAutomation\Dispatcher\Dispatcher::class . '->dispatch';

    $marketingDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Bitmotion\MarketingAutomation\Dispatcher\Dispatcher::class);
    $marketingDispatcher->addSubscriber(\Bitmotion\MarketingAutomation\Slot\LanguageSubscriber::class);
    $marketingDispatcher->addListener(\Bitmotion\MarketingAutomation\Persona\PersonaRestriction::class . '->fetchCurrentPersona');

    if (!isset($GLOBALS['TYPO3_CONF_VARS']['DB']['additionalQueryRestrictions'][\Bitmotion\MarketingAutomation\Persona\PersonaRestriction::class])) {
        $GLOBALS['TYPO3_CONF_VARS']['DB']['additionalQueryRestrictions'][\Bitmotion\MarketingAutomation\Persona\PersonaRestriction::class] = [];
    }

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['createHashBase'][\Bitmotion\MarketingAutomation\Persona\PersonaRestriction::class] = \Bitmotion\MarketingAutomation\Persona\PersonaRestriction::class . '->addPersonaToCacheIdentifier';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][\TYPO3\CMS\Core\Imaging\IconFactory::class]['overrideIconOverlay'][] = \Bitmotion\MarketingAutomation\Hook\BackendIconOverlayHook::class;

    $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
    $signalSlotDispatcher->connect(
        \TYPO3\CMS\Install\Service\SqlExpectedSchemaService::class,
        'tablesDefinitionIsBeingBuilt',
        \Bitmotion\MarketingAutomation\Persona\PersonaRestriction::class,
        'getPersonaFieldsRequiredDatabaseSchema'
    );
    $signalSlotDispatcher->connect(
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::class,
        'tcaIsBeingBuilt',
        \Bitmotion\MarketingAutomation\Persona\PersonaRestriction::class,
        'addPersonaRestrictionFieldToTca'
    );
});
