<?php
declare(strict_types = 1);
namespace Bitmotion\MarketingAutomation\Dispatcher;

use Bitmotion\MarketingAutomation\Persona\Persona;

interface SubscriberInterface
{
    public function needsUpdate(Persona $currentPersona, Persona $newPersona): bool;

    public function update(Persona $persona): Persona;
}
