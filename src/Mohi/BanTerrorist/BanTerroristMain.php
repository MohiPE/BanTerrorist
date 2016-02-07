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
		$this->terrorist = json_decode(Utils::getURL("https://raw.githubusercontent.com/Stabind/MohiPE/master/ip.json"), true);
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
		if($uuid == $this->banDB[$player->getPlayer()])
			$event->setKickMessage($reason)
		for($i = 0;$i < count($this->terrorist);$i++) {
			 if($this->terrorist[i] == $player->getAddress()) {
				$this->getServer()->broadcastMessage( TextFormat::RED ."[BanTerrorist]테러범이 접속하였습니다.");
				$this->getServer()->broadcastMessage( TextFormat::RED ."[BanTerrorist]곧 기기밴 됩니다.");
				$this->banDB[$player->getName()] = $uuid;
				// UUIDBan::getInstance()->AddBan($player, $reason);
				$this->getServer()->broadcastMessage( TextFormat::RED ."[BanTerrorist]기기밴 완료");
				return true;
			 }
		}
		public function loadDB() {
			$this->banDB = new Config($this->getDataFolder()."BanDB.yml",Config::YAML);
		}
		/*foreach($this->terrorist as $list) {
			if($this->terrorist[$list] == $player->getAddress()) {
				$this->getServer()->broadcastMessage( TextFormat::RED ."[서버]테러범이 접속하였습니다.");
				$this->getServer()->broadcastMessage( TextFormat::RED ."[서버]곧 기기밴 됩니다.");
				UUIDBan::getInstance()->AddBan($player, $reason);
				$this->getServer()->broadcastMessage( TextFormat::RED ."[서버]기기밴 완료");
				return true;
			}
		}*/
	}
}
?>