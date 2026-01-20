<?php

namespace Wotz\FilamentResourcePicker;

class ResourceQuery
{
    public const resourcePickerQueryMethod = 'resourcePickerQuery';

    public static function get(string $resourceClass, ?string $search = null)
    {
        $query = $resourceClass::getEloquentQuery();

        if (method_exists($resourceClass, self::resourcePickerQueryMethod)) {
            // Have to do this like this, since we can not access the
            // searchable columns without a HasTable Livewire component
            $query = $resourceClass::resourcePickerQuery($query, $search);
        }

        return $query;
    }
}
