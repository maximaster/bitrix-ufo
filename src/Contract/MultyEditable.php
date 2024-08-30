<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет метод,
 * возвращающий html для вывода формы редактирования значения множественного поля.
 */
interface MultyEditable
{
    /**
     * Функция вызывается при выводе формы редактирования значения множественного поля.
     *
     * <p>Возвращает html для встраивания в ячейку таблицы
     * в форму редактирования объекта (на вкладке "Доп. свойства")</p>
     * <p>Если класс не предоставляет такую функцию,
     * то менеджер типов "соберет" требуемый html из вызовов GetEditFormHTML</p>
     * <p>Элементы $htmlControl приведены к html безопасному виду.</p>
     * <p>Вызывается из метода GetEditFormHTML объекта $USER_FIELD_MANAGER.</p>
     *
     * @see CUserTypeManager::GetEditFormHTML
     *
     * @param array $userField массив, описывающий поле
     * @param array $htmlControl массив управления из формы. Содержит элементы NAME, VALUE и ROWCLASS.
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
    public static function getEditFormHTMLMulty(array $userField, array $htmlControl): string;
}
