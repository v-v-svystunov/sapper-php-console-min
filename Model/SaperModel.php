<?php

namespace Matrix\Model;

use Matrix\Core\Common;
use Matrix\Core\ExceptionProcessor;
use Matrix\Core\Service;

/**
 *
 * @package	Sapper Model
 * @author  Svistunov Valery (VVS)
 * @version	1.1
 *
 */
class SaperModel{
	
	public function createNewGameMatrix ($grid_num=7, $black_hall_cnt=7) {

		$service = Service::getInstance();

		$_matrix = new \stdClass;

		$_matrix->score = 0;

		$_matrix->alpha_bet_row = $service->explore ("CREATE_ALPHA_BET_COUNTER", ["grid_num" => $grid_num]);

		$_matrix->grid_num = $grid_num;

		$_matrix->main = [];

		$_matrix->dark_positions = array_rand (range (0, (($_matrix->grid_num*$_matrix->grid_num)-1)), $black_hall_cnt);

		for($i = 0; $i < $_matrix->grid_num*$_matrix->grid_num; $i++) {
			$item = new \stdClass;;
			$item->show = false;
			if (in_array($i,$_matrix->dark_positions)) {
				$item->black = true;
			} else {
				$item->black = false;
				$item->count = 0;
				$above = $i - $_matrix->grid_num;
				$below = $i + $_matrix->grid_num;
				if ($above >= 0) {
					if (in_array($above, $_matrix->dark_positions)) {
						$item->count++;
					}
					$left = $above - 1;
					$right = $above + 1;
					$item->count = $this->checkLeftRightNeighborhood ($item->count, $left, $right, $_matrix->grid_num, $_matrix->dark_positions);

				}

				if ($below <= $_matrix->grid_num*$_matrix->grid_num-1) {
					if (in_array($below, $_matrix->dark_positions)) {
						$item->count++;
					}
					$left = $below - 1;
					$right = $below + 1;
					$item->count = $this->checkLeftRightNeighborhood ($item->count, $left, $right, $_matrix->grid_num, $_matrix->dark_positions);
				}

				$left = $i - 1;
				$right = $i + 1;
				$item->count = $this->checkLeftRightNeighborhood ($item->count, $left, $right, $_matrix->grid_num, $_matrix->dark_positions);
			}
			array_push ($_matrix->main,$item);
		}

		return $_matrix;
	}

	public function saveMatrix ($matrix=null) {
		if (!is_null ($matrix)) {
			$string_matrix = json_encode ($matrix);
			$file = fopen("matrix","w");
			if (!fwrite($file,$string_matrix)) {
				try {
					throw new \Exception("ERROR: model::saveMatrix can't write to the file `matrix`");
				} catch (\Exception $e) {
					$err = new ExceptionProcessor($e);
					exit;
				}
				fclose($file);
			}
			fclose($file);
			$handle = fopen("matrix", "r");
			$contents = fread($handle, filesize("matrix"));
			fclose($handle);
			$matrix = json_decode($contents);
			if (is_object($matrix)) {
				return $matrix;
			}
		}

		return null;
	}

	public function getMatrix() {
		if (!is_file ("matrix")) {
			print "\n**********************************************************************************************\n";
			print "***** You need to create Game matrix previously by usage `i` as option - check help **********\n";
			print "**********************************************************************************************\n\n";
			exit;
		}
		$handle = fopen("matrix", "r");
		$contents = fread($handle, filesize("matrix"));
		fclose($handle);
		$matrix = json_decode($contents);
		if (is_object($matrix)) {
			return $matrix;
		}

		return null;
	}

	private function checkLeftRightNeighborhood ($count, $left, $right, $grid_num, $dark_positions) {
		if ( $left >= 0 ) {
			if (($left+1) % $grid_num!=0) {
				if (in_array($left, $dark_positions)) {
					$count++;
				}	
			}
		}

		if ($right <= $grid_num*$grid_num-1) {
			if ($right % $grid_num != 0) {
				if (in_array($right, $dark_positions)) {
					$count++;
				}
			}
		}

		return $count;	
	}

	
}