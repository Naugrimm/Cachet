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

use CachetHQ\Cachet\Presenters\Traits\TimestampsTrait;
use CachetHQ\Cachet\Services\Dates\DateFactory;
use GuzzleHttp\Client;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
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
        $client = new Client([
            'base_uri' => config('badges.base_uri').'/static/v1'
        ]);

        $params = collect([
            'label' => $this->wrappedObject->name,
            'message' => $this->human_status(),
            'color' => Str::singular($this->status_color())
        ])->merge($overwriteParams);

        $response = $client->get('', [
            'query' => $params->toArray(),
        ]);

        return response($response->getBody(), $response->getStatusCode(), $response->getHeaders());
    }
}
