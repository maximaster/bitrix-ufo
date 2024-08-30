<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет метод для проверки значения поля.
 */
interface CheckableValue
{
    /**
     * Функция, проверяющая поле.
     *
     * <p>Вызывается из методов CheckFields и CheckFieldsWithOldData объекта $USER_FIELD_MANAGER.</p>
     * <p>Для множественных значений функция вызывается несколько раз.</p>
     *
     * @see CUserTypeManager::CheckFields
     * @see CUserTypeManager::CheckFieldsWithOldData
     *
     * @param array $userField массив, описывающий поле
     * @param mixed $value значение для проверки на валидность
     * @param int|false $userId id пользователя
     *
     * @return array если ошибок нет, должен возвращаться пустой массив
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
     * @psalm-return list<array<'id'|'text', string>>
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) why:dependency
     */
    public static function checkFields(array $userField, $value, $userId = false): array;
}
