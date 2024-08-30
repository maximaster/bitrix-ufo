<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет метод,
 * дополняющий новый фильтр (ядро D7) пользовательскими полями.
 */
interface FilterCompatible
{
    /**
     * Функция, которая дополняет фильтр пользовательскими полями.
     *
     * <p>Вызывается из метода AdminListAddFilterFieldsV2 объекта $USER_FIELD_MANAGER.</p>
     *
     * @see CUserTypeManager::AdminListAddFilterFieldsV2
     *
     * <p>Используется для заполнение параметра FILTER компонента bitrix:main.ui.filter.</p>
     * <p>Разрешенные поля приведены по ссылке.</p>
     * @see https://dev.1c-bitrix.ru/api_d7/bitrix/main/systemcomponents/gridandfilter/mainuifilter.php
     *
     * @param array $userField массив, описывающий поле
     * @param array $htmlControl массив управления из формы
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
    public static function getFilterData(array $userField, array $htmlControl): array;
}
