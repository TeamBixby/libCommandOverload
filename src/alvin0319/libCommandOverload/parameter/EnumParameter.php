<?php

declare(strict_types=1);

namespace alvin0319\libCommandOverload\parameter;

use pocketmine\command\CommandSender;

use function mb_strpos;
use function mb_strtolower;

abstract class EnumParameter extends Parameter{
	/** @var bool */
	protected $exact = false;

	/** @var bool */
	protected $caseSensitive = false;

	public function __construct(string $name, bool $optional = false, bool $exact = false, bool $caseSensitive = false){
		parent::__construct($name, $optional);
		$this->exact = $exact;
		$this->caseSensitive = $caseSensitive;
	}

	public function setExact(bool $exact) : self{
		$this->exact = $exact;
		return $this;
	}

	public function isExact() : bool{
		return $this->exact;
	}

	public function setCaseSensitive(bool $caseSensitive) : self{
		$this->caseSensitive = $caseSensitive;
		return $this;
	}

	public function isCaseSensitive() : bool{
		return $this->caseSensitive;
	}

	/**
	 * @return mixed
	 */
	public function parseSilent(CommandSender $sender, string $argument){
		if($this->enum !== null){
			if($this->isExact()){
				foreach($this->enum->enumValues as $name => $value){
					if(($this->isCaseSensitive() ? $argument : mb_strtolower($argument)) === $value){
						return $value;
					}
				}
				return null;
			}
			foreach($this->enum->enumValues as $name => $value){
				if(mb_strpos($value, $this->isCaseSensitive() ? $argument : mb_strtolower($argument)) !== false){
					return $value;
				}
			}
		}
		return null;
	}
}