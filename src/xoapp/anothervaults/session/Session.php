<?php

namespace xoapp\anothervaults\session;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use xoapp\anothervaults\data\DataCreator;
use xoapp\anothervaults\extension\CustomVault;
use xoapp\anothervaults\library\serializer\Serializer;
use xoapp\anothervaults\Vaults;

class Session
{

    /** @var CustomVault[] */
    private array $vaults = [];

    private readonly DataCreator $dataCreator;

    public function __construct(
        private readonly string $name
    )
    {
        $this->dataCreator = new DataCreator($this->name);
        $this->initialize();
    }

    public function initialize(): void
    {
        $savedData = $this->dataCreator->getSavedData();

        foreach ($savedData as $key => $data) {
            $this->putVault(new CustomVault($key, Serializer::deserialize($data)));
        }

        Vaults::getInstance()->getLogger()->info(TextFormat::colorize(
            "&aSuccessfully loaded &e" . sizeof($this->vaults) . "&a vaults for &e" . $this->name
        ));
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPlayer(): ?Player
    {
        return Server::getInstance()->getPlayerExact($this->name);
    }

    public function getVaults(): array
    {
        return $this->vaults;
    }

    public function putVault(CustomVault $vault): void
    {
        $this->vaults[$vault->getName()] = $vault;
    }

    public function getVault(string $name): ?CustomVault
    {
        return $this->vaults[$name] ?? null;
    }

    public function deleteVault(string $name): void
    {
        $this->dataCreator->delete($name);
        unset($this->vaults[$name]);
    }

    public function save(): void
    {
        array_walk($this->vaults,
            fn (CustomVault $vault) => $this->dataCreator->set($vault->getName(), $vault->serialize())
        );
    }
}