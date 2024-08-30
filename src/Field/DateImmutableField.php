<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Field;

use Bitrix\Main\ObjectException;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\Date as BitrixDate;
use Bitrix\Main\Type\DateTime as BitrixDateTime;
use Bitrix\Main\UserField\TypeBase;
use CAdminCalendar;
use CDatabase;
use CLang;
use DateTimeImmutable;
use Maximaster\BitrixEnums\Main\DateTimeFormat;
use Maximaster\BitrixEnums\Main\Truth;
use Maximaster\BitrixEnums\Main\UserFieldBaseType;
use Maximaster\BitrixTableClasses\Table\Main\UserFieldTable;
use Maximaster\BitrixTableFields\Field\DateTimeImmutableField;
use Maximaster\BitrixUfo\Contract\BitrixUserFieldType;
use Maximaster\BitrixUfo\Contract\ConvertibleValue;
use Maximaster\BitrixUfo\Contract\DatabaseFormatible;
use Maximaster\BitrixUfo\Contract\EntityFieldProvider;
use Maximaster\BitrixUfo\Contract\FilterCompatible;
use Maximaster\BitrixUfo\Contract\PublicEditable;
use Maximaster\BitrixUfo\Contract\PublicFormViewable;
use Maximaster\BitrixUfo\Contract\UserFieldType;
use Maximaster\BitrixUfo\FieldFramework\TypedUserFieldTrait;

use function Maximaster\TypedCrutch\ensure_nestring;

/**
 * Поле, которое хранит представление неизменной даты.
 *
 * @SuppressWarnings(PHPMD.Superglobals) why:dependency
 * @SuppressWarnings(PHPMD.CamelCaseVariableName) why:dependency
 * @SuppressWarnings(PHPMD.ElseExpression) TODO refactor
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity) TODO refactor
 */
class DateImmutableField extends TypeBase implements
    BitrixUserFieldType,
    UserFieldType,
    EntityFieldProvider,
    FilterCompatible,
    ConvertibleValue,
    DatabaseFormatible,
    PublicFormViewable,
    PublicEditable
{
    use TypedUserFieldTrait;

    /**
     * {@inheritDoc}
     */
    public static function id(): string
    {
        return 'ufo_date_immutable';
    }

    /**
     * {@inheritDoc}
     */
    public static function description(): string
    {
        return 'Представление неизменной даты.';
    }

    /**
     * {@inheritDoc}
     */
    public static function baseType(): UserFieldBaseType
    {
        return UserFieldBaseType::DATETIME();
    }

    /**
     * {@inheritDoc}
     */
    public static function columnTypeDefinition(): string
    {
        return 'DATE';
    }

    /**
     * {@inheritDoc}
     *
     * @throws SystemException
     */
    public static function getEntityField(string $name, array $parameters): Field
    {
        return DateTimeImmutableField::on(ensure_nestring($name), $parameters);
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDate::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function prepareSettings(array $userField): array
    {
        $defaultValue = $userField['SETTINGS']['DEFAULT_VALUE'];

        if (is_array($defaultValue) === false) {
            $defaultValue = ['TYPE' => 'NONE', 'VALUE' => ''];
        } else {
            if ($defaultValue['TYPE'] === 'FIXED') {
                $defaultValue['VALUE'] = CDatabase::FormatDate(
                    $defaultValue['VALUE'],
                    CLang::GetDateFormat(DateTimeFormat::SHORT),
                    'YYYY-MM-DD'
                );
            } elseif ($defaultValue['TYPE'] === 'NOW') {
                $defaultValue['VALUE'] = '';
            } else {
                $defaultValue = [
                    'TYPE' => 'NONE',
                    'VALUE' => '',
                ];
            }
        }

        return [
            'DEFAULT_VALUE' => [
                'TYPE' => $defaultValue['TYPE'],
                'VALUE' => $defaultValue['VALUE'],
            ],
        ];
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDate::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function getSettingsHTML($userField, array $htmlControl, bool $isVarsFromForm): string
    {
        $result = '';

        if ($isVarsFromForm) {
            $type = $GLOBALS[$htmlControl['NAME']]['DEFAULT_VALUE']['TYPE'];
            $value = $GLOBALS[$htmlControl['NAME']]['DEFAULT_VALUE']['VALUE'];
        } elseif (is_array($userField) && is_array($userField['SETTINGS']['DEFAULT_VALUE'])) {
            $type = $userField['SETTINGS']['DEFAULT_VALUE']['TYPE'];
            $value = CDatabase::FormatDate(
                $userField['SETTINGS']['DEFAULT_VALUE']['VALUE'],
                'YYYY-MM-DD',
                CLang::GetDateFormat(DateTimeFormat::SHORT)
            );
        } else {
            $type = 'NONE';
            $value = '';
        }

        $result .= '
		<tr>
			<td class="adm-detail-valign-top">Значение по умолчанию:</td>
			<td>
				<label><input type="radio" name="' . $htmlControl['NAME'] . '[DEFAULT_VALUE][TYPE]" value="NONE" '
            . ('NONE' === $type ? 'checked="checked"' : '') . '>нет</label><br>
				<label><input type="radio" name="' . $htmlControl['NAME'] . '[DEFAULT_VALUE][TYPE]" value="NOW" '
            . ('NOW' === $type ? 'checked="checked"' : '') . '>текущая дата</label><br>
				<label><input type="radio" name="' . $htmlControl['NAME'] . '[DEFAULT_VALUE][TYPE]" value="FIXED" '
            . ('FIXED' === $type ? 'checked="checked"' : '') . '>'
            . CAdminCalendar::CalendarDate($htmlControl['NAME'] . '[DEFAULT_VALUE][VALUE]', $value) . '</label><br>
			</td>
		</tr>
		';

        return $result;
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDate::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function getEditFormHTML(array $userField, array $htmlControl): string
    {
        $htmlControl['VALIGN'] = 'middle';

        if ($userField['EDIT_IN_LIST'] === Truth::YES) {
            if ((int) $userField['ENTITY_VALUE_ID'] < 1 && $userField['SETTINGS']['DEFAULT_VALUE']['TYPE'] !== 'NONE') {
                if ($userField['SETTINGS']['DEFAULT_VALUE']['TYPE'] === 'NOW') {
                    $htmlControl['VALUE'] = ConvertTimeStamp(time(), DateTimeFormat::SHORT);
                } else {
                    $htmlControl['VALUE'] = CDatabase::FormatDate(
                        $userField['SETTINGS']['DEFAULT_VALUE']['VALUE'],
                        'YYYY-MM-DD',
                        CLang::GetDateFormat(DateTimeFormat::SHORT)
                    );
                }
            }

            return CAdminCalendar::CalendarDate($htmlControl['NAME'], $htmlControl['VALUE']);
        }

        if ($htmlControl['VALUE'] !== '') {
            return $htmlControl['VALUE'];
        }

        return '&nbsp;';
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDate::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function getFilterHTML(array $userField, array $htmlControl): string
    {
        return CalendarPeriod(
            $htmlControl['NAME'] . '_from',
            $GLOBALS[$htmlControl['NAME'] . '_from'],
            $htmlControl['NAME'] . '_to',
            $GLOBALS[$htmlControl['NAME'] . '_to'],
            'find_form',
            Truth::YES
        );
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDate::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function getFilterData(array $userField, array $htmlControl): array
    {
        return [
            'id' => $htmlControl['ID'],
            'name' => $htmlControl['NAME'],
            'type' => 'date',
        ];
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDate::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function getAdminListViewHTML(array $userField, array $htmlControl): string
    {
        return $htmlControl['VALUE'] !== '' ? $htmlControl['VALUE'] : '&nbsp;';
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDate::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function getAdminListEditHTML(array $userField, array $htmlControl): string
    {
        if ($userField['EDIT_IN_LIST'] === Truth::YES) {
            return CAdminCalendar::CalendarDate($htmlControl['NAME'], $htmlControl['VALUE']);
        }

        if ($htmlControl['VALUE'] !== '') {
            return $htmlControl['VALUE'];
        }

        return '&nbsp;';
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDate::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) why:dependency
     */
    public static function checkFields(array $userField, $value, $userId = false): array
    {
        $errors = [];

        if (is_string($value) && $value !== '' && CheckDateTime($value, FORMAT_DATE) === false) {
            $errors[] = [
                'id' => $userField['FIELD_NAME'],
                'text' => sprintf(
                    'Значение поля "%s" не является корректной датой.',
                    $userField['EDIT_FORM_LABEL'] !== '' ? $userField['EDIT_FORM_LABEL'] : $userField['FIELD_NAME']
                ),
            ];
        }

        return $errors;
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDate::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     * Добавлен проверка на DateTimeImmutable. Разрешено возвращать null-значение.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) why:dependency
     */
    public static function onBeforeSave(array $userField, $value, $userId = false)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof DateTimeImmutable) {
            return BitrixDate::createFromTimestamp($value->getTimestamp());
        }

        if ($value !== '' && ($value instanceof BitrixDate) === false) {
            // try both site's format - short and full
            try {
                $value = new BitrixDate($value);
            } catch (ObjectException $exception) {
                $value = new BitrixDate($value, BitrixDateTime::getFormat());
            }
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDate::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     *
     * TODO Возможно стоит возвращать DateTimeImmutable и добавить проверки/преобразования значения в других методах?
     */
    public static function onAfterFetch(array $userField, array $rawValue)
    {
        $value = $rawValue['VALUE'];

        // TODO Возможно стоит убрать проверку на MULTIPLE, так как нет особого смысла,
        //      данное условие было унаследовано от битрикса.
        if ($userField['MULTIPLE'] === Truth::YES && ($value instanceof BitrixDate) === false) {
            try {
                // try new independent date format
                $value = new BitrixDate($value, UserFieldTable::MULTIPLE_DATE_FORMAT);
            } catch (ObjectException $exception) {
                // try site format (sometimes it can be full site format)
                try {
                    $value = new BitrixDate($value);
                } catch (ObjectException $exception) {
                    $value = new BitrixDate($value, BitrixDateTime::getFormat());
                }
            }
        }

        return (string) $value;
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDate::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function formatField(array $userField, string $fieldName): string
    {
        global $DB;

        return $DB->DateToCharFunction($fieldName, DateTimeFormat::SHORT);
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDate::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function getPublicView(array $userField, array $params): string
    {
        $values = static::normalizeFieldValue($userField['VALUE']);

        $html = '';
        $isFirst = true;

        foreach ($values as $value) {
            if ($isFirst === false) {
                $html .= static::getHelper()->getMultipleValuesSeparator();
            }

            $isFirst = false;

            if ($userField['PROPERTY_VALUE_LINK'] !== '') {
                // phpcs:ignore Generic.Files.LineLength.TooLong
                $value = '<a href="' . htmlspecialcharsbx(str_replace('#VALUE#', urlencode($value), $userField['PROPERTY_VALUE_LINK'])) . '">' . $value . '</a>';
            }

            $html .= static::getHelper()->wrapSingleField($value);
        }

        return static::getHelper()->wrapDisplayResult($html);
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDate::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function getPublicEdit(array $userField, array $params): string
    {
        $fieldName = static::getFieldName($userField, $params);
        $values = static::getFieldValue($userField, $params);
        $html = '';
        $isFirst = true;

        foreach ($values as $value) {
            $tag = '';

            if ($isFirst === false) {
                $html .= static::getHelper()->getMultipleValuesSeparator();
            }

            $isFirst = false;
            $attributes = [];

            if (array_key_exists('attribute', $params)) {
                $attributes = array_merge($attributes, $params['attribute']);
            }

            if ($userField['EDIT_IN_LIST'] !== Truth::YES) {
                $attributes['disabled'] = 'disabled';
            } else {
                $attributes['onclick'] = 'BX.calendar({node: this, field: this, bTime: false, bSetFocus: false})';
            }

            if (isset($attributes['class']) && is_array($attributes['class'])) {
                $attributes['class'] = implode(' ', $attributes['class']);
            }

            $attributes['name'] = $fieldName;
            $attributes['type'] = 'text';
            $attributes['tabindex'] = '0';
            $attributes['value'] = $value;

            $tag .= '<input ' . static::buildTagAttributes($attributes) . '/>';
            $tag .= '<i ' . static::buildTagAttributes([
                'class' => static::getHelper()->getCssClassName() . ' icon',
                // phpcs:ignore Generic.Files.LineLength.TooLong
                'onclick' => 'BX.calendar({node: this.previousSibling, field: this.previousSibling, bTime: false, bSetFocus: false});',
            ]) . '></i>';

            $html .= static::getHelper()->wrapSingleField($tag);
        }

        if ($userField['MULTIPLE'] === Truth::YES && $params['SHOW_BUTTON'] !== Truth::NO) {
            $html .= static::getHelper()->getCloneButton($fieldName);
        }

        static::initDisplay(['date']);

        return static::getHelper()->wrapDisplayResult($html);
    }
}
