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

class BanTerroristMain extends PluginBase implements Listener {
	public $terrorist, $banDB;
	
	public function OnEnable() {
		$this->getLogger()->alert("Made By Mohi(물외한인)");
		$this->loadDB();
		
		/*$cheakUUIDBan = $this->getServer()->getPluginManager()->getPlugin("UUIDBan");
		if($cheakUUIDBan == NULL){
			$this->getLogger()->alert("마루님의 UUIDBan 플러그인이 존재하지 않습니다!");
			$this->getLogger()->alert("플러그인을 비활성화 합니다");
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return false;
		}*/
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
				return true;
			 }
		} */
		foreach($this->banDB as $list) {
			if($list == $uuid) {
				$event->setKickMessage($reason);
				$event->setCancelled();
			}
		}
		foreach($this->terrorist as $list) {
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