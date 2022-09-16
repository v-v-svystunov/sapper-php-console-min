<?php

namespace Matrix\Controller;

use Matrix\Core\Common;
use Matrix\Core\Service;
use Matrix\Core\ExceptionProcessor;
use Matrix\Model\SaperModel;
use Matrix\View\SaperView;

/**
 *
 * @package	Sapper Controller
 * @author  Svistunov Valery (VVS)
 * @version	1.1
 *
 */
class SaperController {

	private $data = array();

	public function viewOpenedGridAction () {
		$model = new SaperModel;
		$_matrix = $model->getMatrix();
		if (is_null($_matrix)) {
			try {
				throw new \Exception("ERROR: controller::viewOpenedGridAction can't open `matrix` - something is going wrong way.");
			} catch (\Exception $e) {
				$err = new ExceptionProcessor($e);
				exit;
			}
		}
		$view = new SaperView;

		$verbose = true;
		$view->drowGameMatrix ($_matrix, $verbose);

		exit;
		
	}

	public function initNewGameAction () {
		if ($this->param&&Common::isNumeric($this->param)) {
			$_grid_num = $this->param;
			if ($_grid_num >25) {
				$_grid_num = 25;
			}
		} else {
			$_grid_num = GRID_SIZE;
		}

		if ($this->additional&&Common::isNumeric($this->additional)) {
			$_black_hall_cnt = $this->additional;
			if ($_black_hall_cnt > $_grid_num*$_grid_num) {
				$_black_hall_cnt = $_grid_num*$_grid_num - 1;
			}
		} else {
			$_black_hall_cnt = BLACK_HALL_NUM;
		}

		$model = new SaperModel;

		$_matrix = $model->createNewGameMatrix ($_grid_num, $_black_hall_cnt);

		$_matrix = $model->saveMatrix ($_matrix);

		if (is_null($_matrix)) {
			try {
				throw new \Exception("ERROR: controller::initNewGameAction can't renew `matrix` - something is going wrong way.");
			} catch (\Exception $e) {
				$err = new ExceptionProcessor($e);
				exit;
			}
		}

		$view = new SaperView;

		$verbose = false;
		$view->drowGameMatrix ($_matrix, $verbose);

		exit;

	}

	

	public function moveGameAction () {
		$model = new SaperModel;
		$view = new SaperView;
		$service = Service::getInstance();

		if ($this->param) {
			$_move_to = $this->param;
			if (!Common::isNumeric(substr($_move_to,0,1))) {
				$_reverse = "";
				$_reverse .= preg_replace("/\D/i","",$_move_to);
				$_reverse .= preg_replace("/\d/i","",$_move_to);
				$_move_to = $_reverse;
			}
		} else {
			$_matrix = $model->getMatrix();
			if (is_null($_matrix)) {
				try {
					throw new \Exception("ERROR: controller::viewOpenedGridAction can't open `matrix` - something is going wrong way.");
				} catch (\Exception $e) {
					$err = new ExceptionProcessor($e);
					exit;
				}
			}
			$verbose = false;
			$view->drowGameMatrix ($_matrix, $verbose);
			print "\n\n**********************************************************************************************\n";
			print "** You need to place as parameter position of desirable to open cell. As example `1a` ********\n";
			print "**********************************************************************************************\n\n";
			exit;
		}
		$_matrix = $model->getMatrix();
		if (is_null($_matrix)) {
			try {
				throw new \Exception("ERROR: controller::viewOpenedGridAction can't open `matrix` - something is going wrong way.");
			} catch (\Exception $e) {
				$err = new ExceptionProcessor($e);
				exit;
			}
		}
		$_alpha_tgt_row = preg_replace("/\d/i","",$_move_to);
		$_num_tgt_row = preg_replace("/\D/i","",$_move_to);
		if ( $_num_tgt_row > $_matrix->grid_num) {
			$verbose = false;
			$view->drowGameMatrix ($_matrix, $verbose);
			print "\n\n********************************************************************************************************************\n";
			print "** Decimal part of target cell which was input as`".$_move_to."` more than `".$_matrix->grid_num."` as grid size ********\n";
			print "*************************************************************************************************************************\n\n";
			exit;	
		}

		if (!in_array($_alpha_tgt_row, $_matrix->alpha_bet_row)) {
			$verbose = false;
			$view->drowGameMatrix ($_matrix, $verbose);
			print "\n\n********************************************************************************************************************\n";
			print "********************** Alphabetical part of target cell which was input as`".$_move_to."` is out of range ***************\n";
			print "*************************************************************************************************************************\n\n";
			exit;
		}

		$_target_index = $service->explore ("DEFF_RECTANGULAR_INDEX", ["alpha" => $_alpha_tgt_row, "decimal" => $_num_tgt_row, "alpha_row" => $_matrix->alpha_bet_row, "grid_size" => $_matrix->grid_num]);

		if ( $_matrix->main[$_target_index]->black) {
			$verbose = true;
			$view->drowGameMatrix ($_matrix, $verbose);
			// $matrix = $model->createNewGameMatrix ($_matrix->grid_num, count($_matrix->dark_positions));
			// $matrix = $model->saveMatrix ($matrix);
		} else {
			$_matrix->score += $_matrix->main[$_target_index]->count;
			$_matrix->main[$_target_index]->show = true;
			$_matrix = $model->saveMatrix ($_matrix);

			if (is_null($_matrix)) {
				try {
					throw new \Exception("ERROR: controller::moveGameAction can't rewrite `matrix` - something is going wrong way.");
				} catch (\Exception $e) {
					$err = new ExceptionProcessor($e);
					exit;
				}
			}

			$verbose = false;
			$view->drowGameMatrix ($_matrix, $verbose);
		}	

		exit;

	}

	public function testGameAction () {
		if ($this->param&&Common::isNumeric($this->param)) {
			$_grid_num = $this->param;
			if ($_grid_num >25) {
				$_grid_num = 25;
			}
		} else {
			$_grid_num = GRID_SIZE;
		}

		if ($this->additional&&Common::isNumeric($this->additional)) {
			$_black_hall_cnt = $this->additional;
			if ($_black_hall_cnt > $_grid_num*$_grid_num) {
				$_black_hall_cnt = $_grid_num*$_grid_num - 1;
			}
		} else {
			$_black_hall_cnt = BLACK_HALL_NUM;
		}

		$_matrix = new \stdClass;

		$_matrix->score = 0;
		
		$_alphabet_counter = range('a','z');
		array_splice ($_alphabet_counter,$_grid_num);
		
		$_matrix->alpha_bet_row = $_alphabet_counter;

		$_matrix->main = [];

		$_dark_positions = array_rand (range (0, (($_grid_num*$_grid_num)-1)), $_black_hall_cnt);

		for($i = 0; $i < $_grid_num*$_grid_num; $i++) {
			$item = new \stdClass;;
			$item->show = false;
			if (in_array($i,$_dark_positions)) {
				$item->black = true;
			} else {
				$item->black = false;
				$item->count = 0;
				$above = $i - $_grid_num;
				$below = $i + $_grid_num;
				if ($above >= 0) {
					if (in_array($above, $_dark_positions)) {
						$item->count++;
					}
					// >> left shift and right shift
					$left = $above - 1;
					$right = $above + 1;
					if ( $left >= 0 ) {
						if (($left+1) % $_grid_num!=0) {
							if (in_array($left, $_dark_positions)) {
								$item->count++;
							}	
						}
					}

					if ($right <= $_grid_num*$_grid_num-1) {
						if ($right % $_grid_num != 0) {
							if (in_array($right, $_dark_positions)) {
								$item->count++;
							}
						}
					}
					// << THE END left shift and right shift

				}

				if ($below <= $_grid_num*$_grid_num-1) {
					if (in_array($below, $_dark_positions)) {
						$item->count++;
					}
					// >> left shift and right shift
					$left = $below - 1;
					$right = $below + 1;
					if ( $left >= 0 ) {
						if (($left+1) % $_grid_num!=0) {
							if (in_array($left, $_dark_positions)) {
								$item->count++;
							}	
						}
					}

					if ($right <= $_grid_num*$_grid_num-1) {
						if ($right % $_grid_num != 0) {
							if (in_array($right, $_dark_positions)) {
								$item->count++;
							}
						}
					}
					// << THE END left shift and right shift	
				}

				$left = $i - 1;
				$right = $i + 1;
				if ( $left >= 0 ) {
					if (($left+1) % $_grid_num!=0) {
						if (in_array($left, $_dark_positions)) {
							$item->count++;
						}	
					}
				}

				if ($right <= $_grid_num*$_grid_num-1) {
					if ($right % $_grid_num != 0) {
						if (in_array($right, $_dark_positions)) {
							$item->count++;
						}
					}
				}
			}
			array_push ($_matrix->main,$item);
		}

		print "  +";
		for ($i=0; $i<$_grid_num; $i++) {
			print "----+";
		}
		print "\n";

		$q = 0;
		for ($i=0; $i < $_grid_num*$_grid_num; $i = $i+$_grid_num) {
			print $_matrix->alpha_bet_row[$q] . " |";
			for ($j = 0; $j < $_grid_num; $j++) {
				$n = $i + $j;
				//print " ".$n.((strlen((string) $n)<2) ? " " : "")." |";
				if ($_matrix->main[$n]->black) {
					print " \033[31mX\033[0m  |";
				} else {
					print " ".$_matrix->main[$n]->count."  |";
				}
			}
			$q++;
			print "\n";
			print "  +";
			for ($k=0; $k<$_grid_num; $k++) {
				print "----+";
			}
			print "\n";
		}
		
		print "   ";
		for ($i=0; $i<$_grid_num; $i++) {
			print "  ".($i+1).((strlen((string) $i)>1)?" ":"  ");
		}

		print "\n\n";

		exit;	
	} 

	public function __set ($name, $value) {
    	$this->data[$name] = $value;
    }

    public function __get ($name) {
		if (array_key_exists($name, $this->data)) {
			return $this->data[$name];
		}
        
        return null;
    }
}