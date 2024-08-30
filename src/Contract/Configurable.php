<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет методы для работы с настройками поля.
 */
interface Configurable
{
    /**
     * Функция вызывается перед сохранением метаданных (настроек) свойства в БД.
     *
     * <p>Функция должна "очистить" массив с настройками экземпляра типа свойства.
     * Для того что бы случайно/намеренно никто не записал туда лишние данные.</p>
     * <p>Вызывается из метода PrepareSettings объекта $USER_FIELD_MANAGER.</p>
     *
     * @see CUserTypeManager::PrepareSettings
     *
     * @param array $userField массив, описывающий поле
     *
     * @return array массив, который в дальнейшем будет сериализован и сохранен в БД
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
     * @psalm-return array<string, mixed>
     */
    public static function prepareSettings(array $userField): array;

    /**
     * Функция вызывается при выводе формы метаданных (настроек) свойства.
     *
     * <p>Возвращает html для встраивания в 2-х колоночную таблицу. В форму usertype_edit.php</p>
     * <p>Т.е. tr td имя поля /td td input для редактирование /td /tr.</p>
     * <p>Вызывается из метода GetFilterHTML объекта $USER_FIELD_MANAGER.</p>
     *
     * @see CUserTypeManager::GetSettingsHTML
     *
     * @param array|false $userField массив, описывающий поле
     * @param array $htmlControl массив управления из формы. Содержит элементы NAME.
     * @param bool $isVarsFromForm булевый флаг использования данных из формы
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
     *     'ID': int,
     *     'NAME': string,
     *     'VALUE': mixed,
     *     'ROWCLASS'?: string,
     *     'VALIGN'?: string
     * } $htmlControl
     */
    public static function getSettingsHTML($userField, array $htmlControl, bool $isVarsFromForm): string;
}
