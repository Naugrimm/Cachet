<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Bus\Handlers\Events\IncidentUpdate;

use CachetHQ\Cachet\Bus\Events\IncidentUpdate\IncidentUpdateWasReportedEvent;
use CachetHQ\Cachet\Integrations\Contracts\System;
use CachetHQ\Cachet\Models\Subscriber;
use CachetHQ\Cachet\Notifications\Incident\NewIncidentNotification;
use CachetHQ\Cachet\Notifications\IncidentUpdate\IncidentUpdatedNotification;
use Illuminate\Database\Eloquent\Builder;

class SendIncidentUpdateEmailNotificationHandler
{
    /**
     * The system instance.
     *
     * @var \CachetHQ\Cachet\Integrations\Contracts\System
     */
    protected $system;

    /**
     * The subscriber instance.
     *
     * @var \CachetHQ\Cachet\Models\Subscriber
     */
    protected $subscriber;

    /**
     * Create a new send incident email notification handler.
     *
     * @param \CachetHQ\Cachet\Integrations\Contracts\System $system
     * @param \CachetHQ\Cachet\Models\Subscriber             $subscriber
     *
     * @return void
     */
    public function __construct(System $system, Subscriber $subscriber)
    {
        $this->system = $system;
        $this->subscriber = $subscriber;
    }

    /**
     * Handle the event.
     *
     * @param \CachetHQ\Cachet\Bus\Events\IncidentUpdate\IncidentUpdateWasReportedEvent $event
     *
     * @return void
     */
    public function handle(IncidentUpdateWasReportedEvent $event)
    {
        $update = $event->update;
        $incident = $update->incident;

        // Only send emails for public incidents while the system is not under scheduled maintenance.
        if (!$incident->visible || !$this->system->canNotifySubscribers()) {
            return;
        }

        if ($incident->user_groups_id == 0) {
            $allowedSubscribers = Subscriber::get();
        } else {
            $allowedSubscribers = Subscriber::whereHas('allowedGroups', function (Builder $query) use($incident) {
                $query->where('user_groups_id', '=', $incident->user_groups_id);
            })->get();
        }

        // notify subscribers.
        $allowedSubscribers->each(function ($subscriber) use ($incident) {
            $subscriber->notify(new NewIncidentNotification($incident));
        });

        if (!$incident->component) {
            return;
        }
    }
}
