<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет метод,
 * возвращающий html для вывода значений множественного поля в списке элементов в режиме редактирования.
 */
interface AdminListMultyEditable
{
    /**
     * Функция вызывается при выводе значения множественного поля в списке элементов в режиме редактирования.
     *
     * <p>Возвращает html для встраивания в ячейку таблицы.</p>
     * <p>Если класс не предоставляет такую функцию,
     * то менеджер типов "соберет" требуемый html из вызовов GetAdminListEditHTML</p>
     * <p>Элементы $htmlControl приведены к html безопасному виду.</p>
     * <p>Вызывается из метода AddUserField объекта $USER_FIELD_MANAGER.</p>
     *
     * @see CUserTypeManager::AddUserField
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
    public static function getAdminListEditHTMLMulty(array $userField, array $htmlControl): string;
}
