<?php
/** @file
 * This file holds the fatal InterruptException.
 */
namespace Villain;

/**
 * A fatal exception.
 *
 * This is thrown for exception conditions that are grave enough
 * to stop processing.
 */
class InterruptException extends \FortissimoInterruptException {}