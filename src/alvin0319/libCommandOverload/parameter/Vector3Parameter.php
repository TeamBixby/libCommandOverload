<?php

declare(strict_types=1);

namespace alvin0319\libCommandOverload\parameter;

use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\Player;

use function count;
use function explode;
use function is_numeric;
use function substr;

use const PHP_INT_MAX;
use const PHP_INT_MIN;

class Vector3Parameter extends Parameter{

	public function __construct(string $name, bool $optional = false){
		parent::__construct($name, $optional);
		$this->length = 3;
	}

	public function canParse(CommandSender $sender, string $argument) : bool{
		$coordinateArgs = explode(" ", $argument);
		if(count($coordinateArgs) !== 3){
			return false;
		}
		return true;
	}

	public function parse(CommandSender $sender, string $argument){
		[$x, $y, $z] = explode(" ", $argument);
		if($sender instanceof Player){
			$x = $this->getCoordinates($x);
			$y = $this->getCoordinates($y);
			$z = $this->getCoordinates($z);
			return new Vector3((float) $sender->getPosition()->getX() + $x, (float) $sender->getPosition()->getY() + $y, (float) $sender->getPosition()->getZ() + $z);
		}
		if(is_numeric($x) && is_numeric($y) && is_numeric($z)){
			return new Vector3((float) $x, (float) $y, (float) $z);
		}
		return null;
	}

	private function getCoordinates(string $input) : float{
		if($input[0] === "~"){
			$input = substr($input, 1);
		}
		$i = (double) $input;

		if($i < PHP_INT_MIN){
			$i = PHP_INT_MIN;
		}elseif($i > PHP_INT_MAX){
			$i = PHP_INT_MAX;
		}

		return $i;
	}

	public function getNetworkType() : int{
		return AvailableCommandsPacket::ARG_TYPE_POSITION;
	}

	public function getTargetName() : string{
		return "x y z";
	}
}