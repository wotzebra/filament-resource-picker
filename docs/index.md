# Resource Picker for Filament

This package allows to pick items from other resources as an alternative for relationships (e.g. when you want to save related items in a json column).

## Installation

You can install the package via composer:

```bash
composer require wotz/filament-resource-picker
```

## Usage

```php
public static function form(Form $form): Form
{
    return $form
        ->schema([
            \Wotz\FilamentResourcePicker\Filament\Forms\Components\ResourcePickerInput::make('author')
                ->resource(UserResource::class),
        ]);
}
```

Passing the resource is obligated, since we will use that to fetch the items that can be picked.

### multiple()

By default you can only pick one item. If you want to pick multiple items, you can use the `multiple()` method.

```php
ResourcePickerInput::make('authors')
    ->resource(UserResource::class)
    ->multiple(),
```

### keyField()

By default we will save the id field, but if you want the name or another field, you can adjust it by calling this method.

```php
ResourcePickerInput::make('authors')
    ->resource(UserResource::class)
    ->keyField('name'),
```

### labelField()

By default we will display the id value, but if you want the name or another field, you can adjust it by calling this method.

```php
ResourcePickerInput::make('authors')
    ->resource(UserResource::class)
    ->labelField('name'),
```

### displayType()

Via this method you can adjust the view we will use in the picker modal

### grid()

Tell us if we have to display the items in the resource in a grid or a list.

## Searching resources

We provide a search input in the picker modal, but you have to implement the search logic yourself. You can do this by adding a `resourcePickerQuery` method to your resource.
The query you get here is the same one as `Resource::getEloquentQuery()` that Filament provides, see the [docs](https://filamentphp.com/docs/3.x/panels/resources/getting-started#customizing-the-resource-eloquent-query).

```php
public static function resourcePickerQuery(Builder $query, ?string $search = null): \Illuminate\Database\Eloquent\Builder
{
    return $query
        ->when(
            $search,
            fn () => $query->where('name', 'like', '%' . $search . '%')
        );
}
```

## Custom query builder method

We provide a `resources(array $ids = [])` method on the query builder, so it's easy to fetch the resources that are picked and maintain their order.

```php
$posts = Post::resources([1, 2, 3])->get();
```
