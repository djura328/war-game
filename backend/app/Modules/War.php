<?php
namespace App\Modules;

use App\Army as Army;
use App\Game;
use App\Modules\Log;
use App\Modules\LogNew;

class War{

	private $warEnded = false;
	private $winnerArmy;
	private $turn = 0;
	private $game;
	private $log;

	public function __construct($id){
	    $this->game = Game::find($id);
	    $this->log =  new Log;
    }

    public function startWar () {

	    while (!$this->warEnded) {
            $this->ready();
	    }

	    return [
	        'winner' => $this->winnerArmy,
			'log' => $this->log->showLog()
        ];
  	}

  	public function startStep () {
        $this->ready();

        return [
            'winner' => $this->warEnded ? $this->winnerArmy : 'War is in progress',
            'log' => $this->log->showLog()
        ];
	}

	public function ready () {


        $attacker = Army::attacker($this->game->id);
        $attacked = $attacker->victim();


		$war_end = $this->warEnd($attacker, $attacked);
		if($war_end) return;

		$this->log->addLog(['Attacker' => $attacker, 'Attacked' => $attacked]);

		$this->battle($attacker, $attacked);

		//$removed_attacker = $attacker->removeDeadUnite();
        //if($removed_attacker) $this->log->addLog(['Destroy Unit' => $attacker]);

        //$removed_attacked = $attacked->removeDeadUnite();
        //if($removed_attacked) $this->log->addLog(['Destroy Unit' => $attacked]);

		$removed_army = Army::removeDeadArmies($this->game->id);
        if($removed_army) $this->log->addLog(['Destroy Army' => $removed_army->first()]);

		$this->turn++;

 	}

 	private function battle(Army $attacker, Army $attacked){

 		$success = mt_rand(1, $attacker->units_count + $attacked->units_count) <= $attacker->units_count ? true : false;

 		if($success){
            $attacked->units->first()->health -= $attacker->units->first()->attack;
            $attacked->units->first()->save();
            $attacked->units->first()->removeDeadUnite();
            $this->log->addLog(['Success' => $attacker, 'Defeated' => $attacked]);

 		}
 		else{
            //$attacker->units->first()->health -= $attacked->units->first()->attack;
            //$attacker->units->first()->save();
            //$attacker->units->first()->removeDeadUnite();
            $this->log->addLog(['Failed' => $attacker, 'Defense' => $attacked]);
 		}
 	}

 	private function warEnd($attacker, $attacked){
  	    if($attacked === null) {
  	        $this->warEnded = true;
            $this->winnerArmy = Army::with(['units' => function($q){
                $q->where('status', '=', 1);
            }])
                ->where('id', $attacker->id)
                ->get();

            return true;
        }
    }
}


?>
