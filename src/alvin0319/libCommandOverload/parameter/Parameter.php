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

namespace alvin0319\libCommandOverload\parameter;

use alvin0319\libCommandOverload\Overload;
use pocketmine\command\CommandSender;
use pocketmine\lang\BaseLang;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\CommandEnum;
use pocketmine\network\mcpe\protocol\types\CommandParameter;

abstract class Parameter extends CommandParameter{
	/** @var int the length of parameter */
	protected $length = 1;

	/** @var Overload */
	protected $overload;

	public function __construct(string $name, bool $optional = false){
		$this->paramName = $name;
		$this->isOptional = $optional;
		$this->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | $this->getNetworkType();
	}

	public function getName() : string{
		return $this->paramName;
	}

	/**
	 * Returns whether parsing is possible
	 */
	public function canParse(CommandSender $sender, string $argument) : bool{
		return $this->parse($sender, $argument) !== null;
	}

	/**
	 * Returns the parsed value.
	 *
	 * @param CommandSender $sender
	 * @param string        $argument
	 *
	 * @return mixed
	 */
	abstract public function parse(CommandSender $sender, string $argument);

	/**
	 * Returns the network type of parameters
	 */
	abstract public function getNetworkType() : int;

	/**
	 * Returns the name of parameter
	 */
	abstract public function getTargetName() : string;

	/**
	 * Returns the translation of fail message
	 */
	public function getFailMessage(BaseLang $language) : string{
		return $language->translateString("%commands.generic.usage");
	}

	public function getEnum() : ?CommandEnum{
		return $this->enum;
	}

	public function prepare() : void{
	}

	public function getLength() : int{
		return $this->length;
	}

	public function getOverload() : Overload{
		return $this->overload;
	}

	public function setOverload(Overload $overload) : self{
		$this->overload = $overload;
		return $this;
	}
}