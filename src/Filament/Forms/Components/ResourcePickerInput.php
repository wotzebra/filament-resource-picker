<?php

namespace Wotz\FilamentResourcePicker\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Wotz\FilamentResourcePicker\Filament\Actions\OpenResourcePickerAction;
use Wotz\FilamentResourcePicker\ResourceQuery;

class ResourcePickerInput extends Field
{
    protected string $view = 'filament-resource-picker::forms.components.resource-picker-input';

    public string|Closure $resource;

    public string|Closure $keyField = 'id';

    public string|Closure $labelField = 'id';

    public null|string|Closure $displayType = null;

    public int|Closure $gridColumns = 1;

    public bool|Closure $isMultiple = false;

    public function setUp(): void
    {
        $this->registerActions([
            OpenResourcePickerAction::make(),

            \Filament\Actions\Action::make('clear-selection')
                ->label(__('filament-resource-picker::picker.clear selection'))
                ->action(fn (\Filament\Schemas\Components\Utilities\Set $set) => $set($this->getStatePath(false), []))
                ->color('gray'),
        ]);
    }

    public function grid(int|Closure $columns = 3): self
    {
        $this->gridColumns = $columns;

        return $this;
    }

    public function isGrid(): bool
    {
        return $this->evaluate($this->gridColumns) > 1;
    }

    public function gridColumns(): int
    {
        return $this->evaluate($this->gridColumns);
    }

    public function displayType(string|Closure $displayType): self
    {
        $this->displayType = $displayType;

        return $this;
    }

    public function getDisplayType(): string
    {
        if (! is_null($this->displayType)) {
            return $this->evaluate($this->displayType);
        }

        if ($this->isGrid()) {
            return 'filament-resource-picker::items.grid';
        }

        return 'filament-resource-picker::items.list';
    }

    public function keyField(string|Closure $keyField): self
    {
        $this->keyField = $keyField;

        return $this;
    }

    public function getKeyField(): string
    {
        return $this->evaluate($this->keyField);
    }

    public function labelField(string|Closure $labelField): self
    {
        $this->labelField = $labelField;

        return $this;
    }

    public function getLabelField(): string
    {
        return $this->evaluate($this->labelField);
    }

    public function multiple(bool|Closure $multiple = true): self
    {
        $this->isMultiple = $multiple;

        return $this;
    }

    public function isMultiple(): bool
    {
        return $this->evaluate($this->isMultiple);
    }

    public function resource(string|Closure $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function getResource(): string
    {
        return $this->evaluate($this->resource);
    }

    public function getStateAsResources(): Collection
    {
        $state = Arr::wrap($this->getState() ?? []);

        return ResourceQuery::get($this->getResource())
            ->whereIn(
                $this->getKeyField(),
                $state,
            )
            ->get()
            ->sortBy(fn ($item) => array_search($item->getKey(), $state))
            ->values();
    }
}
