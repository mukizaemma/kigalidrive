<?php

namespace App\Support;

use Illuminate\Support\Facades\Schema;

class SchemaHelper
{
    /** @var array<string, bool> */
    protected static array $tableCache = [];

    /** @var array<string, bool> */
    protected static array $columnCache = [];

    public static function hasTable(string $table): bool
    {
        return static::$tableCache[$table] ??= Schema::hasTable($table);
    }

    public static function hasColumn(string $table, string $column): bool
    {
        $key = "{$table}.{$column}";

        return static::$columnCache[$key] ??= static::hasTable($table) && Schema::hasColumn($table, $column);
    }

    public static function legacyHotelsEnabled(): bool
    {
        return static::hasTable('hotels');
    }

    public static function legacyProgramsEnabled(): bool
    {
        return static::hasTable('programs');
    }

    public static function legacyCategoriesEnabled(): bool
    {
        return static::hasTable('categories');
    }
}
