<?php
defined('TYPO3_MODE') or die();

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['settingLanguage_postProcess']['marketing_automation'] =
        \Bitmotion\MarketingAutomation\Cookie\Dispatcher::class . '->dispatch';
});
