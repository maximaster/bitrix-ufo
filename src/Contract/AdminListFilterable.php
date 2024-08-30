<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет метод,
 * возвращающий html фильтра по значению поля.
 */
interface AdminListFilterable
{
    /**
     * Функция вызывается при выводе фильтра на странице списка.
     *
     * <p>Возвращает html для встраивания в ячейку таблицы.</p>
     * <p>Элементы $htmlControl приведены к html безопасному виду.</p>
     * <p>Вызывается из метода GetFilterHTML объекта $USER_FIELD_MANAGER.</p>
     * <p>Которая в свою очередь вызывается из метода AdminListShowFilter объекта $USER_FIELD_MANAGER.</p>
     * <p>А также упоминается в методе AddFindFields объекта $USER_FIELD_MANAGER.</p>
     * <p>Для множественных значений функция вызывается несколько раз.</p>
     *
     * @see CUserTypeManager::GetFilterHTML
     * @see CUserTypeManager::AdminListShowFilter
     * @see CUserTypeManager::AddFindFields
     *
     * @param array $userField массив, описывающий поле
     * @param array $htmlControl Массив управления из формы. Содержит элементы NAME и VALUE.
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
     * @psalm-param array{
     *    'ID': int,
     *    'NAME': string,
     *    'VALUE': mixed,
     *    'ROWCLASS'?: string,
     *    'VALIGN'?: string
     * } $htmlControl
     */
    public static function getFilterHTML(array $userField, array $htmlControl): string;
}
