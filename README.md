# libCommandOverload
A PocketMine-MP virion that ported [PocketMine-MP#3871](https://github.com/pmmp/PocketMine-MP/pull/3871) into virion

# Example

```php
<?php

declare(strict_types=1);

namespace alvin0319\overloadTest;

use alvin0319\libCommandOverload\Overload;
use alvin0319\libCommandOverload\PacketInjector;
use alvin0319\libCommandOverload\parameter\IntegerParameter;
use alvin0319\libCommandOverload\parameter\PlayerParameter;
use alvin0319\libCommandOverload\parameter\StringParameter;
use alvin0319\libCommandOverload\parameter\Vector3Parameter;
use alvin0319\libCommandOverload\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;

class OverloadTest extends PluginBase{

    public function onEnable() : void{
        if(!PacketInjector::isRegistered()) PacketInjector::register($this);
        $command = new BaseCommand("test", "Test command");
        $command->addOverload((new Overload())
            ->addParameter(new StringParameter("name"))
            ->addParameter(new IntegerParameter("amount"))
            ->addParameter(new Vector3Parameter("pos"))
            ->addParameter(new PlayerParameter("target"))
            ->setCommandHandler(function(CommandSender $sender, array $args) : void{
                $name = $args["name"];
                $amount = $args["amount"];
                $pos = $args["pos"];
                $target = $args["target"]->getName();
                $sender->sendMessage("Name: $name, Amount: $amount, Pos: $pos, Target: $target");
            })
        );
        $this->getServer()->getCommandMap()->register("test", $command);
    }
}
```