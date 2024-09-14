<?php

/*
 * PocketMine Standard PHP Library
 * Copyright (C) 2019 PocketMine Team <https://github.com/pmmp/PocketMine-SPL>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
*/

declare(strict_types=1);

namespace pocketmine\errorhandler;

final class ErrorTypeToStringMap{
	private const ERROR_STRINGS = [
		0 => "EXCEPTION",
		E_ERROR => "E_ERROR",
		E_WARNING => "E_WARNING",
		E_PARSE => "E_PARSE",
		E_NOTICE => "E_NOTICE",
		E_CORE_ERROR => "E_CORE_ERROR",
		E_CORE_WARNING => "E_CORE_WARNING",
		E_COMPILE_ERROR => "E_COMPILE_ERROR",
		E_COMPILE_WARNING => "E_COMPILE_WARNING",
		E_USER_ERROR => "E_USER_ERROR",
		E_USER_WARNING => "E_USER_WARNING",
		E_USER_NOTICE => "E_USER_NOTICE",
		E_STRICT => "E_STRICT",
		E_RECOVERABLE_ERROR => "E_RECOVERABLE_ERROR",
		E_DEPRECATED => "E_DEPRECATED",
		E_USER_DEPRECATED => "E_USER_DEPRECATED"
	];

	private function __construct(){

	}

	/**
	 * Converts an E_* error constant to its string representation.
	 *
	 * @throws \InvalidArgumentException
	 */
	public static function get(int $errorType) : string{
		if(!isset(self::ERROR_STRINGS[$errorType])){
			throw new \InvalidArgumentException("Invalid error type $errorType");
		}

		return self::ERROR_STRINGS[$errorType];
	}
}
