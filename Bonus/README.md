# BSQ


### Présentation
Le **BSQ** est un problème d'algorithmique courant, où le but est de concevoir un algorithme prenant une grille de caractères, vide (symbolisés par `.`) ou contenant un obstacle `o` en entrée, et d'y trouver le plus grand carré sans obstacle (priorisé dans le sens de la lecture en cas de conflit), avant de remplacer les caractères composants ce dernier par des `x`.

#### Exemple
Entrée:
```
5
ooo..
o....
o.o.o
o....
ooo..
```
Sortie:
```
oooxx
o..xx
o.o.o
o....
ooo..
```

#### Installation

Cloner le dépôt

```
git clone git@github.com:thguerre/ALG_BSQ.git
```

Créer un fichier (veillez à respecter le format d'Entrée présent ci-dessus !)
OU
Générez un fichier automatiquement (voir ci-dessous)
```
nano/vim [fichier]
```

Générer un fichier automatiquement
```
perl generator.pl [largeur] [hauteur] [densité] > [fichier]
```


Lancez le script
```
php bsq.php [fichier]
```

### Bonus
J'ai réalisé quelques bonus pour ce projet:
- Une version en C, dans le but de repousser les limites, tant de mes connaissances du développement en langages à bas niveau, tant techniques: Je voulais voir à quel point il était possible de diminuer le temps d'exécution
- Des tests unitaires pour les deux versions (PHP et C)

#### Installation version C

Pour compiler la seconde version, il vous faudra aller dans le répertoire Bonus
```
cd Bonus
```

Enfin, compiler
```
make
```

Avant d'exécuter le binaire
```
./bsq [fichier]
```

#### Utilisation des tests unitaires

Extraire les grilles
```
unzip mouli_maps.zip
```

Exécuter les tests
```
sh tests_php.sh
```
OU
```
sh tests_c.sh
```
