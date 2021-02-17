<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Bus\Commands\Subscriber;

use CachetHQ\Cachet\Models\Subscriber;

/**
 * This is the subscribe subscriber command.
 *
 * @author Joseph Cohen <joe@alt-three.com>
 */
final class UpdateSubscriberSubscriptionCommand
{
    /**
     * The subscriber email.
     *
     * @var \CachetHQ\Cachet\Models\Subscriber|\CachetHQ\Cachet\Models\SpEmployees
     */
    public $subscriber;

    /**
     * The subscriptions that we want to add.
     *
     * @var array|null
     */
    public $allowedGroups;

    /**
     * Create a new subscribe subscriber command instance.
     *
     * @param \CachetHQ\Cachet\Models\Subscriber|\CachetHQ\Cachet\Models\SpEmployees $subscriber
     * @param null|array                         $allowedGroups
     *
     * @return void
     */
    public function __construct($subscriber, $allowedGroups = null)
    {
        $this->subscriber = $subscriber;
        $this->allowedGroups = $allowedGroups;
    }
}
