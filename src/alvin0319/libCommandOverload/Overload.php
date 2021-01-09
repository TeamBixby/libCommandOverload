<?php

declare(strict_types=1);

namespace alvin0319\libCommandOverload;

use alvin0319\libCommandOverload\parameter\Parameter;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\utils\Utils;

use function count;
use function implode;

class Overload{
	/** @var \Closure|null */
	protected $commandHandler = null;
	/** @var Parameter[] */
	protected $parameters = [];

	public function __construct(?\Closure $commandHandler = null){
		if($commandHandler !== null){
			Utils::validateCallableSignature(function(CommandSender $sender, array $args) : void{
			}, $commandHandler);
		}
		$this->commandHandler = $commandHandler;
	}

	public function addParameter(Parameter $parameter) : self{
		if(count($this->parameters) !== 0){
			foreach($this->parameters as $oldParameter){
				if($parameter->getName() === $oldParameter->getName()){
					throw new CommandException("Cannot register multiple parameters with the same name");
				}
			}
		}
		$parameter->setOverload($this);
		$this->parameters[] = $parameter;
		return $this;
	}

	/**
	 * @return Parameter[]
	 */
	public function getParameters() : array{
		$parameters = [];
		foreach($this->parameters as $position => $parameter){
			$parameter->prepare();
			$parameters[] = $parameter;
		}
		return $parameters;
	}

	/**
	 * @param string[] $args
	 */
	public function canParse(CommandSender $sender, array $args) : bool{
		$argsCount = count($args);
		$parameterCount = count($this->parameters);

		if($argsCount < $parameterCount){
			throw new InvalidCommandSyntaxException();
		}
		$offset = 0;
		$parsed = false;
		foreach($this->getParameters() as $parameter){
			if($offset > $parameterCount){
				break;
			}
			if($parameter->getLength() === PHP_INT_MAX){
				$parsed = true;
				break;
			}
			$argument = implode(" ", array_slice($args, $offset, $parameter->getLength()));
			if(!$parameter->canParse($sender, $argument)){
				break;
			}
			if(!$parameter->isOptional){
				$offset += $parameter->getLength();
			}
			$parsed = true;
		}
		return $parsed;
	}

	public function getCommandHandler() : ?\Closure{
		return $this->commandHandler;
	}

	public function setCommandHandler(?\Closure $handler) : self{
		if($handler !== null){
			Utils::validateCallableSignature(function(CommandSender $sender, array $args) : void{
			}, $handler);
		}
		$this->commandHandler = $handler;
		return $this;
	}
}