#include <stdbool.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <unistd.h>
#include <fcntl.h>
#include <math.h>
#include <stdlib.h>
#include <stdio.h>
#include <string.h>

// returns lowest of 3 values
int min (int x, int y, int z) {
	if (x<y && x<z)
		return x;

	return y<z ? y : z;
}

// the body of the process, didn't knew how to return restructured data in C
char* bsq (char* filename) {

	//check file existence
	if (access(filename, F_OK) != 0) {
		printf("File \"%s\" doesn't exist !\n", filename);
		exit(0);
	}

	// stat file to get file size
	struct stat file;	
	stat(filename, &file);
	const int fileSize = file.st_size;

	// allocate appropriate amount of memory to put the file content into
	char* fileContent = malloc(fileSize+1);

	// read file content properly
	int handle = open(filename, 'r');
	read(handle, fileContent, fileSize);
	close(handle);

	// dirty atoi to get grid height
	int gridHeight = atoi(fileContent);

	// figure grid width out from file size and height	
	int counter = -1;
	char checkChar = ' ';
	do {
		checkChar = fileContent[counter];
		counter++;
	} while (checkChar != '\n');
	int gridStart = counter;
	int gridWidth = (int) ((fileSize - gridStart)/gridHeight) -1;


	int biggestSize = 0;
	int biggestCoords[2] = {0, 0};
	int row[gridWidth];
	int buffer[2] = {0, 0};

	// scanning row initialization
	for (int i = 0; i<gridWidth; i++)
		row[i] = 0;

	// main loop
	for (int y = 0; y < gridHeight; y++) {
		// buffer clearing
		buffer[0] = 0;
		buffer[1] = 0;

		for (int x = 0; x < gridWidth; x++) {
			// buffer shifting
			buffer[0] = buffer[1];
			buffer[1] = row[x];

			char currChar = fileContent[gridStart + ((y*gridWidth)+y) + x];
			int tz = x == 0 ? 0 : row[x-1];

			// row actualisation
			row[x] = currChar == '.' ? min(buffer[0], buffer[1], tz)+1 : 0;

			// if new best -> register size and coordinates
			if (row[x] > biggestSize) {
				biggestSize = row[x];
				memcpy(biggestCoords, (int[]){x, y}, 8);
			}
		}
	}

	// setting x's 
	for (int y=(biggestCoords[1]-biggestSize)+1; y <= biggestCoords[1] ; y++) {
		for (int x=(biggestCoords[0]-biggestSize)+1; x <= biggestCoords[0] ; x++) {
			fileContent[gridStart + ((y*gridWidth)+y) + x] = 'x';
		}
	}

	// printing grid, excluding first line
	fileContent[fileSize] = '\0';
	printf("%s", fileContent + gridStart);

	// free up memory	
	free(fileContent);

	return "test";
}
