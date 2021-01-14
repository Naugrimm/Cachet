<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Bus\Handlers\Events\Incident;

use CachetHQ\Cachet\Bus\Events\Incident\IncidentWasCreatedEvent;
use CachetHQ\Cachet\Integrations\Contracts\System;
use CachetHQ\Cachet\Models\AllowedGroups;
use CachetHQ\Cachet\Models\Subscriber;
use Illuminate\Database\Eloquent\Builder;
use CachetHQ\Cachet\Notifications\Incident\NewIncidentNotification;

class SendIncidentEmailNotificationHandler
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
     * @param \CachetHQ\Cachet\Bus\Events\Incident\IncidentWasCreatedEvent $event
     *
     * @return void
     */
    public function handle(IncidentWasCreatedEvent $event)
    {
        $incident = $event->incident;

        if (!$event->notify || !$this->system->canNotifySubscribers()) {
            return false;
        }

        // Only send emails for public incidents.
        if (!$incident->visible) {
            return;
        }

        $allowedSubscribers = collect();

        if($incident->component != null) {
                //komponente muss keiner gruppe zugeordnet sein, prüfen
                if($incident->component->user_groups_id != 0) {

                } else {
                    if($incident->user_groups_id == 0) {
                        $allowedSubscribers = Subscriber::whereHas('allowedGroups', function (Builder $query) use ($incident) {
                            $query->where('user_groups_id', '=', $incident->component->user_groups_id);
                        })->get();
                    } else {
                        $allowedSubscribers = Subscriber::whereHas('allowedGroups', function (Builder $query) use ($incident) {
                            $query->where('user_groups_id', '=', $incident->user_groups_id)
                                ->where('user_groups_id', '=', $incident->component->user_groups_id);
                        })->get();
                    }
                }
        } else {
            if ($incident->user_groups_id == 0) {
                $allowedSubscribers = Subscriber::get();
            } else {
                $allowedSubscribers = Subscriber::whereHas('allowedGroups', function (Builder $query) use($incident) {
                    $query->where('user_groups_id', '=', $incident->user_groups_id);
                })->get();
            }
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
