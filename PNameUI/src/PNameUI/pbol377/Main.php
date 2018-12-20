<?php
namespace PNameUI\pbol377;

use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket; 
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

class Main extends PluginBase implements Listener{
public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents ($this, $this);
		@mkdir ( $this->getDataFolder () );
      $this->nametag = new Config($this->getDataFolder() . "nametag.yml", Config::YAML);
      $this->nt = $this->nametag->getAll();
      $this->lang = new Config($this->getDataFolder() . "language.yml", Config::YAML,[
      "language" => "eng"
         ]);
      $this->lg = $this->lang->getAll();
      if($this->lg["language"]=="eng"){
      $this->getLogger()->alert("§cLanguage set to ENG for NameTag Plugin");
      }
      else if($this->lg["language"]=="kor"){
      $this->getLogger()->alert("§c§l언어가 한국어로 설정되었습니다");
      }
      else{
      	$this->getLogger()->alert("§c§lUnsupported Language at language.yml. Please check again");
      	$this->getServer()->getPluginManager()->disablePlugin($this);
      }
	}
	
public function onJoin(PlayerJoinEvent $event){
	$player = $event->getPlayer();
	$name = $player->getName();
	if(isset($this->nt[$name])){
		$player->setNameTag($this->nt[$name]);
		if($this->lg["language"]=="eng"){
			$player->sendMessage("§aNameTag set to ".$this->nt[$name].". to change it to normal ,please select initialize button on UI");
			}
		else{
			$player->sendMessage("§a§l표시 닉네임이  ".$this->nt[$name]."로 설정되었습니다. 초기화를 원하시면 UI에서 초기화 버튼을 눌러주세요");
			}
		}
	}
	
public function sendUI(Player $p, $c, $d) {
		$pack = new ModalFormRequestPacket();
		$pack->formId = $c;
		$pack->formData = $d;
		$p->dataPacket($pack);
	}

public function onCommand(Commandsender $sender, Command $command, string $label, array $args) : bool{
	$name = $sender->getName();
	$cmd = $command->getName();
	if (!$sender instanceof Player) {
		$sender->sendMessage("§c§lProhibited in Console");
		return true;
		}
	switch($cmd){
		case "nt":
		if($this->lg["language"]=="eng"){
			continue;
			}
		else{
			$sender->sendMessage ("§c§l영어 전용 명령어 입니다" );
			break;
			}
		case "닉네임":
		if($this->lg["language"]=="eng"){
			$sender->sendMessage ("§cOnly for Kor language" );
			break;
			}
		if($this->lg["language"]=="eng"){
			    if($sender->isOp()){
			    $this->sendUI($sender, 22222, $this->NameENG());
			}
			else{
			$sender->sendMessage ("§cOnly for OP" );
		 }
		}
		else{
			if($sender->isOp()){
			    $this->sendUI($sender, 22222, $this->NameKOR());
			}
			else{
			$sender->sendMessage ("§c§l명령어를 사용할 권한이 없습니다" );
		 }
	   }//lang
	break;
	}//switch
	return true;
	}
	
public function NameENG() {

         $encode = [
		"type" => "form",
		"title" => "§cNameUI",
		"content" => "§cSet prefix and nametag shown uppon your steve body\n\nv 1.0",
		"buttons" => [
		[
		"text" => "Initialize Nametag",
		],
		[
		"text" => "Set NameTag & Prefix",
		],
		[
		"text" => "Exit",
		]
		]
		];
		return json_encode($encode);
	}
	
public function NameKOR() {

         $encode = [
		"type" => "form",
		"title" => "§c§l표시 닉네임 수정",
		"content" => "§c§l표시되는 닉네임을 수정합니다\n\nv1.0",
		"buttons" => [
		[
		"text" => "§l플레이어 닉 초기화하기",
		],
		[
		"text" => "§l플레이어 닉네임 & 칭호 설정",
		],
		[
		"text" => "§l나가기",
		]
		]
		];
		return json_encode($encode);
	}
	
public function MessageE($c) {

         $encode = [
		"type" => "form",
		"title" => "§cMessage",
		"content" => $c,
		"buttons" => [
		[
		"text" => "Main",
		],
		[
		"text" => "Exit",
		]
		]
		];
		return json_encode($encode);
	}
	
public function Message($c) {

         $encode = [
		"type" => "form",
		"title" => "§c§l메세지",
		"content" => $c,
		"buttons" => [
		[
		"text" => "§l메인",
		],
		[
		"text" => "§l나가기",
		]
		]
		];
		return json_encode($encode);
	}

public function NameTagE() {
	
		$encode = [
		"type" => "custom_form",
		"title" => "Set NameTag",
		"content" => [
		[
		"type" => "input",
		"text" => "Input NameTag to set\n",
		],
		[
		"type" => "input",
		"text" => "Input Prefix to add next to NameTag\n",
		],
		[
		"type" => "input",
		"text" => "Input Player name to set Nametag. \n\nIdentification of Up, Lowercase alphabet is essential\n",
		]
		]
		];
		return json_encode($encode);
	}
	
public function NameTag() {
	
		$encode = [
		"type" => "custom_form",
		"title" => "§c§l닉네임 설정",
		"content" => [
		[
		"type" => "input",
		"text" => "§l사용할 닉네임을 입력해주세요\n",
		],
		[
		"type" => "input",
		"text" => "§l사용할 칭호를 입력해주세요\n",
		],
		[
		"type" => "input",
		"text" => "§l설정 시킬 플레이어 닉네임을 입력해주세요.\n\n 대소문자 구별은 필수입니다. \n\n자기 자신일 경우 자신의 닉네임을 입력해주세요.\n",
		],
		]
		];
		return json_encode($encode);
	}
	
public function DTE() {
	
		$encode = [
		"type" => "custom_form",
		"title" => "§cInitialize Nametag",
		"content" => [
		[
		"type" => "input",
		"text" => "Input Player Name to initialize Nametag\n",
		]
		]
		];
		return json_encode($encode);
	}
	
public function DT() {
	
		$encode = [
		"type" => "custom_form",
		"title" => "§c§l표시 닉네임 초기화",
		"content" => [
		[
		"type" => "input",
		"text" => "§l초기화 할 플레이어의이름을 입력해주세요\n",
		]
		]
		];
		return json_encode($encode);
	}

public function onDataPacketRecieve(DataPacketReceiveEvent $event) {
		$packet = $event->getPacket();
		$player = $event->getPlayer();
		$name = $player->getName();
		if ($packet instanceof ModalFormResponsePacket) {
			$id = $packet->formId;
			$a = json_decode($packet->formData, true);
			if ($id === 22222) {
				if($this->lg["language"]=="eng"){
				    if($a === 0){
					$this->sendUI($player, 10293, $this->DTE());
					    }
					else if($a === 1){
						$this->sendUI($player, 55555, $this->NameTagE());
						}
					else if($a === 2){
						return;
						}
					}
				else{
					if($a === 0){
					       $this->sendUI($player, 10293, $this->DT());
					    }
					else if($a === 1){
						$this->sendUI($player, 55555, $this->NameTag());
						   }
					else if($a === 2){
						return;
						}
					     }//lang
					    }
				else if ($id === 55555) {
					
					if(!is_null($this->getServer()->getPlayer($a[2]))){
					$this->addTag($a[2],$a[0],$a[1]);
					$this->save();
					if($this->lg["language"]=="eng"){
						$this->sendUI($player, 33333, $this->MessageE("NameTag of ".$a[2]."set to ".$a[1].$a[0]));
						}
					else{
					$this->sendUI($player, 33333, $this->Message("§l".$a[2]."의 닉네임과 칭호가 ".$a[1].$a[0]."로 설정되었습니다"));
					}//lang끝
					}//player끝
					else{
						if($this->lg["language"]=="eng"){
					       $this->sendUI($player, 33333, $this->MessageE("§cPlayer ".$a[2]. " does not exist"));
						}
					else{
					       $this->sendUI($player, 33333, $this->Message($a[2]."§l§c의 이름을 가진 플레이어가 없습니다"));
					}
					}//else끝
				}//else if끝
					
				else if ($id === 10293) {
				if(!is_null($this->getServer()->getPlayer($a[0]))){
					if($this->lg["language"]=="eng"){
						   $this->deleteTag($a[0]);
					       $this->sendUI($player, 33333, $this->MessageE("§fDelete NameTag of ".$a[0]. "was succesful"));
						}
					else{
					$this->deleteTag($a[0]);
					       $this->sendUI($player, 33333, $this->Message($a[0]."§l의 닉네임이 성공적으로 삭제되었습니다"));
					}//lang끝
					$this->save();
					}//null끝
					else{
						if($this->lg["language"]=="eng"){
					       $this->sendUI($player, 33333, $this->MessageE("§cPlayer ".$a[0]. " does not exist"));
						}
					else{
					       $this->sendUI($player, 33333, $this->Message($a[0]."§l§c의 이름을 가진 플레이어가 없습니다"));
					}
					$this->save();
					}
				}
				
				else if ($id === 33333) {
					if($a === 0){
						if($this->lg["language"]=="eng"){
							$this->sendUI($player, 22222, $this->NameENG());
							}
						else{
							$this->sendUI($player, 22222, $this->NameKOR());
							}
						}
					else if($a === 1){
						return;
						}
					}
			}
		}
		
public function addTag($name,$tag,$prefix){
	$this->nt[$name] = $prefix.$tag;
	$player = $this->getServer()->getPlayer($name);
	$player->setNameTag($this->nt[$name]);
	$this->save();
	}
	
public function deleteTag($name){
     unset($this->nt[$name]);
     $player = $this->getServer()->getPlayer($name);
	 $player->setNameTag($name);
     $this->save();
	}
	
public function save(){
		$this->nametag->setAll($this->nt);
		$this->nametag->save();
		$this->lang->setAll($this->lg);
		$this->lang->save();
	}
}
