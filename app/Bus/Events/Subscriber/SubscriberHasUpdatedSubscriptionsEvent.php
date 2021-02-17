<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Bus\Events\Subscriber;

use CachetHQ\Cachet\Models\Subscriber;

/**
 * This is the subscriber has updated subscriptions event.
 *
 * @author James Brooks <james@alt-three.com>
 */
final class SubscriberHasUpdatedSubscriptionsEvent implements SubscriberEventInterface
{
    /**
     * The subscriber.
     *
     * @var \CachetHQ\Cachet\Models\Subscriber|\CachetHQ\Cachet\Models\SpEmployees
     */
    public $subscriber;

    /**
     * Create a new subscriber has updated subscriptions event instance.
     *
     * @param \CachetHQ\Cachet\Models\Subscriber|\CachetHQ\Cachet\Models\SpEmployees $subscriber
     *
     * @return void
     */
    public function __construct($subscriber)
    {
        $this->subscriber = $subscriber;
    }

    /**
     * Get the event description.
     *
     * @return string
     */
    public function __toString()
    {
        return 'Subscriber has updated subscription.';
    }
}
