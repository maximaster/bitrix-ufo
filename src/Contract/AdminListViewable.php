<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет метод,
 * возвращающий html для вывода значения поля в списке элементов.
 */
interface AdminListViewable
{
    /**
     * Функция вызывается при выводе значения поля в списке элементов.
     *
     * <p>Возвращает html для встраивания в ячейку таблицы.</p>
     * <p>Элементы $htmlControl приведены к html безопасному виду.</p>
     * <p>Вызывается из методов getListView и AddUserField объекта $USER_FIELD_MANAGER.</p>
     *
     * @see CUserTypeManager::getListView
     * @see CUserTypeManager::AddUserField
     *
     * @param array $userField массив, описывающий поле
     * @param array $htmlControl массив управления из формы. Содержит элементы NAME и VALUE. NAME может отсутствовать.
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
    public static function getAdminListViewHTML(array $userField, array $htmlControl): string;
}
