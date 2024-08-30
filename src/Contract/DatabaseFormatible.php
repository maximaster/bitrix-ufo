<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет метод,
 * возвращающий строку преобразования значения поля при выборке через SQL-запрос.
 */
interface DatabaseFormatible
{
    /**
     * Функция должна возвращать строку преобразования значения поля при выборке через SQL-запрос.
     *
     * <p>Вызывается из методов GetUserFields, GetUserFieldValue и GetSelect объекта $USER_FIELD_MANAGER.</p>
     *
     * @see CUserTypeManager::GetUserFields
     * @see CUserTypeManager::GetUserFieldValue
     * @see CUserTypeManager::GetSelect
     *
     * @param array $userField массив, описывающий поле
     * @param string $fieldName имя пользовательского поля
     *
     * @retrn string
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
    public static function formatField(array $userField, string $fieldName): string;
}
