<?php

declare(strict_types=1);

namespace alvin0319\libCommandOverload\parameter;

use pocketmine\command\CommandSender;
use pocketmine\IPlayer;
use pocketmine\lang\BaseLang;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\CommandEnum;
use pocketmine\Player;
use pocketmine\Server;
use function array_map;

class PlayerParameter extends EnumParameter{
	/** @var bool */
	protected $includeOffline = false;

	public function __construct(string $name, bool $optional = false, bool $exact = false, bool $caseSensitive = false, bool $includeOffline = false){
		parent::__construct($name, $optional, $exact, $caseSensitive);
		$this->includeOffline = $includeOffline;
	}

	public function setIncludeOffline(bool $includeOffline) : self{
		$this->includeOffline = $includeOffline;
		return $this;
	}

	public function isIncludeOffline() : bool{
		return $this->includeOffline;
	}

	public function canParse(CommandSender $sender, string $argument) : bool{
		if(!parent::canParse($sender, $argument)){
			return false;
		}
		return Player::isValidUserName($argument);
	}

	public function parse(CommandSender $sender, string $argument){
		return $this->parseSilent($sender, $argument);
	}

	public function getNetworkType() : int{
		return AvailableCommandsPacket::ARG_TYPE_TARGET;
	}

	public function getTargetName() : string{
		return "target";
	}

	public function getFailMessage(BaseLang $language) : string{
		return $language->translateString("%commands.generic.player.notFound");
	}

	/**
	 * @return IPlayer|null
	 */
	public function parseSilent(CommandSender $sender, string $argument){
		/** @var string|null $value */
		$value = parent::parseSilent($sender, $argument);
		if($value !== null){
			if(!$this->isIncludeOffline()){
				return $sender->getServer()->getPlayer($value);
			}
			if($sender->getServer()->hasOfflinePlayerData($value)){
				return $sender->getServer()->getOfflinePlayer($value);
			}
		}
		return null;
	}

	public function prepare() : void{
		$this->enum = new CommandEnum();
		$this->enum->enumName = "player";
		$this->enum->enumValues = array_map(function(Player $player) : string{
			if(mb_strpos($player->getName(), " ") !== false){
				return "\"{$player->getName()}\"";
			}
			return $player->getName();
		}, Server::getInstance()->getOnlinePlayers());
	}
}