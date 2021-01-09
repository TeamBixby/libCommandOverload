<?php

declare(strict_types=1);

namespace alvin0319\libCommandOverload;

use alvin0319\libCommandOverload\parameter\Parameter;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class BaseCommand extends Command{
	/** @var Overload[] */
	protected $overloads = [];

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return false;
		}
		foreach($this->overloads as $overload){
			if($overload->canParse($sender, $args)){
				$handler = $overload->getCommandHandler();
				if($handler !== null){
					($handler)($sender, $this->parseArguments($sender, $overload, $args));
				}
				return true;
			}
		}
		return true;
	}

	/**
	 * @param string[] $args
	 *
	 * @return array<string, mixed>
	 */
	private function parseArguments(CommandSender $sender, Overload $overload, array $args) : array{
		$result = [];
		$offset = 0;
		$parameters = $overload->getParameters();
		$argCount = count($parameters);
		usort($parameters, function(Parameter $a, Parameter $b) : int{
			if($a->getLength() === PHP_INT_MAX){
				return 1;
			}
			return -1;
		});
		foreach($parameters as $parameter){
			if($offset > $argCount){
				break;
			}
			if($parameter->getLength() === PHP_INT_MAX){
				$result[$parameter->getName()] = implode(" ", array_slice($args, $offset));
				break;
			}
			$argument = implode(" ", array_slice($args, $offset, $parameter->getLength()));
			if($parameter->canParse($sender, $argument)){
				$result[$parameter->getName()] = $parameter->parse($sender, $argument);
				$offset += $parameter->getLength();
			}
		}
		return $result;
	}

	public function addOverload(Overload $overload) : void{
		$this->overloads[] = $overload;
	}

	/**
	 * @return Overload[]
	 */
	public function getOverloads() : array{
		if(count($this->overloads) === 0){
			$this->overloads[] = new Overload();
		}
		return $this->overloads;
	}

}