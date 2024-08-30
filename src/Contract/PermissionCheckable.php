<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс,
 * который предоставляет метод для проверки доступа пользователя к полю.
 */
interface PermissionCheckable
{
    /**
     * Проверяет, есть ли у пользователя $userId доступ к полю $userField.
     *
     * <p>Вызывается из методов GetUserFields и getUserFieldsWithReadyData объекта $USER_FIELD_MANAGER.</p>
     *
     * @see CUserTypeManager::GetUserFields
     * @see CUserTypeManager::getUserFieldsWithReadyData
     *
     * @param array $userField массив, описывающий поле
     * @param int|false $userId id пользователя
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
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) why:dependency
     */
    public static function checkPermission(array $userField, $userId = false): bool;
}
