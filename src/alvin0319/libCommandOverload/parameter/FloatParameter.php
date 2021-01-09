<?php

declare(strict_types=1);

namespace alvin0319\libCommandOverload\parameter;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class FloatParameter extends NumericParameter{

	public function parse(CommandSender $sender, string $argument){
		return (float) $argument;
	}

	public function getNetworkType() : int{
		return AvailableCommandsPacket::ARG_TYPE_FLOAT;
	}

	public function getTargetName() : string{
		return "float";
	}
}