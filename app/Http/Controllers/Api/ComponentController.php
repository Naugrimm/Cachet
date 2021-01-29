<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Http\Controllers\Api;

use CachetHQ\Cachet\Bus\Commands\Component\CreateComponentCommand;
use CachetHQ\Cachet\Bus\Commands\Component\RemoveComponentCommand;
use CachetHQ\Cachet\Bus\Commands\Component\UpdateComponentCommand;
use CachetHQ\Cachet\Models\Component;
use GrahamCampbell\Binput\Facades\Binput;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Request;
use McCool\LaravelAutoPresenter\Facades\AutoPresenter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ComponentController extends AbstractApiController
{
    /**
     * Get all components.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (app(Guard::class)->check()) {
            $components = Component::query();
        } else {
            $components = Component::enabled();
        }

        if ($tags = Binput::get('tags')) {
            $components->withAnyTags($tags);
        }

        $components->search(Binput::except(['sort', 'order', 'per_page']));

        if ($sortBy = Binput::get('sort')) {
            $direction = Binput::has('order') && Binput::get('order') == 'desc';

            $components->sort($sortBy, $direction);
        }

        $components = $components->paginate(Binput::get('per_page', 20));

        return $this->paginator($components, Request::instance());
    }

    public function showBadgeForAll()
    {
        if (app(Guard::class)->check()) {
            $components = Component::query();
        } else {
            $components = Component::enabled();
        }

        if ($tags = Binput::get('tags')) {
            $components->withAnyTags($tags);
        }

        $components->lowest();

        $components->search(
            Binput::except([
                'sort', 'order', 'per_page',
                'color', 'label', 'link', 'labelColor', 'logo','style'
            ])
        );

        $baseParams = collect([
            'label' => setting('app_name', config('app.name'))
        ]);

        $overwriteParams = collect(
            Binput::only([
                'color', 'label', 'link', 'labelColor', 'logo','style'
            ])
        )
            ->filter();

        $overwriteParams = $baseParams->merge($overwriteParams);

        return AutoPresenter::decorate($components->first())->toBadgeResponse($overwriteParams);
    }

    /**
     * Get a single component.
     *
     * @param \CachetHQ\Cachet\Models\Component $component
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Component $component)
    {
        return $this->item($component);
    }

    /**
     * Retrieves the badge for this component from a shields.io server
     *
     * @param Component $component
     * @return mixed
     */
    public function showBadge(Component $component)
    {
        $overwriteParams = collect(
            Binput::only([
                'color', 'label', 'link', 'labelColor', 'logo','style'
            ])
            )
            ->filter();

        return AutoPresenter::decorate($component)->toBadgeResponse($overwriteParams);
    }

    /**
     * Create a new component.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        try {
            $component = execute(new CreateComponentCommand(
                Binput::get('name'),
                Binput::get('description'),
                Binput::get('status'),
                Binput::get('link'),
                Binput::get('order'),
                Binput::get('group_id'),
                null,
                (bool) Binput::get('enabled', true),
                Binput::get('meta'),
                Binput::get('tags')
            ));
        } catch (QueryException $e) {
            throw new BadRequestHttpException();
        }

        return $this->item($component);
    }

    /**
     * Update an existing component.
     *
     * @param \CachetHQ\Cachet\Models\Component $component
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Component $component)
    {
        try {
            execute(new UpdateComponentCommand(
                $component,
                Binput::get('name'),
                Binput::get('description'),
                Binput::get('status'),
                true,
                Binput::get('link'),
                Binput::get('order'),
                Binput::get('group_id'),
                null,
                Binput::get('enabled', $component->enabled),
                Binput::get('meta'),
                Binput::get('tags'),
                (bool) Binput::get('silent', false)
            ));
        } catch (QueryException $e) {
            throw new BadRequestHttpException();
        }

        return $this->item($component);
    }

    /**
     * Delete an existing component.
     *
     * @param \CachetHQ\Cachet\Models\Component $component
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Component $component)
    {
        execute(new RemoveComponentCommand($component));

        return $this->noContent();
    }
}
