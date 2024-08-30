<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\FieldFramework;

use Webmozart\Assert\Assert;

/**
 * Отображает поле в админке как простое текстовое поле ввода.
 */
trait StringInputVisibleFieldTrait
{
    /**
     * @psalm-var array{ROWS: int, SIZE: int, MAX_LENGTH: int, DEFAULT_VALUE: string, REGEXP: string}
     */
    private static array $defaultSettings = [
        'ROWS' => 0,
        'SIZE' => 0,
        'MAX_LENGTH' => 0,
        'MIN_LENGTH' => 0,
        'DEFAULT_VALUE' => '',
        'REGEXP' => '',
    ];

    /**
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
     * @noinspection DuplicatedCode
     */
    public static function getEditFormHTML(array $userField, array $htmlControl): string
    {
        $userField = self::normalizedUserField($userField);

        if ($userField['ENTITY_VALUE_ID'] < 1 && strlen($userField['SETTINGS']['DEFAULT_VALUE']) > 0) {
            $htmlControl['VALUE'] = htmlspecialcharsbx($userField['SETTINGS']['DEFAULT_VALUE']);
        }

        if ($userField['SETTINGS']['ROWS'] < 2) {
            return '<input type="text" ' .
                'name="' . ($htmlControl['NAME']) . '" ' .
                'size="' . $userField['SETTINGS']['SIZE'] . '" ' .
                ($userField['SETTINGS']['MAX_LENGTH'] > 0
                    ? 'maxlength="' . $userField['SETTINGS']['MAX_LENGTH'] . '" '
                    : '')
                . 'value="' . $htmlControl['VALUE'] . '" ' .
                ($userField['EDIT_IN_LIST'] != 'Y' ? 'disabled="disabled" ' : '') . '>';
        }

        return '<textarea ' .
            'name="' . ($htmlControl['NAME']) . '" ' .
            'cols="' . $userField['SETTINGS']['SIZE'] . '" ' .
            'rows="' . $userField['SETTINGS']['ROWS'] . '" ' .
            ($userField['SETTINGS']['MAX_LENGTH'] > 0
                ? 'maxlength="' . $userField['SETTINGS']['MAX_LENGTH'] . '" ' : '') .
            ($userField['EDIT_IN_LIST'] != 'Y' ? 'disabled="disabled" ' : '') .
            '>' . $htmlControl['VALUE'] . '</textarea>';
    }

    /**
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
        $value = strval($htmlControl['VALUE']);

        return strlen($value) > 0 ? $value : '&nbsp;';
    }

    /**
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
     * @noinspection DuplicatedCode
     */
    public static function getAdminListEditHTML(array $userField, array $htmlControl): string
    {
        $userField = self::normalizedUserField($userField);
        $htmlControl = self::normalizedHtmlControl($htmlControl);

        return sprintf(
            '<input type="text" name="%s" size="%s" %s value="%s" >',
            $htmlControl['NAME'],
            $userField['SETTINGS']['SIZE'],
            $userField['SETTINGS']['MAX_LENGTH'] > 0
                ? 'maxlength="' . $userField['SETTINGS']['MAX_LENGTH'] . '" '
                : '',
            $htmlControl['VALUE']
        );
    }

    /**
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
    public static function getFilterHTML(array $userField, array $htmlControl): string
    {
        $userField = self::normalizedUserField($userField);

        return sprintf(
            '<input type="text" name="%s" size="%s" value="%s">',
            $htmlControl['NAME'],
            $userField['SETTINGS']['SIZE'],
            strval($htmlControl['VALUE'])
        );
    }

    /**
     * @psalm-param array{
     *    'FIELD_NAME': non-empty-string,
     *    'ENTITY_ID': non-empty-string,
     *    'SETTINGS': array<mixed>|null,
     *    'VALUE': mixed,
     *    'MULTIPLE': 'Y'|'N',
     *    'EDIT_IN_LIST': 'Y'|'N',
     *    'ENTITY_VALUE_ID': int
     * } $userField
     * @psalm-return array{
     *     'FIELD_NAME': non-empty-string,
     *     'ENTITY_ID': non-empty-string,
     *     'SETTINGS': array{
     *          'SIZE': int,
     *          'MAX_LENGTH': int,
     *          'MIN_LENGTH': int,
     *          'ROWS': int,
     *          'DEFAULT_VALUE': string,
     *          'REGEXP': string
     *     },
     *     'VALUE': string|null,
     *     'MULTIPLE': 'Y'|'N',
     *     'EDIT_IN_LIST': 'Y'|'N',
     *     'ENTITY_VALUE_ID': int
     * }
     */
    private static function normalizedUserField(array $userField): array
    {
        $userField['VALUE'] = is_string($userField['VALUE']) ? $userField['VALUE'] : null;
        $userField['SETTINGS'] = is_array($userField['SETTINGS']) ? $userField['SETTINGS'] : [];
        $userField['SETTINGS'] += self::$defaultSettings;

        // @phpstan-ignore-next-line why:false-positive
        return $userField;
    }

    /**
     * @psalm-param array{
     *    'ID': int,
     *    'NAME': string,
     *    'VALUE': mixed,
     *    'ROWCLASS'?: string,
     *    'VALIGN'?: string
     * } $htmlControl
     * @psalm-return array{'NAME': string, 'VALUE': string|null, 'ROWCLASS'?: string, 'VALIGN'?: string}
     */
    private static function normalizedHtmlControl(array $htmlControl): array
    {
        Assert::nullOrString($htmlControl['VALUE']);

        return $htmlControl;
    }
}
