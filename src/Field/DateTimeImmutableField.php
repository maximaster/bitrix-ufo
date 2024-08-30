<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Field;

use Bitrix\Main\ObjectException;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime as BitrixDateTime;
use Bitrix\Main\UserField\TypeBase;
use CAdminCalendar;
use CDatabase;
use CLang;
use CTimeZone;
use DateTimeImmutable;
use Maximaster\BitrixEnums\Main\DateTimeFormat;
use Maximaster\BitrixEnums\Main\Truth;
use Maximaster\BitrixEnums\Main\UserFieldBaseType;
use Maximaster\BitrixTableClasses\Table\Main\UserFieldTable;
use Maximaster\BitrixTableFields\Field\DateTimeImmutableField as DateTimeImmutableTableField;
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
 * Поле, которое хранит представление неизменной даты и времени.
 *
 * @SuppressWarnings(PHPMD.Superglobals) why:dependency
 * @SuppressWarnings(PHPMD.CamelCaseVariableName) why:dependency
 * @SuppressWarnings(PHPMD.ElseExpression) TODO refactor
 * @SuppressWarnings(PHPMD.CyclomaticComplexity) TODO refactor
 * @SuppressWarnings(PHPMD.NPathComplexity) TODO refactor
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity) TODO refactor
 */
class DateTimeImmutableField extends TypeBase implements
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
        return 'ufo_date_time_immutable';
    }

    /**
     * {@inheritDoc}
     */
    public static function description(): string
    {
        return 'Представление неизменной даты и времени.';
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
        return 'DATETIME';
    }

    /**
     * {@inheritDoc}
     *
     * @throws SystemException
     */
    public static function getEntityField(string $name, array $parameters): Field
    {
        return DateTimeImmutableTableField::on(ensure_nestring($name), $parameters);
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDateTime::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
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
                    CLang::GetDateFormat(DateTimeFormat::FULL),
                    'YYYY-MM-DD HH:MI:SS'
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
            'USE_SECOND' => $userField['SETTINGS']['USE_SECOND'] === Truth::NO
                ? Truth::NO
                : Truth::YES,
        ];
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDateTime::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function getSettingsHTML($userField, array $htmlControl, bool $isVarsFromForm): string
    {
        $result = '';

        if ($isVarsFromForm) {
            $type = $GLOBALS[$htmlControl['NAME']]['DEFAULT_VALUE']['TYPE'];
            $value = $GLOBALS[$htmlControl['NAME']]['DEFAULT_VALUE']['VALUE'];
        } elseif (is_array($userField) && is_array($userField['SETTINGS']['DEFAULT_VALUE'])) {
            $type = $userField['SETTINGS']['DEFAULT_VALUE']['TYPE'];
            $value = str_replace(
                ' 00:00:00',
                '',
                CDatabase::FormatDate(
                    $userField['SETTINGS']['DEFAULT_VALUE']['VALUE'],
                    'YYYY-MM-DD HH:MI:SS',
                    CLang::GetDateFormat(DateTimeFormat::FULL)
                )
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
            . CAdminCalendar::CalendarDate($htmlControl['NAME'] . '[DEFAULT_VALUE][VALUE]', $value, 20, true)
            . '</label><br>
			</td>
		</tr>
		';

        if ($isVarsFromForm) {
            $value = $GLOBALS[$htmlControl['NAME']]['USE_SECOND'] === Truth::NO
                ? Truth::NO
                : Truth::YES;
        } elseif (is_array($userField)) {
            $value = $userField['SETTINGS']['USE_SECOND'] === Truth::NO ? Truth::NO : Truth::YES;
        } else {
            $value = Truth::YES;
        }

        $result .= '
		<tr>
			<td class="adm-detail-valign-top">Использовать секунды:</td>
			<td>
				<input type="hidden" name="' . $htmlControl['NAME'] . '[USE_SECOND]" value="N" />
				<label><input type="checkbox" value="Y" name="' . $htmlControl['NAME'] . '[USE_SECOND]" '
            . ($value === Truth::YES ? ' checked="checked"' : '') . '/>&nbsp;да</label>
			</td>
		</tr>
		';

        return $result;
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDateTime::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function getEditFormHTML(array $userField, array $htmlControl): string
    {
        $htmlControl['VALIGN'] = 'middle';

        if ($userField['EDIT_IN_LIST'] === Truth::YES) {
            if ((int) $userField['ENTITY_VALUE_ID'] < 1 && $userField['SETTINGS']['DEFAULT_VALUE']['TYPE'] !== 'NONE') {
                if ($userField['SETTINGS']['DEFAULT_VALUE']['TYPE'] === 'NOW') {
                    $htmlControl['VALUE'] = ConvertTimeStamp(time() + CTimeZone::GetOffset(), DateTimeFormat::FULL);
                } else {
                    $htmlControl['VALUE'] = str_replace(
                        ' 00:00:00',
                        '',
                        CDatabase::FormatDate(
                            $userField['SETTINGS']['DEFAULT_VALUE']['VALUE'],
                            'YYYY-MM-DD HH:MI:SS',
                            CLang::GetDateFormat(DateTimeFormat::FULL)
                        )
                    );
                }
            }

            return CAdminCalendar::CalendarDate($htmlControl['NAME'], $htmlControl['VALUE'], 20, true);
        }

        if ($htmlControl['VALUE'] !== '') {
            return $htmlControl['VALUE'];
        }

        return '&nbsp;';
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDateTime::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
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
     * Перенесён из CUserTypeDateTime::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function getFilterData(array $userField, array $htmlControl): array
    {
        return [
            'id' => $htmlControl['ID'],
            'name' => $htmlControl['NAME'],
            'type' => 'date',
            'time' => true,
        ];
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDateTime::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function getAdminListViewHTML(array $userField, array $htmlControl): string
    {
        return $htmlControl['VALUE'] !== '' ? $htmlControl['VALUE'] : '&nbsp;';
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDateTime::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function getAdminListEditHTML(array $userField, array $htmlControl): string
    {
        if ($userField['EDIT_IN_LIST'] === Truth::YES) {
            return CAdminCalendar::CalendarDate($htmlControl['NAME'], $htmlControl['VALUE'], 20, true);
        }

        if ($htmlControl['VALUE'] !== '') {
            return $htmlControl['VALUE'];
        }

        return '&nbsp;';
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDateTime::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) why:dependency
     */
    public static function checkFields(array $userField, $value, $userId = false): array
    {
        if ($value instanceof DateTimeImmutable) {
            return [];
        }

        $errors = [];
        $value = (string) $value;

        if ($value !== '') {
            try {
                BitrixDateTime::createFromUserTime($value);
            } catch (ObjectException $exception) {
                $errors[] = [
                    'id' => $userField['FIELD_NAME'],
                    'text' => sprintf(
                        'Значение поля "%s" не является корректной датой.',
                        $userField['EDIT_FORM_LABEL'] !== '' ? $userField['EDIT_FORM_LABEL'] : $userField['FIELD_NAME']
                    ),
                ];
            }
        }

        return $errors;
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDateTime::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     * Добавлен проверка на DateTimeImmutable. Разрешено возвращать null-значение;
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) why:dependency
     */
    public static function onBeforeSave(array $userField, $value, $userId = false)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof DateTimeImmutable) {
            return BitrixDateTime::createFromTimestamp($value->getTimestamp());
        }

        if ($value !== '' && ($value instanceof BitrixDateTime) === false) {
            $value = BitrixDateTime::createFromUserTime($value);
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDateTime::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     *
     * TODO Возможно стоит возвращать DateTimeImmutable и добавить проверки/преобразования значения в других методах?
     */
    public static function onAfterFetch(array $userField, array $rawValue)
    {
        $value = $rawValue['VALUE'];

        // TODO Возможно стоит убрать проверку на MULTIPLE, так как нет особого смысла,
        //      данное условие было унаследовано от битрикса.
        if ($userField['MULTIPLE'] === Truth::YES && ($value instanceof BitrixDateTime) === false) {
            // Invalid value
            if (strlen($value) <= 1) {
                // will be ignored by the caller
                return null;
            }

            try {
                // try new independent datetime format
                $value = new BitrixDateTime($value, UserFieldTable::MULTIPLE_DATETIME_FORMAT);
            } catch (ObjectException $exception) {
                // try site format
                try {
                    $value = new BitrixDateTime($value);
                } catch (ObjectException $exception) {
                    // try short format
                    $value = BitrixDateTime::createFromUserTime($value);
                }
            }
            // TODO Возможно стоит убрать данное условие, так как нет особого смысла,
            //      данное условие было унаследовано от битрикса.
        } elseif (($value instanceof BitrixDateTime) === false) {
            // is not necessary, $value is a valid string already
            // $value = Type\DateTime::createFromUserTime($value);
        }

        return (string) $value;
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDateTime::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function formatField(array $userField, string $fieldName): string
    {
        global $DB;

        return $DB->DateToCharFunction($fieldName, DateTimeFormat::FULL);
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDateTime::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    public static function getPublicView(array $userField, array $params): string
    {
        $values = static::normalizeFieldValue($userField['VALUE']);

        $html = '';
        $isFirst = true;

        foreach ($values as $value) {
            $value = CDatabase::FormatDate(
                $value,
                CLang::GetDateFormat(DateTimeFormat::FULL),
                static::getFormat($value, $userField)
            );

            if ($isFirst === false) {
                $html .= static::getHelper()->getMultipleValuesSeparator();
            }

            $isFirst = false;

            if ($userField['PROPERTY_VALUE_LINK'] !== '') {
                $value = '<a href="'
                    . htmlspecialcharsbx(str_replace('#VALUE#', urlencode($value), $userField['PROPERTY_VALUE_LINK']))
                    . '">' . $value . '</a>';
            }

            $html .= static::getHelper()->wrapSingleField($value);
        }

        return static::getHelper()->wrapDisplayResult($html);
    }

    /**
     * {@inheritDoc}
     *
     * Перенесён из CUserTypeDateTime::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
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
                // phpcs:ignore Generic.Files.LineLength.TooLong
                $attributes['onclick'] = 'BX.calendar({node: this, field: this, bTime: true, bSetFocus: false, bUseSecond: '
                    . ($userField['SETTINGS']['USE_SECOND'] === Truth::NO ? 'false' : 'true') . '})';
            }

            if (isset($attributes['class']) && is_array($attributes['class'])) {
                $attributes['class'] = implode(' ', $attributes['class']);
            }

            $attributes['name'] = $fieldName;
            $attributes['type'] = 'text';
            $attributes['tabindex'] = '0';
            $attributes['value'] = CDatabase::FormatDate(
                $value,
                CLang::GetDateFormat(DateTimeFormat::FULL),
                static::getFormat($value, $userField)
            );

            $tag .= '<input ' . static::buildTagAttributes($attributes) . '/>';
            $tag .= '<i ' . static::buildTagAttributes([
                'class' => static::getHelper()->getCssClassName() . ' icon',
                // phpcs:ignore Generic.Files.LineLength.TooLong
                'onclick' => 'BX.calendar({node: this.previousSibling, field: this.previousSibling, bTime: true, bSetFocus: false, bUseSecond: '
                    . ($userField['SETTINGS']['USE_SECOND'] === Truth::NO ? 'false' : 'true') . '});',
            ]) . '></i>';

            $html .= static::getHelper()->wrapSingleField($tag);
        }

        if ($userField['MULTIPLE'] === Truth::YES && $params['SHOW_BUTTON'] !== Truth::NO) {
            $html .= static::getHelper()->getCloneButton($fieldName);
        }

        static::initDisplay(['date']);

        return static::getHelper()->wrapDisplayResult($html);
    }

    /**
     * Перенесён из CUserTypeDateTime::class с небольшим рефакторингом из-за того, что некоторые методы не статические.
     */
    protected static function getFormat($value, array $userField)
    {
        $format = CLang::GetDateFormat(DateTimeFormat::FULL);

        if ($userField['SETTINGS']['USE_SECOND'] === Truth::NO && (MakeTimeStamp($value) % 60) <= 0) {
            $format = str_replace(':SS', '', $format);
        }

        return $format;
    }
}
