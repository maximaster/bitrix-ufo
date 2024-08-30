<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс.
 */
interface BitrixUserFieldType extends
    Editable,
    AdminListViewable,
    AdminListEditable,
    AdminListFilterable,
    Configurable,
    CheckableValue,
    DatabaseColumnTypeConfigurable
{
    /**
     * Должен возвращать описание пользовательского свойства.
     *
     * @return array Массив с описанием методов и типа свойства
     *
     * @psalm-return array<string,string>
     */
    public static function getUserTypeDescription(): array;
}
