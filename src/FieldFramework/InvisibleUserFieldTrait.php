<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\FieldFramework;

/**
 * Поле для которого все методы отрисовки возвращают пустоту.
 */
trait InvisibleUserFieldTrait
{
    /**
     * Функция вызывается при выводе формы редактирования значения поля.
     *
     * <p>Возвращает html для встраивания в ячейку таблицы
     * в форму редактирования объекта (на вкладке "Доп. свойства")</p>
     * <p>Элементы $htmlControl приведены к html безопасному виду.</p>
     * <p>Вызывается из метода GetEditFormHTML объекта $USER_FIELD_MANAGER.</p>
     *
     * @see CUserTypeManager::GetEditFormHTML
     *
     * @param array $userField массив, описывающий поле
     * @param array $htmlControl массив управления из формы
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
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter) why:dependency
     */
    public static function getEditFormHTML(array $userField, array $htmlControl): string
    {
        return '';
    }

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
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter) why:dependency
     */
    public static function getAdminListViewHTML(array $userField, array $htmlControl): string
    {
        return '';
    }

    /**
     * Функция вызывается при выводе значения поля в списке элементов в режиме редактирования.
     *
     * <p>Возвращает html для встраивания в ячейку таблицы.</p>
     * <p>Элементы $htmlControl приведены к html безопасному виду.</p>
     * <p>Вызывается из метода AddUserField объекта $USER_FIELD_MANAGER.</p>
     *
     * @see CUserTypeManager::AddUserField
     *
     * @param array $userField массив, описывающий поле
     * @param array $htmlControl массив управления из формы. Содержит элементы NAME и VALUE.
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
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter) why:dependency
     */
    public static function getAdminListEditHTML(array $userField, array $htmlControl): string
    {
        return '';
    }

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
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter) why:dependency
     */
    public static function getFilterHTML(array $userField, array $htmlControl): string
    {
        return '';
    }
}
