<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет метод,
 * возвращающий текстовое представление значений поля.
 */
interface PublicPrintable
{
    /**
     * Функция должна возвращать текстовое представление значений поля.
     *
     * <p>Вызывается из метода getPublicText объекта $USER_FIELD_MANAGER.</p>
     *
     * @see CUserTypeManager::getPublicText
     *
     * @param array $userField массив, описывающий поле
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
    public static function getPublicText(array $userField): string;
}
