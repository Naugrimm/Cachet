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
use CachetHQ\Cachet\Services\Dates\DateFactory;
use GrahamCampbell\Binput\Facades\Binput;
use GuzzleHttp\Client;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use McCool\LaravelAutoPresenter\BasePresenter;

class ComponentPresenter extends BasePresenter implements Arrayable
{
    use TimestampsTrait;

    /**
     * Returns the override class name for theming.
     *
     * @return string
     */
    public function status_color()
    {
        switch ($this->wrappedObject->status) {
            case 0: return 'greys';
            case 1: return 'greens';
            case 2: return 'blues';
            case 3: return 'yellows';
            case 4: return 'reds';
        }
    }

    /**
     * Looks up the human readable version of the status.
     *
     * @return string
     */
    public function human_status()
    {
        return trans('cachet.components.status.'.$this->wrappedObject->status);
    }

    /**
     * Find all tag names for the component names.
     *
     * @return array
     */
    public function tags()
    {
        return $this->wrappedObject->tags->pluck('name', 'slug');
    }

    /**
     * Present formatted date time.
     *
     * @return string
     */
    public function updated_at_formatted()
    {
        return ucfirst(app(DateFactory::class)->make($this->wrappedObject->updated_at)->format($this->incidentDateFormat()));
    }

    /**
     * Convert the presenter instance to an array.
     *
     * @return string[]
     */
    public function toArray()
    {
        return array_merge($this->wrappedObject->toArray(), [
            'created_at'  => $this->created_at(),
            'updated_at'  => $this->updated_at(),
            'status_name' => $this->human_status(),
            'tags'        => $this->tags(),
        ]);
    }

    public function toBadgeResponse(Collection $overwriteParams = null)
    {
        switch ($this->status_color()) {
            case 'reds': $color = Config::get('setting.style_reds', '#FF6F6F'); break;
            case 'blues': $color = Config::get('setting.style_blues', '#3498DB'); break;
            case 'greens': $color = Config::get('setting.style_greens', '#7ED321'); break;
            case 'yellows': $color = Config::get('setting.style_yellows', '#F7CA18'); break;
            default: $color = null;
        }

        $badge = Badger::generate(
            $overwriteParams->get('label', $this->wrappedObject->name),
            $overwriteParams->get('message', $this->human_status()),
            $overwriteParams->get('color', substr($color, 1)),
            $overwriteParams->get('style', 'flat-square')
        );

        return response($badge, 200, ['Content-Type' => 'image/svg+xml']);
    }
}
