<?php

use Codedor\FilamentResourcePicker\Livewire\ResourcePicker;
use Codedor\FilamentResourcePicker\Tests\Fixtures\Item;
use Codedor\FilamentResourcePicker\Tests\Fixtures\ItemResource;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

function pickerFor(string $statePath): Testable
{
    return Livewire::test(ResourcePicker::class, [
        'resourceClass' => ItemResource::class,
        'displayType' => 'filament-resource-picker::items.list',
        'statePath' => $statePath,
        'keyField' => 'id',
        'labelField' => 'name',
        'state' => [],
        'isMultiple' => true,
        'isGrid' => false,
        'gridColumns' => 1,
    ]);
}

it('lists the resources of the given resource class', function () {
    Item::query()->create(['name' => 'First item']);
    Item::query()->create(['name' => 'Second item']);

    pickerFor('data.item_ids')
        ->assertSee('First item')
        ->assertSee('Second item');
});

it('writes the picked resources to the livewire component that owns the form', function () {
    // Regression: the picker used to look up a component named
    // `app.filament.resources.*`, which never matches other panels, and relied
    // on the field's `picked-resource` listener. Filament only renders the
    // modal of the topmost mounted action, so when the picker is opened from a
    // nested action modal (e.g. a block editor) that listener is not in the
    // DOM and the selection was lost.
    pickerFor('mountedFormComponentActionsData.0.item_ids')
        ->assertSeeHtml("\$wire.\$parent.\$set('mountedFormComponentActionsData.0.item_ids', this.state)")
        ->assertDontSeeHtml('app.filament.resources');
});
