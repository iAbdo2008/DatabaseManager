<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\color;

use function count;
use function intdiv;

final class Color{
	private int $a;
	private int $r;
	private int $g;
	private int $b;

	public function __construct(int $r, int $g, int $b, int $a = 0xff){
		$this->r = $r & 0xff;
		$this->g = $g & 0xff;
		$this->b = $b & 0xff;
		$this->a = $a & 0xff;
	}

	/**
	 * Returns the alpha (opacity) value of this colour.
	 */
	public function getA() : int{
		return $this->a;
	}

	/**
	 * Returns the red value of this colour.
	 */
	public function getR() : int{
		return $this->r;
	}

	/**
	 * Returns the green value of this colour.
	 */
	public function getG() : int{
		return $this->g;
	}

	/**
	 * Returns the blue value of this colour.
	 */
	public function getB() : int{
		return $this->b;
	}

	/**
	 * Mixes the supplied list of colours together to produce a result colour.
	 *
	 * @param Color ...$colors
	 */
	public static function mix(Color $color1, Color ...$colors) : Color{
		$colors[] = $color1;
		$count = count($colors);

		$a = $r = $g = $b = 0;

		foreach($colors as $color){
			$a += $color->a;
			$r += $color->r;
			$g += $color->g;
			$b += $color->b;
		}

		return new Color(intdiv($r, $count), intdiv($g, $count), intdiv($b, $count), intdiv($a, $count));
	}

	/**
	 * Returns a Color from the supplied RGB colour code (24-bit)
	 */
	public static function fromRGB(int $code) : Color{
		return new Color(($code >> 16) & 0xff, ($code >> 8) & 0xff, $code & 0xff);
	}

	/**
	 * Returns a Color from the supplied ARGB colour code (32-bit)
	 */
	public static function fromARGB(int $code) : Color{
		return new Color(($code >> 16) & 0xff, ($code >> 8) & 0xff, $code & 0xff, ($code >> 24) & 0xff);
	}

	/**
	 * Returns an ARGB 32-bit colour value.
	 */
	public function toARGB() : int{
		return ($this->a << 24) | ($this->r << 16) | ($this->g << 8) | $this->b;
	}

	/**
	 * Returns a Color from the supplied RGBA colour code (32-bit)
	 */
	public static function fromRGBA(int $code) : Color{
		return new Color(($code >> 24) & 0xff, ($code >> 16) & 0xff, ($code >> 8) & 0xff, $code & 0xff);
	}

	/**
	 * Returns an RGBA 32-bit colour value.
	 */
	public function toRGBA() : int{
		return ($this->r << 24) | ($this->g << 16) | ($this->b << 8) | $this->a;
	}

	/**
	 * Returns whether the two colors are equivalent.
	 */
	public function equals(self $other) : bool{
		return $this->a === $other->a && $this->r === $other->r && $this->g === $other->g && $this->b === $other->b;
	}
}
