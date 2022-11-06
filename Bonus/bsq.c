
#include <stdio.h>
#include <stdlib.h>
#include "flex.c"

int main (int argc, char **argv) {
	// removing first argv './bsq'
	argc--; argv++;

	// argument number check
	if (argc != 1) {
		printf("Wrong command usage !\n", argc);
		exit(0);
	}
	
	char* test = bsq(argv[0]);
}
