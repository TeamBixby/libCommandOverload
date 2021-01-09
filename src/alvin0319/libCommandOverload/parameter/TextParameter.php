<?php

declare(strict_types=1);

namespace alvin0319\libCommandOverload\parameter;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

use const PHP_INT_MAX;

class TextParameter extends StringParameter{

	public function __construct(string $name, bool $optional = false){
		parent::__construct($name, $optional);
		$this->length = PHP_INT_MAX;
	}

	public function getNetworkType() : int{
		return AvailableCommandsPacket::ARG_TYPE_RAWTEXT;
	}

	public function getTargetName() : string{
		return "text";
	}
}