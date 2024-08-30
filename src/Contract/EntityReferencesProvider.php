<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\Relations\Reference;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет метод,
 * возвращающий список ссылочных полей, привязанных к переданному полю.
 */
interface EntityReferencesProvider
{
    /**
     * Этот метод вызывается при извлечении поля из базы данных и позволяет получить список ссылочных полей,
     * привязанных к переданному полю.
     *
     * <p>Вызывается из метода getEntityReferences объекта $USER_FIELD_MANAGER.</p>
     *
     * @see CUserTypeManager::getEntityReferences
     *
     * @param array $userField массив, описывающий поле
     * @param Field $field объект поля в контексте ORM D7
     *
     * @return Reference[]
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
     */
    public static function getEntityReferences(array $userField, Field $field): array;
}
