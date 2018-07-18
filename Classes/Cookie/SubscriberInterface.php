<?php
declare(strict_types = 1);
namespace Bitmotion\MarketingAutomation\Cookie;

interface SubscriberInterface
{
    public function needsUpdate(Cookie $oldCookie, Cookie $newCookie): bool;

    public function update(Cookie $cookie): Cookie;
}
