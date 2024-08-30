<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет метод,
 * возвращающий html для редактирования поля в публичной части.
 */
interface PublicEditable
{
    /**
     * Функция возвращает html для редактирования поля в публичной части (в основном используется в system.view.field).
     *
     * <p>Вызывается из метода GetPublicEdit объекта $USER_FIELD_MANAGER.</p>
     * <p>Через поле EDIT_CALLBACK Битриксового массива настройки пользовательского поля.</p>
     *
     * @see CUserTypeManager::GetPublicEdit
     *
     * @param array $userField массив, описывающий поле
     * @param array $params массив параметров из компонента system.edit.field (возможные параметры).
     *
     * @return string HTML для вывода
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
     * @psalm-param array<string, mixed> $params
     */
    public static function getPublicEdit(array $userField, array $params): string;
}
