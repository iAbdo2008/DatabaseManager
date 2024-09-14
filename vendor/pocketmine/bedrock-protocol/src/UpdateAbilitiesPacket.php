<?php

/*
 * This file is part of BedrockProtocol.
 * Copyright (C) 2014-2022 PocketMine Team <https://github.com/pmmp/BedrockProtocol>
 *
 * BedrockProtocol is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\AbilitiesData;

/**
 * Updates player abilities and permissions, such as command permissions, flying/noclip, fly speed, walk speed etc.
 * Abilities may be layered in order to combine different ability sets into a resulting set.
 */
class UpdateAbilitiesPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_ABILITIES_PACKET;

	private AbilitiesData $data;

	/**
	 * @generate-create-func
	 */
	public static function create(AbilitiesData $data) : self{
		$result = new self;
		$result->data = $data;
		return $result;
	}

	public function getData() : AbilitiesData{ return $this->data; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->data = AbilitiesData::decode($in);
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$this->data->encode($out);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateAbilities($this);
	}
}
