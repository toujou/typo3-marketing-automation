<?php
declare(strict_types = 1);

$GLOBALS['TCA']['tt_content']['ctrl']['enablecolumns'][\Bitmotion\MarketingAutomation\Persona\PersonaRestriction::PERSONA_ENABLE_FIELDS_KEY] = 'tx_marketingautomation_persona';
$GLOBALS['TCA']['pages']['ctrl']['enablecolumns'][\Bitmotion\MarketingAutomation\Persona\PersonaRestriction::PERSONA_ENABLE_FIELDS_KEY] = 'tx_marketingautomation_persona';
