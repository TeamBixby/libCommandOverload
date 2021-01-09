<?php

declare(strict_types=1);

namespace alvin0319\libCommandOverload\parameter;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class StringParameter extends Parameter{

	public function canParse(CommandSender $sender, string $argument) : bool{
		return true;
	}

	public function parse(CommandSender $sender, string $argument){
		return $argument;
	}

	public function getNetworkType() : int{
		return AvailableCommandsPacket::ARG_TYPE_STRING;
	}

	public function getTargetName() : string{
		return "string";
	}
}