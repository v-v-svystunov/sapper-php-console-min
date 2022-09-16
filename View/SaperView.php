<?php
namespace Matrix\View;

/**
 *
 * @package	Sapper View
 * @author  Svistunov Valery (VVS)
 * @version	1.1
 *
 */
class SaperView  {

	public function drowGameMatrix ($matrix, $verbose = true) {
		print "  +";
		for ($i=0; $i<$matrix->grid_num; $i++) {
			print "----+";
		}
		print "\n";

		$q = 0;
		for ($i=0; $i < $matrix->grid_num*$matrix->grid_num; $i = $i+$matrix->grid_num) {
			print $matrix->alpha_bet_row[$q] . " |";
			for ($j = 0; $j < $matrix->grid_num; $j++) {
				$n = $i + $j;
				if (!$verbose) {
					if ($matrix->main[$n]->show) {
						if ($matrix->main[$n]->black) {
							print " \033[31mX\033[0m  |";
						} else {
							print " ".$matrix->main[$n]->count."  |";
						}
					} else {
						print " *  |";
					}
				} else {
					if ($matrix->main[$n]->black) {
						print " \033[31mX\033[0m  |";
					} else {
						print " ".$matrix->main[$n]->count."  |";
					}
				}
			}
			$q++;
			print "\n";
			print "  +";
			for ($k=0; $k<$matrix->grid_num; $k++) {
				print "----+";
			}
			print "\n";
		}

		
		print "   ";
		for ($i=0; $i<$matrix->grid_num; $i++) {
			print "  ".($i+1).((strlen((string) $i)>1)?" ":"  ");
		}

		print "\n\n";
		if ($matrix->score) {
			print "*********************************************************\n";
			print "****** Your Score is `".$matrix->score."` ***************\n";
			print "*********************************************************\n\n";
		}
	}
	
}
