<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет метод,
 * выполняющийся перед удалением значений пользовательских полей.
 */
interface DeleteHandler
{
    /**
     * Функция, которая выполняется перед удалением значений пользовательских полей.
     *
     * <p>Вызывается из метода Delete объекта $USER_FIELD_MANAGER.</p>
     * <p>Для множественных значений функция вызывается несколько раз.</p>
     *
     * @see CUserTypeManager::Delete
     *
     * @param array $userField массив, описывающий поле
     * @param mixed $value значение поля
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
    public static function onDelete(array $userField, $value): void;
}
