<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Presenters;

use AltThree\Badger\Facades\Badger;
use CachetHQ\Cachet\Presenters\Traits\TimestampsTrait;
use GuzzleHttp\Client;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use McCool\LaravelAutoPresenter\BasePresenter;
use McCool\LaravelAutoPresenter\Facades\AutoPresenter;

class ComponentGroupPresenter extends BasePresenter implements Arrayable
{
    use TimestampsTrait;

    /**
     * Flag for the enabled_components_lowest function.
     *
     * @var bool
     */
    protected $enabledComponentsLowest = false;

    /**
     * Returns the lowest component status.
     *
     * @return string|null
     */
    public function lowest_status()
    {
        if ($component = $this->enabled_components_lowest()) {
            return AutoPresenter::decorate($component)->status;
        }
    }

    /**
     * Returns the lowest component status, readable by humans.
     *
     * @return string|null
     */
    public function lowest_human_status()
    {
        if ($component = $this->enabled_components_lowest()) {
            return AutoPresenter::decorate($component)->human_status;
        }
    }

    /**
     * Returns the lowest component status color.
     *
     * @return string|null
     */
    public function lowest_status_color()
    {
        if ($component = $this->enabled_components_lowest()) {
            return AutoPresenter::decorate($component)->status_color;
        }
    }

    /**
     * Return the enabled components from the wrapped object, and cache it if need be.
     *
     * @return bool
     */
    public function enabled_components_lowest()
    {
        if (is_bool($this->enabledComponentsLowest)) {
            $this->enabledComponentsLowest = $this->wrappedObject->enabled_components_lowest()->first();
        }

        return $this->enabledComponentsLowest;
    }

    /**
     * Determine the class for collapsed/uncollapsed groups.
     *
     * @return string
     */
    public function collapse_class()
    {
        return $this->is_collapsed() ? 'ion-ios-plus-outline' : 'ion-ios-minus-outline';
    }

    /**
     * Determine if the group should be collapsed.
     *
     * @return bool
     */
    public function is_collapsed()
    {
        if ($this->wrappedObject->collapsed === 0) {
            return false;
        } elseif ($this->wrappedObject->collapsed === 1) {
            return true;
        }

        return $this->wrappedObject->components->filter(function ($component) {
            return $component->status > 1;
        })->isEmpty();
    }

    /**
     * Convert the presenter instance to an array.
     *
     * @return string[]
     */
    public function toArray()
    {
        return array_merge($this->wrappedObject->toArray(), [
            'created_at'          => $this->created_at(),
            'updated_at'          => $this->updated_at(),
            'lowest_human_status' => $this->lowest_human_status(),
        ]);
    }

    /**
     * Determine if any of the contained components have active subscriptions.
     *
     * @return bool
     */
    public function has_subscriber($subscriptions)
    {
        $enabled_components = $this->wrappedObject->enabled_components()->orderBy('order')->pluck('id')->toArray();
        $intersected = array_intersect($enabled_components, $subscriptions);

        return count($intersected) != 0;
    }

    /**
     * Determine the class for collapsed/uncollapsed groups on the subscription form.
     *
     * @return string
     */
    public function collapse_class_with_subscriptions($subscriptions)
    {
        return $this->has_subscriber($subscriptions) ? 'ion-ios-minus-outline' : 'ion-ios-plus-outline';
    }

    public function toBadgeResponse(Collection $overwriteParams = null)
    {
        switch ($this->lowest_status_color()) {
            case 'reds': $color = Config::get('setting.style_reds', '#FF6F6F'); break;
            case 'blues': $color = Config::get('setting.style_blues', '#3498DB'); break;
            case 'greens': $color = Config::get('setting.style_greens', '#7ED321'); break;
            case 'yellows': $color = Config::get('setting.style_yellows', '#F7CA18'); break;
            default: $color = null;
        }

        $badge = Badger::generate(
            $overwriteParams->get('label', $this->wrappedObject->name),
            $overwriteParams->get('message', $this->lowest_human_status()),
            $overwriteParams->get('color', substr($color, 1)),
            $overwriteParams->get('style', 'flat-square')
        );

        return response($badge, 200, ['Content-Type' => 'image/svg+xml']);
    }
}
