<?php

namespace Mohi\BanTerrorist;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerEvent;
use pocketmine\Player;
use pocketmine\utils\Utils;
use pocketmine\utils\Config;

class BanTerrorist extends PluginBase implements Listener {
	public $terrorist, $banDB;
	
	public function OnEnable() {
		$this->getLogger()->alert("Made By Mohi(물외한인)");
		@mkdir($this->getDataFolder());
		$this->loadDB();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function onLogin(PlayerPreLoginEvent $event){
		$player = $event->getPlayer();
		$uuid = $player->getClientId();
		$reason = "당신은 비매너 유저입니다.";
		/*
		for($i = 0;$i < count($this->terrorist);$i++) {
			 if($this->terrorist[i] == $player->getAddress()) {
				$this->getServer()->broadcastMessage( TextFormat::RED ."[BanTerrorist]테러범이 접속하였습니다.");
				$this->getServer()->broadcastMessage( TextFormat::RED ."[BanTerrorist]곧 기기밴 됩니다.");
				$this->banDB[$player->getName()] = $uuid;
				// UUIDBan::getInstance()->AddBan($player, $reason);
				$this->getServer()->broadcastMessage( TextFormat::RED ."[BanTerrorist]기기밴 완료");
			 }
		} */
		foreach($this->banDB as $list) {
			if($list == $uuid) {
				$event->setKickMessage($reason);
				$event->setCancelled();
				return;
			}
		}
		for($i = 0; $i < count($this->terrorist); $i++) {
			if($this->terrorist[i] == $player->getAddress()) {
				$this->getServer()->broadcastMessage( TextFormat::RED ."[BanTerrorist] 비매너 플레이어가 접속하였습니다.");
				$this->getServer()->broadcastMessage( TextFormat::RED ."[BanTerrorist] 곧 기기밴 됩니다.");
				$event->setKickMessage($reason);
				$this->banDB[$player->getName()] = $uuid;
				$this->save("BanDB.yml", $this->banDB, true);
				$event->setCancelled();
				//UUIDBan::getInstance()->AddBan($player, $reason);
				$this->getServer()->broadcastMessage( TextFormat::RED ."[BanTerrorist] 기기밴 완료");
			}
		}
		/*foreach($this->terrorist as $list) {
			if($list == $player->getAddress()) {
				$this->getServer()->broadcastMessage( TextFormat::RED ."[BanTerrorist] 비매너 플레이어가 접속하였습니다.");
				$this->getServer()->broadcastMessage( TextFormat::RED ."[BanTerrorist] 곧 기기밴 됩니다.");
				$event->setKickMessage($reason);
				$this->banDB[$player->getName()] = $uuid;
				$this->save("BanDB.yml", $this->banDB, true);
				$event->setCancelled();
				//UUIDBan::getInstance()->AddBan($player, $reason);
				$this->getServer()->broadcastMessage( TextFormat::RED ."[BanTerrorist] 기기밴 완료");
			}
		}*/
	}
	public function loadDB() {
		$this->banDB = (new Config($this->getDataFolder()."BanDB.yml",Config::YAML))->getAll();
		$this->terrorist = json_decode(Utils::getURL("https://raw.githubusercontent.com/Stabind/MohiPE/master/ip.json"), true);
	}

 public function save($db, $param, $async = false) {
		$dbsave = (new Config ($this->getDataFolder().$db, Config::YAML));
		$dbsave->setAll($param);
		$dbsave->save($async);
	}
}
?>