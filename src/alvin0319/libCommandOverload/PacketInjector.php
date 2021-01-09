<?php

declare(strict_types=1);

namespace alvin0319\libCommandOverload;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\AssumptionFailedError;

use function array_map;

class PacketInjector implements Listener{
	/** @var PluginBase $plugin */
	protected static $plugin;

	public static function register(PluginBase $plugin) : void{
		if(self::$plugin instanceof PluginBase){
			throw new AssumptionFailedError("Event handler already registered");
		}
		self::$plugin = $plugin;
		self::$plugin->getServer()->getPluginManager()->registerEvents(new PacketInjector(), self::$plugin);
	}

	public static function isRegistered() : bool{
		return self::$plugin instanceof PluginBase;
	}

	public function onDataPacketSend(DataPacketSendEvent $event) : void{
		$packet = $event->getPacket();
		if($packet instanceof AvailableCommandsPacket){
			foreach($packet->commandData as $name => $data){
				$command = self::$plugin->getServer()->getCommandMap()->getCommand($name);
				if($command instanceof BaseCommand){
					$data->overloads = array_map(function(Overload $overload) : array{
						return $overload->getParameters();
					}, $command->getOverloads());
				}
			}
		}
	}
}