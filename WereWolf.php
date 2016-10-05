<?php

namespace werewolf;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\Listener;
//use werewolf\tasks\GamePlayingTask;




 class WereWolf extends PluginBase implements Listener{


       public function onEnable(){


                 $this->getServer()->registerEvent($this, $this); //register event

                 if(!file_exists($this->getDataFolder())){

                        mkdir($this->getDataFolder(), 0744, true);
                 }


                 $this->con = new Config($this->getDataFolder() ."config.yml", Config::YAML,
                 array(
                       'BlockId' => '0:0'
                      ));

                 $this->mess = new Config($this->getDataFolder() ."message.yml", Config:YAML,
                 array(
                       'ajoin' => 'あなたは既にゲームに参加しています',
                       'astart' => 'ゲーム中です。しばらくお待ちください',
                       'jjoin' => 'ゲームに参加しました',
                       'wwolf' => 'あなたの役職は人狼です',
                       'wvill' => 'あなたの役職は村人です',
                       'wteller' => 'あなたの役職は占い師です',
                       'wknight' => 'あなたの役職は騎士です',
                       


                 $this->status = 0;
                 $this->players = [];
                 $this->pcount = 0;

       }


       public function onTouch(PlayerInteractEvent $ev){

          if($ev->getBlock()->getId().":".$this->getBlock()->getDamage() == $this->con->get('BlockId')){

              if($this->status == 0){

                      $player = $ev->getPlayer();
                      $name = $player->getName();
                      $players = $this->players;

                      if(in_array($name, $players)){
 
                              $player->sendMessage('§c'.$this->mess->get('ajoin'));
                      }else{

                              array_push($this->players, $name);

                              $player->sendMessage('§b'.$this->mess->get('jjoin'));

                              if($this->pcount == 10){
 
                                    $this->status = 1;
                                    $this->start();
                             }
                      }
               }else{
                    $player->sendMessage('§c'.$this->mess->get('astart'));
              }
       }
     }


       public function start($players){


                                    shuffle($players);

                                    $this->pl = $players;

                                    foreach($players as $key => $value){

                                           switch($key){

                                                        case "0":

                                                                 $nplayer = $this->getServer()->getPlayer($value);
                                                                 $nplayer->sendMessage('§a'.$this->mess->get('wwolf'));
                                                                 $nplayer->sendMessage('§a人狼はあなたと§6 '.$players[1].' §aです');
                                                                 $this->player[$value]['work'] = 'wolf';
                                                                 break;

                                                        case '1':
                              
                                                                 $nplayer = $this->getServer()->getPlayer($value);
                                                                 $nplayer->sendMessage('§a'.$this->mess->get('wknight'));
                                                                 $this->player[$value]['work'] = 'knight';
                                                                 break;

                                                        case '2':

                                                                 $nplayer = $this->getServer()->getPlayer($value);
                                                                 $nplayer->sendMessage('§a'.$this->mess->get('wwolf'));
                                                                 $nplayer->sendMessage('§a人狼はあなたと§6 '.$players[3].' §aです');
                                                                 $this->player[$value]['work'] = 'wolf';
                                                                 break;

                                                        case '3':

                                                                 $nplayer = $this->getServer()->getPlayer($value);
                                                                 $nplayer->sendMessage('§a'.$this->mess->get('wteller'));
                                                                 $this->player[$value]['work'] = 'teller';
                                                                 break;

                                                        default:
                                                                 $nplayer = $this->getServer()->getPlayer($value);
                                                                 $nplayer->sendMessage('§a'.$this->mess->get('wvill'));
                                                                 $this->player[$value]['work'] = 'villager';
                                              }

                                            $this->getServer()->getPlayer($value)->sendMessage('§eGM§f > §b皆さん、役職は覚えましたか? では,はじめます。');
                                    }
                          $this->pre();
          }

         
         public function pre(){

                 foreach($this->pl as $pname){

                          $this->getServer()->getPlayer($pname)->sendMessage('§eGM§f > §bあなた方の中に人狼が紛れています。');
                          $this->getServer()->getPlayer($pname)->sendMessage('§eGM§f > §b村人は人狼を見つけ出し、処刑しなければいけません。');
                          if($this->player[$pname]['work'] == 'wolf'){

                                 $this->getServer()->getPlayer($pname)->sendMessage('§eGM§f > §bこれは個別メッセージです。');
                                 $this->getServer()->getPlayer($pname)->sendMessage('§eGM§f > §b誰を殺すか、１人だけ選んでください。');
                                 $this->getServer()->getPlayer($pname)->sendMessage('§eGM§f > §b/eat 殺す相手 で選択してください。');
                                 $this->player[$pname]['command'] = 1;
                          }

                          if($this->player[$pname]['work'] == 'teller'){

                                 $this->getServer()->getPlayer($pname)->sendMessage('§eGM§f > §bこれは個別メッセージです。');
                                 $this->getServer()->getPlayer($pname)->sendMessage('§eGM§f > §b誰を占うか、１人だけ選んでください。');
                                 $this->getServer()->getPlayer($pname)->sendMessage('§eGM§f > §b/catch 占う相手 で選択してください。');
                                 $this->player[$pname]['command'] = 1;
                          }

                          if($this->player[$pname]['work'] == 'knight'){

                                 $this->getServer()->getPlayer($pname)->sendMessage('§eGM§f > §bこれは個別メッセージです。');
                                 $this->getServer()->getPlayer($pname)->sendMessage('§eGM§f > §b誰を守るか、１人だけ選んでください。');
                                 $this->getServer()->getPlayer($pname)->sendMessage('§eGM§f > §b/guard 守る相手 で選択してください。');
                                 $this->getServer()->getPlayer($pname)->sendMessage('§eGM§f > §b自分は守れないのでご注意ください');
                                 $this->player[$pname]['command'] = 1;
                          }
                 }
         }

         public function onCommand(CommandSender $sender, Command $cmd,$label, array $args){


                  $name = $sender->getName();
                  $cname = $cmd->getName();

                  switch($cname){


                                 case 'eat':

                                            if(!empty($this->player[$name]['work'])){

                                                   if($this->player[$name]['command'] == 1){

                                                          if($this->player[$name]['work'] == 'wolf'){

                                                                    if(isset($args[0])){

                                                                          if(in_array($args[0], $this->pl){

                                                                                     if(empty($this->kill)){

                                                                                             $this->kill = $args[0];
                                                                                             $sender->sendMessage('§bターゲットは §6'$args[0]' §bになりました');
                                                                                             $this->player[$name]['command'] = 0;
                                                                                     }else{

                                                                                           $sender->sendMessage('§c既に襲う相手は選択されています');
                                                                                     }
                                                                          }else{
                                                                           
                                                                                $sender->sendMessage('§c そのプレイヤーはいないようです');
                                                                          }
                                                                     }else{

                                                                          $sender->sendMessage('§c プレイヤーを選択してください');
                                                                     }
                                                          }else{

                                                            $sender->sendMessage('§eあなたはこのコマンドを使うことはできません');
                                                          }
                                                 }else{

                                                       $sender->sendMessage('§e今は襲えません');
                                                 }
                                          }else{

                                                 $sender->sendMessage('§eあなたはこのコマンドを使うことはできません');
                                          }
                                       break;

                 
                          }
      }
  }


                          

                                      