<?php

namespace werewolf;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
//use werewolf\tasks\GamePlayingTask;






 class WereWolf extends PluginBase implements Listener{




       public function onEnable(){


                 $this->getServer()->getPluginManager()->registerEvents($this, $this); //register event

                 if(!file_exists($this->getDataFolder())){

                        mkdir($this->getDataFolder(), 0744, true);
                 }


                 $this->con = new Config($this->getDataFolder() ."config.yml", Config::YAML,
                 array(
                       'BlockId' => '0:0'
                      ));

                 $this->mess = new Config($this->getDataFolder() ."message.yml", Config::YAML,
                 array(
                       'ajoin' => 'あなたは既にゲームに参加しています',
                       'astart' => 'ゲーム中です。しばらくお待ちください',
                       'jjoin' => 'ゲームに参加しました',
                       'wwolf' => 'あなたの役職は人狼です',
                       'wvill' => 'あなたの役職は村人です',
                       'wteller' => 'あなたの役職は占い師です',
                       'wknight' => 'あなたの役職は騎士です'));
                       


                 $this->loadData();
                 $this->ItemAdd();

       }


       public function loadData(){


                 $this->status = 0;
                 $this->players = [];
                 $this->pcount = 0;

                 $this->p["1"] = 0;
                 $this->p["2"] = 0;
                 $this->p["3"] = 0;
                 $this->p["4"] = 0;
                 $this->p["5"] = 0;
                 $this->p["6"] = 0;
                 $this->p["7"] = 0;
                 $this->p["8"] = 0;
                 $this->p["9"] = 0;
                 $this->p["10"] = 0;



                 foreach($this->players as $key => $name){


                          switch($key){


                                 case '0':

                                          $item = Item::get(35, 0, 1);

                                          $item->setCustomName("§a".$name);

                                          $this->player[$name]['item'] = $item;

                                          break;
                                 case '1':

                                           $item = Item::get(35, 1, 1);

                                           $item->setCustomName("§b".$name);

                                           $this->player[$name]['item'] = $item;
    
                                           break;
                                 case '2':
 
                                          $item = Item::get(35, 3, 1);
 
                                          $item->setCustomName("§a".$name);
 
                                          $this->player[$name]['item'] = $item;

                                          break;

                                 case '3':

                                          $item = Item::get(35, 4, 1);
 
                                          $item->setCustomName("§b".$name);

                                          $this->player[$name]['item'] = $item;

                                          break;

                                 case '4':

                                          $item = Item::get(35, 5, 1);
 
                                          $item->setCustomName("§a".$name);

                                          $this->player[$name]['item'] = $item;
 
                                          break;
 
                                 case '5':

                                          $item = Item::get(35, 6, 1);
                                          $item->setCustomName("§b".$name);

                                          $this->player[$name]['item'] = $item;

                                         break;

                                 case '6':

                                          $item = Item::get(35, 9, 1);
                                          $item->setCustomName("§a".$name);

                                          $this->player[$name]['item'] = $item;

                                          break;

                                 case '7':

                                          $item = Item::get(35, 11, 1);
                                          $item->setCustomName("§b".$name);

                                          $this->player[$name]['item'] = $item;
 
                                          break;

                                 case '8':
                                          $item = Item::get(35, 12, 1);

                                          $item->setCustomName("§a".$name);

                                          $this->player[$name]['item'] = $item;

                                          break;

                                 case '9':

                                          $item = Item::get(35, 14, 1);

                                          $item->setCustomName("§b".$name);

                                          $this->player[$name]['item'] = $item;

                                         break;
                          }

                }




                                          
       }


       public function onTouch(PlayerInteractEvent $ev){

           $player = $ev->getPlayer();

          if($ev->getBlock()->getId().":".$ev->getBlock()->getDamage() == $this->con->get('BlockId')){

              if($this->status == 0){

                      
                      $name = $player->getName();
                      $players = $this->players;

                     /* if(in_array($name, $players)){
 
                              $player->sendMessage('§c'.$this->mess->get('ajoin'));
                      }else{*/

                              array_push($this->players, $name);
                              $this->pcount++;

                              $player->sendMessage('§b'.$this->mess->get('jjoin'));

                              if($this->pcount == 10){
 
                                    $this->status = 1;
                                    $this->start();
                             }
                      //}
               }else{
                         $player->sendMessage('§c'.$this->mess->get('astart'));
               }
        }
     }


       public function start(){


                                    shuffle($this->players);

                                  

                                    foreach($this->players as $key => $value){

                                           switch($key){

                                                        case "0":

                                                                 $nplayer = $this->getServer()->getPlayer($value);
                                                                 $nplayer->sendMessage('§a'.$this->mess->get('wwolf'));
                                                                 $nplayer->sendMessage('§a人狼はあなたと§6 '.$this->players[3].' §aです');
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
                                                                 $nplayer->sendMessage('§a人狼はあなたと§6 '.$this->players[0].' §aです');
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

                 foreach($this->players as $pname){

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

         public function getItemName($name){

                 if(!empty($this->player[$name]['item'])){

                          return $this->player[$name]['item'];
                 }else{

                      return false;
                 }
        }


         public function getWork($name){


                  if(!empty($this->player[$name]['work'])){

                          switch($this->player[$name]['work']){


                                  case 'knight':

                                                return '騎士';
                                                break;
                                  case 'teller':

                                                return '占い師';
                                                break;

                                  case 'villager':
 
                                                return '村人';
                                                break;

                                  case 'wolf':
                                               return '人狼';
                                               break;

                           }
                  }else{

                           return false;
                  }
        }





         public function onCommand(CommandSender $sender, Command $cmd,$label, array $args){


                  $name = $sender->getName();
                  $cname = $cmd->getName();

                  switch($cname){


                                 case 'eat':

                                            if(!empty($this->player[$name]['work'])){

                                               if(!empty($this->player[$name]['command'])){

                                                   if($this->player[$name]['command'] == 1){

                                                          if($this->player[$name]['work'] == 'wolf'){

                                                                    if(isset($args[0])){

                                                                          if(in_array($args[0], $this->players)){

                                                                                     if(empty($this->kill)){

                                                                                             $this->kill = $args[0];
                                                                                             $sender->sendMessage('§bターゲットは §6'.$args[0].' §bになりました');
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
                                                 
                                          }else{

                                                 $sender->sendMessage('§eあなたはこのコマンドを使うことはできません');
                                          }
                                       break;

                                       case 'catch':

                                                    if(!empty($this->player[$name]['work'])){
 
                                                                 if(!empty($this->player[$name]['command'])){

                                                                      if($this->player[$name]['command'] == 1){

                                                                            if($this->player[$name]['work'] == 'wolf'){

                                                                                   if(isset($args[0])){

                                                                                            if(in_array($args[0], $this->players)){

                                                                                                       $work = $this->getWork();
                                                                                                       $sender->sendMessage('§b'.$args[0].' §aは §6'.$work.' §aです'); 
                                                                                                       $this->player[$name]['command'] = 0;
                                                                                            }else{

                                                                                                       $sender->sendMessage('§e そのプレイヤーはいません');
                                                                                            }
                                                                                   }else{
                                                                                      
                                                                                            $sender->sendMessage('§b 占う相手を選択してください');
                                                                                   }
                                                                           }else{

                                                                               $sender->sendMessage('§cあなたはこのコマンドは使えません');
 
                                                                            }
                                                                     }else{

                                                                             $sender->sendMessage('§e今は占えません');
                                                                     }
                                                              }else{

                                                                    $sender->sendMessage('§eあなたはこのコマンドは使えません');
                                                              }
                                                     }else{

                                                          $sender->sendMessage('§あなたはこのコマンドは使えません');
                                                     }
                                                 break;

                                                                                                               
                 
                          }                    //まだ途中w
      }


      public function getNeoA($args, $value){

            if(($key = array_search($value, $args)) !== false){

                      unset($args[$key]);
             
                      $back = array_values($args);
 
                      return $back;
            }else{


                      return false;
            }
     }

     public function checkSlot($inventory, $number, $item){

              if($inventory->getHotbarSlotIndex($number)->getId() != 0){

                        ++$number;

                        $this->checkSlot($inventory, $number, $item);
              }else{

                 $inventory->setHotbarSlotIndex($number, $item);
 
                 return true;
              }
    }

            



      public function ItemAdd(){


                 foreach($this->players as $pname){

                            $neo = $this->getNeoA($this->players, $pname);
                            $player = $this->getServer()->getPlayer($pname);
                            if($player instanceof Player){
                            
                                 $inventory = $player->getInventory();
 
                                 foreach($neo as $name){

                                       $item = $this->getItemName($name);
                                       $this->checkSlot($inventory, 1, $item);
                                 }
                            }
                }
    }
                                                           

                                                             


                                

                                

                                     

                                     



                                     
  }


                          

                                      