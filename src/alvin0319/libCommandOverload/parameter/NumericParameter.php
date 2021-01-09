<?php

declare(strict_types=1);

namespace alvin0319\libCommandOverload\parameter;

use pocketmine\command\CommandSender;
use function is_numeric;

abstract class NumericParameter extends Parameter{

	public function canParse(CommandSender $sender, string $argument) : bool{
		return is_numeric($argument);
	}
}