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

/**
 * Methods for working with binary strings
 */
namespace pocketmine\utils;

use InvalidArgumentException;
use function chr;
use function ord;
use function pack;
use function preg_replace;
use function round;
use function sprintf;
use function strlen;
use function substr;
use function unpack;
use const PHP_INT_MAX;

class Binary{
	private const SIZEOF_SHORT = 2;
	private const SIZEOF_INT = 4;
	private const SIZEOF_LONG = 8;

	private const SIZEOF_FLOAT = 4;
	private const SIZEOF_DOUBLE = 8;

	public static function signByte(int $value) : int{
		return $value << 56 >> 56;
	}

	public static function unsignByte(int $value) : int{
		return $value & 0xff;
	}

	public static function signShort(int $value) : int{
		return $value << 48 >> 48;
	}

	public static function unsignShort(int $value) : int{
		return $value & 0xffff;
	}

	public static function signInt(int $value) : int{
		return $value << 32 >> 32;
	}

	public static function unsignInt(int $value) : int{
		return $value & 0xffffffff;
	}

	public static function flipShortEndianness(int $value) : int{
		return self::readLShort(self::writeShort($value));
	}

	public static function flipIntEndianness(int $value) : int{
		return self::readLInt(self::writeInt($value));
	}

	public static function flipLongEndianness(int $value) : int{
		return self::readLLong(self::writeLong($value));
	}

	/**
	 * @return mixed[]
	 * @throws BinaryDataException
	 */
	private static function safeUnpack(string $formatCode, string $bytes, int $needLength) : array{
		$haveLength = strlen($bytes);
		if($haveLength < $needLength){
			throw new BinaryDataException("Not enough bytes: need $needLength, have $haveLength");
		}
		//unpack SUCKS SO BADLY. We really need an extension to replace this garbage :(
		$result = unpack($formatCode, $bytes);
		if($result === false){
			//this should never happen; we checked the length above
			throw new \AssertionError("unpack() failed for unknown reason");
		}
		return $result;
	}

	/**
	 * Reads a byte boolean
	 */
	public static function readBool(string $b) : bool{
		return $b[0] !== "\x00";
	}

	/**
	 * Writes a byte boolean
	 */
	public static function writeBool(bool $b) : string{
		return $b ? "\x01" : "\x00";
	}

	/**
	 * Reads an unsigned byte (0 - 255)
	 *
	 * @throws BinaryDataException
	 */
	public static function readByte(string $c) : int{
		if($c === ""){
			throw new BinaryDataException("Expected a string of length 1");
		}
		return ord($c[0]);
	}

	/**
	 * Reads a signed byte (-128 - 127)
	 *
	 * @throws BinaryDataException
	 */
	public static function readSignedByte(string $c) : int{
		if($c === ""){
			throw new BinaryDataException("Expected a string of length 1");
		}
		return self::signByte(ord($c[0]));
	}

	/**
	 * Writes an unsigned/signed byte
	 */
	public static function writeByte(int $c) : string{
		return chr($c);
	}

	/**
	 * Reads a 16-bit unsigned big-endian number
	 *
	 * @throws BinaryDataException
	 */
	public static function readShort(string $str) : int{
		return self::safeUnpack("n", $str, self::SIZEOF_SHORT)[1];
	}

	/**
	 * Reads a 16-bit signed big-endian number
	 *
	 * @throws BinaryDataException
	 */
	public static function readSignedShort(string $str) : int{
		return self::signShort(self::safeUnpack("n", $str, self::SIZEOF_SHORT)[1]);
	}

	/**
	 * Writes a 16-bit signed/unsigned big-endian number
	 */
	public static function writeShort(int $value) : string{
		return pack("n", $value);
	}

	/**
	 * Reads a 16-bit unsigned little-endian number
	 *
	 * @throws BinaryDataException
	 */
	public static function readLShort(string $str) : int{
		return self::safeUnpack("v", $str, self::SIZEOF_SHORT)[1];
	}

	/**
	 * Reads a 16-bit signed little-endian number
	 *
	 * @throws BinaryDataException
	 */
	public static function readSignedLShort(string $str) : int{
		return self::signShort(self::safeUnpack("v", $str, self::SIZEOF_SHORT)[1]);
	}

	/**
	 * Writes a 16-bit signed/unsigned little-endian number
	 */
	public static function writeLShort(int $value) : string{
		return pack("v", $value);
	}

	/**
	 * Reads a 3-byte big-endian number
	 *
	 * @throws BinaryDataException
	 */
	public static function readTriad(string $str) : int{
		return self::safeUnpack("N", "\x00" . $str, self::SIZEOF_INT)[1];
	}

	/**
	 * Writes a 3-byte big-endian number
	 */
	public static function writeTriad(int $value) : string{
		return substr(pack("N", $value), 1);
	}

	/**
	 * Reads a 3-byte little-endian number
	 *
	 * @throws BinaryDataException
	 */
	public static function readLTriad(string $str) : int{
		return self::safeUnpack("V", $str . "\x00", self::SIZEOF_INT)[1];
	}

	/**
	 * Writes a 3-byte little-endian number
	 */
	public static function writeLTriad(int $value) : string{
		return substr(pack("V", $value), 0, -1);
	}

	/**
	 * Reads a 4-byte signed integer
	 *
	 * @throws BinaryDataException
	 */
	public static function readInt(string $str) : int{
		return self::signInt(self::safeUnpack("N", $str, self::SIZEOF_INT)[1]);
	}

	/**
	 * Writes a 4-byte integer
	 */
	public static function writeInt(int $value) : string{
		return pack("N", $value);
	}

	/**
	 * Reads a 4-byte signed little-endian integer
	 *
	 * @throws BinaryDataException
	 */
	public static function readLInt(string $str) : int{
		return self::signInt(self::safeUnpack("V", $str, self::SIZEOF_INT)[1]);
	}

	/**
	 * Writes a 4-byte signed little-endian integer
	 */
	public static function writeLInt(int $value) : string{
		return pack("V", $value);
	}

	/**
	 * Reads a 4-byte floating-point number
	 *
	 * @throws BinaryDataException
	 */
	public static function readFloat(string $str) : float{
		return self::safeUnpack("G", $str, self::SIZEOF_FLOAT)[1];
	}

	/**
	 * Reads a 4-byte floating-point number, rounded to the specified number of decimal places.
	 *
	 * @throws BinaryDataException
	 */
	public static function readRoundedFloat(string $str, int $accuracy) : float{
		return round(self::readFloat($str), $accuracy);
	}

	/**
	 * Writes a 4-byte floating-point number.
	 */
	public static function writeFloat(float $value) : string{
		return pack("G", $value);
	}

	/**
	 * Reads a 4-byte little-endian floating-point number.
	 *
	 * @throws BinaryDataException
	 */
	public static function readLFloat(string $str) : float{
		return self::safeUnpack("g", $str, self::SIZEOF_FLOAT)[1];
	}

	/**
	 * Reads a 4-byte little-endian floating-point number rounded to the specified number of decimal places.
	 *
	 * @throws BinaryDataException
	 */
	public static function readRoundedLFloat(string $str, int $accuracy) : float{
		return round(self::readLFloat($str), $accuracy);
	}

	/**
	 * Writes a 4-byte little-endian floating-point number.
	 */
	public static function writeLFloat(float $value) : string{
		return pack("g", $value);
	}

	/**
	 * Returns a printable floating-point number.
	 */
	public static function printFloat(float $value) : string{
		return preg_replace("/(\\.\\d+?)0+$/", "$1", sprintf("%F", $value));
	}

	/**
	 * Reads an 8-byte floating-point number.
	 *
	 * @throws BinaryDataException
	 */
	public static function readDouble(string $str) : float{
		return self::safeUnpack("E", $str, self::SIZEOF_DOUBLE)[1];
	}

	/**
	 * Writes an 8-byte floating-point number.
	 */
	public static function writeDouble(float $value) : string{
		return pack("E", $value);
	}

	/**
	 * Reads an 8-byte little-endian floating-point number.
	 *
	 * @throws BinaryDataException
	 */
	public static function readLDouble(string $str) : float{
		return self::safeUnpack("e", $str, self::SIZEOF_DOUBLE)[1];
	}

	/**
	 * Writes an 8-byte floating-point little-endian number.
	 */
	public static function writeLDouble(float $value) : string{
		return pack("e", $value);
	}

	/**
	 * Reads an 8-byte integer.
	 *
	 * @throws BinaryDataException
	 */
	public static function readLong(string $str) : int{
		return self::safeUnpack("J", $str, self::SIZEOF_LONG)[1];
	}

	/**
	 * Writes an 8-byte integer.
	 */
	public static function writeLong(int $value) : string{
		return pack("J", $value);
	}

	/**
	 * Reads an 8-byte little-endian integer.
	 *
	 * @throws BinaryDataException
	 */
	public static function readLLong(string $str) : int{
		return self::safeUnpack("P", $str, self::SIZEOF_LONG)[1];
	}

	/**
	 * Writes an 8-byte little-endian integer.
	 */
	public static function writeLLong(int $value) : string{
		return pack("P", $value);
	}

	/**
	 * Reads a 32-bit zigzag-encoded variable-length integer.
	 *
	 * @param int    $offset reference parameter
	 *
	 * @throws BinaryDataException
	 */
	public static function readVarInt(string $buffer, int &$offset) : int{
		$raw = self::readUnsignedVarInt($buffer, $offset);
		$temp = ((($raw << 63) >> 63) ^ $raw) >> 1;
		return $temp ^ ($raw & (1 << 63));
	}

	/**
	 * Reads a 32-bit variable-length unsigned integer.
	 *
	 * @param int    $offset reference parameter
	 *
	 * @throws BinaryDataException if the var-int did not end after 5 bytes or there were not enough bytes
	 */
	public static function readUnsignedVarInt(string $buffer, int &$offset) : int{
		$value = 0;
		for($i = 0; $i <= 28; $i += 7){
			if(!isset($buffer[$offset])){
				throw new BinaryDataException("No bytes left in buffer");
			}
			$b = ord($buffer[$offset++]);
			$value |= (($b & 0x7f) << $i);

			if(($b & 0x80) === 0){
				return $value;
			}
		}

		throw new BinaryDataException("VarInt did not terminate after 5 bytes!");
	}

	/**
	 * Writes a 32-bit integer as a zigzag-encoded variable-length integer.
	 */
	public static function writeVarInt(int $v) : string{
		$v = ($v << 32 >> 32);
		return self::writeUnsignedVarInt(($v << 1) ^ ($v >> 31));
	}

	/**
	 * Writes a 32-bit unsigned integer as a variable-length integer.
	 *
	 * @return string up to 5 bytes
	 */
	public static function writeUnsignedVarInt(int $value) : string{
		$buf = "";
		$remaining = $value & 0xffffffff;
		for($i = 0; $i < 5; ++$i){
			if(($remaining >> 7) !== 0){
				$buf .= chr($remaining | 0x80);
			}else{
				$buf .= chr($remaining & 0x7f);
				return $buf;
			}

			$remaining = (($remaining >> 7) & (PHP_INT_MAX >> 6)); //PHP really needs a logical right-shift operator
		}

		throw new InvalidArgumentException("Value too large to be encoded as a VarInt");
	}

	/**
	 * Reads a 64-bit zigzag-encoded variable-length integer.
	 *
	 * @param int    $offset reference parameter
	 *
	 * @throws BinaryDataException
	 */
	public static function readVarLong(string $buffer, int &$offset) : int{
		$raw = self::readUnsignedVarLong($buffer, $offset);
		$temp = ((($raw << 63) >> 63) ^ $raw) >> 1;
		return $temp ^ ($raw & (1 << 63));
	}

	/**
	 * Reads a 64-bit unsigned variable-length integer.
	 *
	 * @param int    $offset reference parameter
	 *
	 * @throws BinaryDataException if the var-int did not end after 10 bytes or there were not enough bytes
	 */
	public static function readUnsignedVarLong(string $buffer, int &$offset) : int{
		$value = 0;
		for($i = 0; $i <= 63; $i += 7){
			if(!isset($buffer[$offset])){
				throw new BinaryDataException("No bytes left in buffer");
			}
			$b = ord($buffer[$offset++]);
			$value |= (($b & 0x7f) << $i);

			if(($b & 0x80) === 0){
				return $value;
			}
		}

		throw new BinaryDataException("VarLong did not terminate after 10 bytes!");
	}

	/**
	 * Writes a 64-bit integer as a zigzag-encoded variable-length long.
	 */
	public static function writeVarLong(int $v) : string{
		return self::writeUnsignedVarLong(($v << 1) ^ ($v >> 63));
	}

	/**
	 * Writes a 64-bit unsigned integer as a variable-length long.
	 */
	public static function writeUnsignedVarLong(int $value) : string{
		$buf = "";
		$remaining = $value;
		for($i = 0; $i < 10; ++$i){
			if(($remaining >> 7) !== 0){
				$buf .= chr($remaining | 0x80); //Let chr() take the last byte of this, it's faster than adding another & 0x7f.
			}else{
				$buf .= chr($remaining & 0x7f);
				return $buf;
			}

			$remaining = (($remaining >> 7) & (PHP_INT_MAX >> 6)); //PHP really needs a logical right-shift operator
		}

		throw new InvalidArgumentException("Value too large to be encoded as a VarLong");
	}
}
