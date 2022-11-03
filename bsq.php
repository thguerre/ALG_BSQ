<?php

class Solver {
	function __construct ($argc, $argv) {
		// $this->tellMemory("constructor");

		// var_dump($argc, $argv);
		if ($argc != 2)
			exit("Need one argument\n");

		$this->filename = $argv[1];

		if (!file_exists($this->filename))
			exit("File invalid\n");

		$this->getContent();
		// $this->tellMemory("file parsed");
		// $this->preprocess();
		$this->process();
	}
	
	// parse file
	function getContent () {
		$handle = fopen($this->filename, 'r');

		$firstLine = fgets($handle);

		$this->gridHeight = intval($firstLine);

		if ($this->gridHeight == 0)
			return;

		$this->rGrid = [];

		for ($i=0; $i < $this->gridHeight; $i++) {
			$this->rGrid[$i] = fgets($handle);
		}

		$this->gridWidth = strlen($this->rGrid[0])-1;
		// var_dump($this->rGrid);
		// echo "\nGrid is $this->gridWidth by $this->gridHeight\n";
	

		return;

		$rawFile = file_get_contents($this->filename);
		// var_dump($rawFile);

		$firstLine = strpos($rawFile, "\n");
		$secondLine = strpos($rawFile, "\n", $firstLine + 1);
		// var_dump("succ", intval(substr($rawFile, 0, $firstLine)));
		$this->gridHeight = intval(substr($rawFile, 0, $firstLine));
		$this->gridWidth = ($secondLine - $firstLine) - 1;
		var_dump($firstLine, $secondLine, $secondLine - $firstLine -1);
		$this->rawGrid = [];
		for ($i = 0; $i < $this->gridHeight; $i++) {
			echo $i.PHP_EOL;
			$this->rawGrid[$i] = substr($rawFile, (firstLine));
		}

		//i do wonder why i didn't used fgets..

		$this->gridHeight = intval(array_shift($this->rawGrid));
		array_pop($this->rawGrid);
		// $this->gridWidth = strlen($this->rawGrid[0]);
		echo "\nGrid is $this->gridWidth by $this->gridHeight\n";
	}

	function tellMemory ($state = NULL) {
		echo "Current memory usage"
			. (
				is_null($state) ? "" : " [$state]"
			)
			.": ". round(memory_get_usage()/(1024*1024), 2) . '/' . round(memory_get_peak_usage()/(1024*1024), 2) . "MB\n";
	}

	function process () {
		$biggestSquareWidth = 0;
		$biggestSquareCoords = [0, 0];

		$row = array_fill(0, $this->gridWidth, 0);
		// $buffer = [0, 0];

		for ($y = 0; $y < $this->gridHeight; $y++) {
			$buffer = [0, 0]; 
			for ($x = 0; $x < $this->gridWidth; $x++) { 
				$buffer[0] = $buffer[1];
				$buffer[1] = $row[$x];

				$currChar = $this->rGrid[$y][$x];

				$tz = $x == 0 ?	0 : $row[$x-1];

				$row[$x] = ($currChar=='.') ? min($buffer[0], $buffer[1], $tz)+1 : 0;
				// echo "$x / $y + $tz : '$currChar' \{$row[$x]}\n";
				if ($row[$x] > $biggestSquareWidth) {
					$biggestSquareWidth = $row[$x];
					$biggestSquareCoords = [$x, $y];
				}
			}
		}

		// var_dump($biggestSquareWidth, $biggestSquareCoords);
		// print_r($this->rGrid);

		for ($y = 1+$biggestSquareCoords[1]-$biggestSquareWidth; $y <= $biggestSquareCoords[1]; $y++) {
			// echo ("t: $y\n");
			for ($x = 1+$biggestSquareCoords[0]-$biggestSquareWidth; $x <= $biggestSquareCoords[0]; $x++)
				$this->rGrid[$y][$x] = 'x';
		}
		// print_r($this->rGrid);

		foreach ($this->rGrid as $row) {
			echo $row;
		}
	}

	function process2 () {
		//initiate loop
		$biggestSquareWidth = 0;
		$biggestSquareCoords = [0, 0];

		$currentRow = array_fill(0, $this->gridWidth, [0, 0]);
		// $this->tellMemory("process start");
		// ob_
		//main loop
		for ($y=$this->gridHeight-1; $y>=0; $y--) {
			$previousRow = $currentRow;
			// unset($currentRow);
			$currentRow = new SplFixedArray($this->gridWidth);

			// if (isset($this->rGrid[$y+1]) && $y == 9997) {
			// 	$this->tellMemory("opti1");
			// 	// unset($this->rGrid[$y+1]);
			// 	$this->tellMemory("opti2");
			// 	//exit();
			// }
			for ($x=$this->gridWidth-1; $x>=0; $x--) {
				// checkerz variables
				$isBottom = $y == $this->gridHeight-1; // is on last row ?
				$isRight = $x == $this->gridWidth-1; // is on last column ?
				$isObstacle = $this->rGrid[$y][$x] == 'o'; // is cell an obstacle ?
				// $x = ;
				// echo $x.'|'.$y."\n";
				// gc_collect_cycles();
				// $this->tellMemory("checkz");
				$currentRow[$x] =
					[
						$isObstacle ? 0 : (
							$isRight ? 1 :
								$currentRow[$x+1][0] + 1
						),
						$isObstacle ? 0 : (
							$isBottom ? 1 :
								$previousRow[$x][1] + 1
						)
					] ;
				// unset($isBottom, $isRight, $isObstacle);
				// echo "Cell [$x, $y] : [".$currentRow[$x][0].", ".$currentRow[$x][1]."]\n";
				// preliminary check (only top row and left column of tested square)
				if (($potentialNewHigh = min($currentRow[$x])) >= $biggestSquareWidth) {
					$isNewHigh = true;
					//secondary check (check if everything is fine)
					for ($i=$x; $i<($x+$potentialNewHigh); $i++) { 
						// var_dump("REEEE", $i, $currentRow[$i][0], $currentRow[$i][1]);
						if ($currentRow[$i][1] < $potentialNewHigh) {
							$isNewHigh = false;
							if ($currentRow[$i][1] < $biggestSquareWidth)
								continue 2;
						}
					}

					if ($isNewHigh) {
						$biggestSquareWidth = $potentialNewHigh;
						// echo "newHigh: ".$biggestSquareWidth.PHP_EOL;
					}
					$biggestSquareCoords = [$x, $y];
				}
				// var_dump($y, $this->rGrid[$y][0]);
			}
			// echo "$y\n";
			// print_r($currentRow);
		}

		
		// echo implode($this->rGrid).PHP_EOL.PHP_EOL;
		// echo implode($this->rGrid).PHP_EOL.PHP_EOL;
		// place x's
		for ($i=$biggestSquareCoords[0]; $i < $biggestSquareCoords[0]+$biggestSquareWidth; $i++) {
			for ($j=$biggestSquareCoords[1]; $j < $biggestSquareCoords[1]+$biggestSquareWidth; $j++) { 
				$this->rGrid[$j][$i] = 'x';
			}
		}

		for ($i=0; $i < $this->gridHeight; $i++) { 
			echo $this->rGrid[$i];
		}

		// echo implode($this->rGrid).PHP_EOL.PHP_EOL;
		// $this->tellMemory("process end");
	}

	function process3 () {
		//initiate loop
		$biggestSquareWidth = 0;
		$biggestSquareCoords = [0, 0];

		for ($x=0; $x < $this->gridWidth - $biggestSquareWidth; $x++) {
			for ($y=0; $y < $this->gridHeight - $biggestSquareWidth; $y++) {
				if (($tmp = $this->headMax($x, $y)) > $biggestSquareWidth) {
					$biggestSquareWidth = $tmp;
					$biggestSquareCoords = [$y, $x];
				}
				// echo $x.'/'.$y.' : '.$tmp.PHP_EOL;
			}
			// echo "$x - $biggestSquareWidth".PHP_EOL;
		}
		// echo "result = $biggestSquareWidth width at ".$biggestSquareCoords[0].'/'.$biggestSquareCoords[1].PHP_EOL;

		echo implode($this->rGrid).PHP_EOL.PHP_EOL;
		for ($i=$biggestSquareCoords[0]; $i < $biggestSquareCoords[0]+$biggestSquareWidth; $i++) { 
			for ($j=$biggestSquareCoords[1]; $j < $biggestSquareCoords[1]+$biggestSquareWidth; $j++) { 
				$this->rGrid[$j][$i] = 'x';
			}
		}
		echo implode($this->rGrid).PHP_EOL.PHP_EOL;
	}

	// gets cell max square (head is top left corner) returns an int
	function headMax ($x, $y) : int
	{
		if ($this->rGrid[$y][$x] == 'o')
			return 0;
		$currentMaxWidth = 1;
		while ($this->onionLayerClear($x, $y, $currentMaxWidth))
			$currentMaxWidth++;
		return $currentMaxWidth;
	}

	function onionLayerClear ($x, $y, $layer) : bool
	{
		if (($x + $layer) >= $this->gridHeight || ($y + $layer) >= $this->gridWidth)
			return false;
		
			// var_dump($x, $y, $layer);

		//right side
		// echo "== right checks ==\n";
		$lockedY = $y + $layer;
		$maxX = $x + $layer;
		for ($i = $x; $i <= $maxX; $i++) { 
			// var_dump([$i, $y+$layer]);
			if ($this->rGrid[$i][$lockedY] == 'o')
				return false;
		}
		// return true;
		
		//bottom side
		// echo "== bottom checks ==\n";
		$lockedX = $x + $layer;
		$maxY = $y + $layer;
		for ($i = $y; $i < $maxY; $i++) { 
			// var_dump([$lockedX, $i+$layer]);
			if ($this->rGrid[$lockedX][$i] == 'o')
				return false;
		}

		return true;
		
	}

}

$solver = new Solver($argc, $argv);
