# BSQ


### Présentation
Le **BSQ** est un problèma d'algorithmique courant, où le but est de concevoir un algorithme prenant une grille de caractères, vide (symbolisés par `.`) ou contenant un obstacle `o` en entrée, et d'y trouver le plus grand carré sans obstacle, avant de remplacer les caractères composant ce dernier par des `x`.

#### Exemple
Entrée:
```
5
ooo..
o....
o.o.o
o...o
ooooo
```
Sortie:
```
oooxx
o..xx
o.o.o
o...o
ooooo
```

#### Installation

Clone git

```
git clone git@github.com:thguerre/ALG_BSQ.git
```

creation fichier 

```
nano/vim [fichier]
```

execution

```
php bsq.php [fichier]
```

### Bonus
J'ai réalisé quelques bonus:
- Une version en C, dans le but de repousser les limites, tant de mes connaissances du développement en langages à bas niveau, tant techniques: Je voulais voir à quel point il était possible de diminuer le temps d'exécution
- Des tests unitaires pour les deux versions (PHP et C)

#### Installation version C

Pour compiler la seconde version, il vous faudra aller dans le répertoire Bonus
```
cd Bonus
```

compiler
```
gcc bsq.c -o bsq
```

execution
```
./bsq [fichier]
```

#### Utilisation des tests unitaires

dezipper
```
unzip mouli_maps
```

utiliser
```
sh tests_php.sh
```
