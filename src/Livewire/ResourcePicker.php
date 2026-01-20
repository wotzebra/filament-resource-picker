<?php

namespace Wotz\FilamentResourcePicker\Livewire;

use Wotz\FilamentResourcePicker\ResourceQuery;
use Illuminate\Support\Arr;
use Livewire\Component;

class ResourcePicker extends Component
{
    public string $resourceClass;

    public $items = [];

    public string $displayType;

    public string $statePath;

    public string $keyField;

    public string $labelField;

    public array $state = [];

    public bool $isMultiple;

    public bool $isGrid;

    public int $gridColumns;

    public ?int $minItems = null;

    public ?int $maxItems = null;

    public ?array $relationFilters = null;

    public string $search = '';

    public array $selectedRelations = [];

    public function mount(
        string $resourceClass,
        string $displayType,
        string $statePath,
        string $keyField,
        string $labelField,
        array $state,
        bool $isMultiple,
        bool $isGrid,
        int $gridColumns,
        ?int $minItems = null,
        ?int $maxItems = null,
        ?array $relationFilters = null,
    ) {
        $this->resourceClass = $resourceClass;
        $this->displayType = $displayType;
        $this->statePath = $statePath;
        $this->keyField = $keyField;
        $this->labelField = $labelField;
        $this->state = Arr::wrap($state);
        $this->isMultiple = $isMultiple;
        $this->isGrid = $isGrid;
        $this->gridColumns = $gridColumns;
        $this->minItems = $minItems;
        $this->maxItems = $maxItems;
        $this->relationFilters = $relationFilters;

        $this->items = $this->getItems();
    }

    public function render()
    {
        return view('filament-resource-picker::livewire.resource-picker', [
            'isList' => ! $this->isGrid,
            'hasSearch' => method_exists($this->resourceClass, ResourceQuery::resourcePickerQueryMethod),
        ]);
    }

    public function getItems(int $offset = 0)
    {
        return ResourceQuery::get($this->resourceClass, $this->search)
            ->tap(fn ($query) => $this->searchRelations($query))
            ->latest()
            ->offset($offset)
            ->limit(24)
            ->get();
    }

    public function getItemCount()
    {
        return ResourceQuery::get($this->resourceClass, $this->search)
            ->tap(fn ($query) => $this->searchRelations($query))
            ->count();
    }

    public function loadMoreItems(): void
    {
        $this->items = [
            ...$this->items,
            ...$this->getItems(count($this->items)),
        ];
    }

    public function updatedSearch(): void
    {
        $this->items = $this->getItems();
    }

    public function toggleRelation($relation, $id): void
    {
        $this->setSelectedRelation($relation, $id);

        $this->items = $this->getItems();
    }

    protected function searchRelations($query)
    {
        return collect($this->relationFilters)->each(function ($filters, $relation) use ($query) {
            if (! empty($this->getSelectedRelations($relation))) {
                $query->whereHas($relation, function ($q) use ($relation) {
                    $q->whereIn('id', $this->getSelectedRelations($relation));
                });
            }
        });
    }

    protected function setSelectedRelation($relation, $id): void
    {
        if (! array_key_exists($relation, $this->selectedRelations)) {
            $this->selectedRelations[$relation][] = $id;

            return;
        }

        if (! in_array($id, $this->selectedRelations[$relation])) {
            $this->selectedRelations[$relation][] = $id;

            return;
        }

        $this->selectedRelations[$relation] = array_diff($this->selectedRelations[$relation], [$id]);
    }

    protected function getSelectedRelations($relation)
    {
        return $this->selectedRelations[$relation] ?? [];
    }

    public function isRelationFilterActive($relation, $id): bool
    {
        return in_array($id, $this->getSelectedRelations($relation));
    }
}
