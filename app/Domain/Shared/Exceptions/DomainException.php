<?php

namespace Domain\Shared\Exceptions;

use RuntimeException;

/**
 * Clase base para todas las excepciones del dominio.
 * Permite centralizar la jerarquía de errores de negocio.
 */
abstract class DomainException extends RuntimeException
{
    // Podrías añadir aquí métodos comunes a todas las excepciones de dominio,
    // como códigos de error personalizados, logging contextual, etc.
}
