<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет метод,
 * возвращающий представление значения поля для поиска.
 */
interface SearchIndexable
{
    /**
     * Функция должна возвращать представление значения поля для поиска.
     *
     * <p>Вызывается из метода OnSearchIndex объекта $USER_FIELD_MANAGER.</p>
     * <p>OnSearchIndex также называется функцией обновления индекса поиска объектов.</p>
     * <p>Для нескольких значений поле VALUE представляет собой массив.</p>
     *
     * @see CUserTypeManager::OnSearchIndex
     *
     * @param array $userField массив, описывающий поле
     *
     * @psalm-param array{
     *    'FIELD_NAME': non-empty-string,
     *    'ENTITY_ID': non-empty-string,
     *    'SETTINGS': array<mixed>|null,
     *    'VALUE': mixed,
     *    'MULTIPLE': 'Y'|'N',
     *    'EDIT_IN_LIST': 'Y'|'N',
     *    'ENTITY_VALUE_ID': int
     * } $userField
     */
    public static function onSearchIndex(array $userField): string;
}
