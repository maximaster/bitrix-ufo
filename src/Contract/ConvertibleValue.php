<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет методы для конвертации значения поля.
 */
interface ConvertibleValue
{
    /**
     * Функция вызывается перед сохранением значений в БД.
     *
     * <p>Вызывается из метода Update объекта $USER_FIELD_MANAGER.</p>
     * <p>Для множественных значений функция вызывается несколько раз, если не определён метод onBeforeSaveAll.</p>
     *
     * @see CUserTypeManager::Update
     *
     * @param array $userField массив, описывающий поле
     * @param mixed $value значение поля
     * @param int|false $userId id пользователя. По логике данный параметр не нужен, но битрикс его передает.
     *
     * @return mixed значение для вставки в БД
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
    public static function onBeforeSave(array $userField, $value, $userId = false);

    /**
     * Вызывается после извлечения значения из БД.
     *
     * <p>Вызывается из метода OnAfterFetch объекта $USER_FIELD_MANAGER.</p>
     * <p>Которая в свою очередь вызывается из методов GetUserFieldValue,
     * getUserFieldsWithReadyData и GetUserFields объекта $USER_FIELD_MANAGER.</p>
     * <p>Для множественных значений функция вызывается несколько раз.</p>
     *
     * @see CUserTypeManager::Update
     * @see CUserTypeManager::GetUserFieldValue
     * @see CUserTypeManager::getUserFieldsWithReadyData
     * @see CUserTypeManager::GetUserFields
     *
     * @param array $userField массив, описывающий поле
     * @param array $rawValue ['VALUE' => <актуальное значение>]
     *
     * @return mixed|null null для случая, когда значение поля не задано
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
     * @psalm-param array<'VALUE', mixed> $rawValue
     */
    public static function onAfterFetch(array $userField, array $rawValue);
}
