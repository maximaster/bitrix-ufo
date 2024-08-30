<?php

declare(strict_types=1);

namespace Maximaster\BitrixUfo\Contract;

use Bitrix\Main\ORM\Fields\Field;

/**
 * Интерфейс типа пользовательского поля для Битрикс, который предоставляет метод, возвращающий поле в контексте ORM D7.
 */
interface EntityFieldProvider
{
    /**
     * Этот метод вызывается при извлечении поля из базы данных и позволяет использовать поле из ORM D7 вместо сырого
     * значения.
     *
     * <p>Вызывается из метода getEntityField объекта $USER_FIELD_MANAGER.</p>
     *
     * @see CUserTypeManager::getEntityField
     *
     * @param array $parameters @deprecated use configure* and add* methods instead
     *
     * @psalm-param non-empty-string $name
     * @psalm-param array<non-empty-string, mixed> $parameters @deprecated use configure* and add* methods instead
     */
    public static function getEntityField(string $name, array $parameters): Field;
}
