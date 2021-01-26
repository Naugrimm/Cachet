<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Bus\Handlers\Events\Schedule;

use CachetHQ\Cachet\Bus\Events\Schedule\ScheduleEventInterface;
use CachetHQ\Cachet\Models\Setting;
use CachetHQ\Cachet\Models\Subscriber;
use CachetHQ\Cachet\Notifications\Schedule\NewScheduleNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use CachetHQ\Cachet\Presenters\Traits\TimestampsTrait;
use CachetHQ\Cachet\Services\Dates\DateFactory;

/**
 * This is the send schedule event notification handler.
 *
 * @author James Brooks <james@alt-three.com>
 */
class SendScheduleEmailNotificationHandler
{
    use TimestampsTrait;

    /**
     * The subscriber instance.
     *
     * @var \CachetHQ\Cachet\Models\Subscriber
     */
    protected $subscriber;

    /**
     * Create a new send schedule email notification handler.
     *
     * @param \CachetHQ\Cachet\Models\Subscriber $subscriber
     *
     * @return void
     */
    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    /**
     * Handle the event.
     *
     * @param \CachetHQ\Cachet\Bus\Events\Schedule\ScheduleEventInterface $event
     *
     * @return void
     */
    public function handle(ScheduleEventInterface $event)
    {
        $schedule = $event->schedule;
        if (!$event->notify) {
            return false;
        }

        $dateFactory = new DateFactory(config('app.timezone'), Setting::where('name', '=', 'app_timezone')->first()->value);
        $scheduleDate = ucfirst($dateFactory->make($schedule->scheduled_at)->format($this->incidentDateFormat()));

        // First notify all global subscribers.
        $globalSubscribers = $this->subscriber->isVerified()->isGlobal()->get()->each(function ($subscriber) use ($schedule, $scheduleDate) {
            //$subscriber->notify(new NewScheduleNotification($schedule));
            $this->subscriber = $subscriber;

            $content = trans('notifications.schedule.new.mail.content', [
                'name' => $schedule->name,
                'date' => $scheduleDate
            ]);

            $data = [
                'content' => $content,
                'unsubscribe_text'        => trans('cachet.subscriber.unsubscribe'),
                'unsubscribe_url'         => cachet_route('subscribe.unsubscribe', $subscriber->verify_code),
                'manage_text' => trans('cachet.subscriber.manage_subscription'),
                'manage_url'  => cachet_route('subscribe.manage', $subscriber->verify_code),
                'year' => Carbon::now()->year,
                'app_name' => Setting::where('name', '=', 'app_name')->first()->value,
                'app_url' => Setting::where('name', '=', 'app_domain')->first()->value
            ];

            if (!config('mail.from.address')) {
                $url = parse_url(env('APP_URL'));

                if (isset($url['host'])) {
                    config(['mail.from.address' => "notify@{$url['host']}"]);
                }
            }

            Mail::send('notifications.schedule.newschedules', $data, function ($message) {
                $message
                    ->to($this->subscriber->email)
                    ->subject(trans('notifications.schedule.new.mail.subject'));
                $message->from(config('mail.from.address'), config('mail.from.name'));
            });
        });
    }
}
