<?php

class Solver {
	//basic error management
	function __construct (int $argc, array $argv) {
		if ($argc != 2)
			exit("Need one argument\n");

		$this->filename = $argv[1];

		if (!file_exists($this->filename))
			exit("File invalid\n");

		$this->getContent();
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
	}

	function process () {
		// "best square" size and bottom-right corner coordinates
		$biggestSquareWidth = 0;
		$biggestSquareCoords = [0, 0];

		// initialize temporary array
		$row = array_fill(0, $this->gridWidth, 0);

		// main loop, 
		for ($y = 0; $y < $this->gridHeight; $y++) {
			$buffer = [0, 0]; 
			for ($x = 0; $x < $this->gridWidth; $x++) { 
				$buffer[0] = $buffer[1];
				$buffer[1] = $row[$x];

				$currChar = $this->rGrid[$y][$x];

				$tz = $x == 0 ?	0 : $row[$x-1];

				$row[$x] = ($currChar=='.') ? min($buffer[0], $buffer[1], $tz)+1 : 0;
				if ($row[$x] > $biggestSquareWidth) {
					$biggestSquareWidth = $row[$x];
					$biggestSquareCoords = [$x, $y];
				}
			}
		}

		// write x's on the grid
		for ($y = 1+$biggestSquareCoords[1]-$biggestSquareWidth; $y <= $biggestSquareCoords[1]; $y++) {
			for ($x = 1+$biggestSquareCoords[0]-$biggestSquareWidth; $x <= $biggestSquareCoords[0]; $x++)
				$this->rGrid[$y][$x] = 'x';
		}

		// display each row
		foreach ($this->rGrid as $row) {
			echo $row;
		}
	}

}

$solver = new Solver($argc, $argv);
