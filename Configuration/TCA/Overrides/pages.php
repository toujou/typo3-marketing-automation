<?php
declare(strict_types = 1);

use Bitmotion\MarketingAutomation\Persona\PersonaRestriction;

$GLOBALS['TCA']['pages']['ctrl']['enablecolumns'][PersonaRestriction::PERSONA_ENABLE_FIELDS_KEY] = 'tx_marketingautomation_persona';
