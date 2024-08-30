<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Field;

use Bitrix\Main\Text\HtmlFilter;
use Bitrix\Main\UserField\TypeBase;
use Maximaster\BitrixEnums\Main\UserFieldBaseType;
use Maximaster\BitrixUfo\Contract\BitrixUserFieldType;
use Maximaster\BitrixUfo\Contract\FilterCompatible;
use Maximaster\BitrixUfo\Contract\PublicEditable;
use Maximaster\BitrixUfo\Contract\PublicFormViewable;
use Maximaster\BitrixUfo\Contract\SearchIndexable;
use Maximaster\BitrixUfo\Contract\UserFieldType;
use Maximaster\BitrixUfo\FieldFramework\StringInputVisibleFieldTrait;
use Maximaster\BitrixUfo\FieldFramework\TypedUserFieldTrait;

/**
 * @SuppressWarnings(PHPMD.Superglobals) why:dependency
 * @SuppressWarnings(PHPMD.ElseExpression) TODO refactor
 * @SuppressWarnings(PHPMD.CyclomaticComplexity) TODO refactor
 * @SuppressWarnings(PHPMD.NPathComplexity) TODO refactor
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity) TODO refactor
 */
class StringField extends TypeBase implements
    BitrixUserFieldType,
    UserFieldType,
    FilterCompatible,
    PublicFormViewable,
    PublicEditable,
    SearchIndexable
{
    use StringInputVisibleFieldTrait;
    use TypedUserFieldTrait;

    /**
     * {@inheritDoc}
     */
    public static function id(): string
    {
        return 'ufo_string';
    }

    /**
     * {@inheritDoc}
     */
    public static function description(): string
    {
        return 'Поле типа строка (bitrix-ufo).';
    }

    /**
     * {@inheritDoc}
     */
    public static function baseType(): UserFieldBaseType
    {
        return UserFieldBaseType::STRING();
    }

    /**
     * {@inheritDoc}.
     */
    public static function columnTypeDefinition(): string
    {
        return 'TEXT';
    }

    /**
     * {@inheritDoc}
     *
     * @noinspection DuplicatedCode
     */
    public static function prepareSettings(array $userField): array
    {
        $size = intval($userField['SETTINGS']['SIZE']);
        $rows = intval($userField['SETTINGS']['ROWS']);
        $min = intval($userField['SETTINGS']['MIN_LENGTH']);
        $max = intval($userField['SETTINGS']['MAX_LENGTH']);

        return [
            'SIZE' => ($size <= 1 ? 20 : ($size > 255 ? 225 : $size)),
            'ROWS' => ($rows <= 1 ? 1 : ($rows > 50 ? 50 : $rows)),
            'REGEXP' => $userField['SETTINGS']['REGEXP'],
            'MIN_LENGTH' => $min,
            'MAX_LENGTH' => $max,
            'DEFAULT_VALUE' => $userField['SETTINGS']['DEFAULT_VALUE'],
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @noinspection DuplicatedCode
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength) TODO
     */
    public static function getSettingsHTML($userField, array $htmlControl, bool $isVarsFromForm): string
    {
        $result = '';
        if ($isVarsFromForm) {
            $value = htmlspecialcharsbx($GLOBALS[$htmlControl['NAME']]['DEFAULT_VALUE']);
        } elseif (is_array($userField)) {
            $value = htmlspecialcharsbx($userField['SETTINGS']['DEFAULT_VALUE']);
        } else {
            $value = '';
        }

        $result .= '
            <tr>
                <td>' . GetMessage('USER_TYPE_STRING_DEFAULT_VALUE') . ':</td>
                <td>
                    <input type="text" name="' . $htmlControl['NAME']
                    . '[DEFAULT_VALUE]" size="20"  maxlength="225" value="' . $value . '">
                </td>
            </tr>
        ';

        if ($isVarsFromForm) {
            $value = intval($GLOBALS[$htmlControl['NAME']]['SIZE']);
        } elseif (is_array($userField)) {
            $value = intval($userField['SETTINGS']['SIZE']);
        } else {
            $value = 20;
        }
        $result .= '
            <tr>
                <td>' . GetMessage('USER_TYPE_STRING_SIZE') . ':</td>
                <td>
                    <input type="text" name="' . $htmlControl['NAME']
                    . '[SIZE]" size="20"  maxlength="20" value="' . $value . '">
                </td>
            </tr>
		';

        if ($isVarsFromForm) {
            $value = intval($GLOBALS[$htmlControl['NAME']]['ROWS']);
        } elseif (is_array($userField)) {
            $value = intval($userField['SETTINGS']['ROWS']);
        } else {
            $value = 1;
        }

        if ($value < 1) {
            $value = 1;
        }

        $result .= '
            <tr>
                <td>' . GetMessage('USER_TYPE_STRING_ROWS') . ':</td>
                <td>
                    <input type="text" name="' . $htmlControl['NAME']
                    . '[ROWS]" size="20"  maxlength="20" value="' . $value . '">
                </td>
            </tr>
		';
        if ($isVarsFromForm) {
            $value = intval($GLOBALS[$htmlControl['NAME']]['MIN_LENGTH']);
        } elseif (is_array($userField)) {
            $value = intval($userField['SETTINGS']['MIN_LENGTH']);
        } else {
            $value = 0;
        }

        $result .= '
            <tr>
                <td>' . GetMessage('USER_TYPE_STRING_MIN_LEGTH') . ':</td>
                <td>
                    <input type="text" name="' . $htmlControl['NAME']
                    . '[MIN_LENGTH]" size="20"  maxlength="20" value="' . $value . '">
                </td>
            </tr>
		';

        if ($isVarsFromForm) {
            $value = intval($GLOBALS[$htmlControl['NAME']]['MAX_LENGTH']);
        } elseif (is_array($userField)) {
            $value = intval($userField['SETTINGS']['MAX_LENGTH']);
        } else {
            $value = 0;
        }

        $result .= '
            <tr>
                <td>' . GetMessage('USER_TYPE_STRING_MAX_LENGTH') . ':</td>
                <td>
                    <input type="text" name="' . $htmlControl['NAME']
                    . '[MAX_LENGTH]" size="20"  maxlength="20" value="' . $value . '">
                </td>
            </tr>
		';

        if ($isVarsFromForm) {
            $value = htmlspecialcharsbx($GLOBALS[$htmlControl['NAME']]['REGEXP']);
        } elseif (is_array($userField)) {
            $value = htmlspecialcharsbx($userField['SETTINGS']['REGEXP']);
        } else {
            $value = '';
        }

        $result .= '
            <tr>
                <td>' . GetMessage('USER_TYPE_STRING_REGEXP') . ':</td>
                <td>
                    <input type="text" name="' . $htmlControl['NAME']
                    . '[REGEXP]" size="20"  maxlength="200" value="' . $value . '">
                </td>
            </tr>
		';

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public static function getFilterHTML(array $userField, array $htmlControl): string
    {
        return '<input type="text" ' .
            'name="' . $htmlControl['NAME'] . '" ' .
            'size="' . $userField['SETTINGS']['SIZE'] . '" ' .
            'value="' . $htmlControl['VALUE'] . '"' . '>';
    }

    /**
     * {@inheritDoc}
     */
    public static function getFilterData(array $userField, array $htmlControl): array
    {
        return [
            'id' => $htmlControl['ID'],
            'name' => $htmlControl['NAME'],
            'filterable' => '',
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @noinspection DuplicatedCode
     */
    public static function getPublicView(array $userField, array $params): string
    {
        $value = static::normalizeFieldValue($userField['VALUE']);

        $html = '';
        $first = true;
        foreach ($value as $res) {
            if ($first === false) {
                $html .= static::getHelper()->getMultipleValuesSeparator();
            }
            $first = false;

            $res = HtmlFilter::encode($res);

            if ($userField['SETTINGS']['ROWS'] > 1 && strlen($res) > 0) {
                $res = nl2br($res);
            }

            if (strlen($userField['PROPERTY_VALUE_LINK']) > 0) {
                $res = '<a href="' . htmlspecialcharsbx(
                    str_replace('#VALUE#', urlencode($res), $userField['PROPERTY_VALUE_LINK'])
                ) . '">' . $res . '</a>';
            }

            $html .= static::getHelper()->wrapSingleField($res);
        }

        static::initDisplay();

        return static::getHelper()->wrapDisplayResult($html);
    }

    /**
     * {@inheritDoc}
     *
     * @noinspection DuplicatedCode
     */
    public static function getPublicEdit(array $userField, array $params): string
    {
        $fieldName = static::getFieldName($userField, $params);
        $value = static::getFieldValue($userField, $params);

        $html = '';
        foreach ($value as $res) {
            $attrList = [];

            if ($userField['SETTINGS']['MAX_LENGTH'] > 0) {
                $attrList['maxlength'] = intval($userField['SETTINGS']['MAX_LENGTH']);
            }

            if ($userField['EDIT_IN_LIST'] != 'Y') {
                $attrList['disabled'] = 'disabled';
            }

            if ($userField['SETTINGS']['ROWS'] < 2) {
                if ($userField['SETTINGS']['SIZE'] > 0) {
                    $attrList['size'] = intval($userField['SETTINGS']['SIZE']);
                }
            } else {
                $attrList['cols'] = intval($userField['SETTINGS']['SIZE']);
                $attrList['rows'] = intval($userField['SETTINGS']['ROWS']);
            }

            if (array_key_exists('attribute', $params)) {
                $attrList = array_merge($attrList, $params['attribute']);
            }

            if (isset($attrList['class']) && is_array($attrList['class'])) {
                $attrList['class'] = implode(' ', $attrList['class']);
            }

            $attrList['class'] = static::getHelper()->getCssClassName()
                . (isset($attrList['class']) ? ' ' . $attrList['class'] : '');

            $attrList['name'] = $fieldName;
            $attrList['tabindex'] = '0';

            if ($userField['SETTINGS']['ROWS'] < 2) {
                $attrList['type'] = 'text';
                $attrList['value'] = $res;

                $html .= static::getHelper()->wrapSingleField(
                    '<input ' . static::buildTagAttributes($attrList) . '/>'
                );
            } else {
                $html .= static::getHelper()->wrapSingleField(
                    '<textarea ' . static::buildTagAttributes($attrList)
                    . '>' . htmlspecialcharsbx($res) . '</textarea>'
                );
            }
        }

        if ($userField['MULTIPLE'] == 'Y' && $params['SHOW_BUTTON'] != 'N') {
            $html .= static::getHelper()->getCloneButton($fieldName);
        }

        static::initDisplay();

        return static::getHelper()->wrapDisplayResult($html);
    }

    /**
     * {@inheritDoc}
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) why:dependency
     */
    public static function checkFields(array $userField, $value, $userId = false): array
    {
        $userField = self::normalizedUserField($userField);

        $aMsg = [];
        if ($value != '' && strlen($value) < $userField['SETTINGS']['MIN_LENGTH']) {
            $aMsg[] = [
                'id' => $userField['FIELD_NAME'],
                'text' => GetMessage(
                    'USER_TYPE_STRING_MIN_LEGTH_ERROR',
                    [
                         '#FIELD_NAME#' => $userField['EDIT_FORM_LABEL'] != ''
                             ? $userField['EDIT_FORM_LABEL']
                             : $userField['FIELD_NAME'],
                         '#MIN_LENGTH#' => $userField['SETTINGS']['MIN_LENGTH'],
                     ]
                ),
            ];
        }

        if ($userField['SETTINGS']['MAX_LENGTH'] > 0 && strlen($value) > $userField['SETTINGS']['MAX_LENGTH']) {
            $aMsg[] = [
                'id' => $userField['FIELD_NAME'],
                'text' => GetMessage(
                    'USER_TYPE_STRING_MAX_LEGTH_ERROR',
                    [
                         '#FIELD_NAME#' => $userField['EDIT_FORM_LABEL'] != ''
                             ? $userField['EDIT_FORM_LABEL']
                             : $userField['FIELD_NAME'],
                         '#MAX_LENGTH#' => $userField['SETTINGS']['MAX_LENGTH'],
                     ]
                ),
            ];
        }

        if (
            $userField['SETTINGS']['REGEXP'] != ''
            && boolval(preg_match($userField['SETTINGS']['REGEXP'], $value)) === false
        ) {
            $aMsg[] = [
                'id' => $userField['FIELD_NAME'],
                'text' => (
                    strlen($userField['ERROR_MESSAGE']) > 0 ?
                    $userField['ERROR_MESSAGE'] :
                    GetMessage(
                        'USER_TYPE_STRING_REGEXP_ERROR',
                        [
                            '#FIELD_NAME#' => $userField['EDIT_FORM_LABEL'] != ''
                                ? $userField['EDIT_FORM_LABEL']
                                : $userField['FIELD_NAME'],
                        ]
                    )
                ),
            ];
        }

        return $aMsg;
    }

    /**
     * {@inheritDoc}
     */
    public static function onSearchIndex(array $userField): string
    {
        if (is_array($userField['VALUE'])) {
            return implode("\r\n", $userField['VALUE']);
        }

        return strval($userField['VALUE']);
    }
}
