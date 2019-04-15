# Test de recrutement ingénieur PHP / DATA

## Prérequis

### Dépendances

* PHP 5.6+
* composer : gestionnaire de dépendances pour PHP https://getcomposer.org/
* système d'exploitation : ce test a été testé et validé sur un environnement de type GNU/Linux 64bits.

### Installation

```shell
composer install
```

## Le test

Le but de ce test est d'implémenter une méthode qui reçoit en argument le nom d'un fichier contenant des informations sur les stocks d'un ensemble de magasins et qui, à partir du contenu de ce fichier, calcule et retourne un ensemble de statistiques.

Très concrètement il s'agit d'implémenter la méthode `FileAnalyzer::generateStats()` définie dans le fichier `src/Ex1/FileAnalyzer.php`.

Ensuite, il suffit de lancer le test unitaire de cette méthode via la commande suivante pour savoir si le test est réussi.

```shell
./vendor/bin/phpunit
```

Bien entendu, vous pouvez modifier votre code et relancer le test unitaire autant de fois que nécessaire.

### Le fichier de stocks

Ce fichier contient des enregistrements de type "stock", c'est à dire qui représentent la quantité en stock d'un SKU (référence d'une déclinaison d'un produit) dans un magasin.

Chaque enregistrement associe un couple SKU / magasin à une quantité en stock.
Un couple SKU / magasin ne peut pas apparaître plus d'une fois dans ce fichier.

La structure du fichier est la suivante :
* 1 ligne correspond à un enregistrement.
* un enregistrement est composé de plusieurs champs séparés par une virgule.
* les champs sont, dans l'ordre :
  * `store_id` : l'identifiant du magasin.
  * `sku_id` : l'identifiant du SKU.
  * `quantity` : la quantité en stock.

### Le format des statistiques à générer

Le rôle de la méthode `generateStats()` est donc de lire ce fichier de stocks et de calculer des statistiques retournée via un tableau associatif au format suivant :

* `store_count` : Le nombre de magasins disctincts dans le fichier.
* `sku_count` : Le nombre de SKU disctincts dans le fichier.
* `max_quantity` : La quantité maximum disponible.
* `avg_quantity`: La quantité moyenne disponible.
* `availability_rate`: Le taux de stocks disponibles (c'est à dire avec une quantité supérieure à 0), entre 0 et 1 (1 équivaut à 100%).
* `stores` : un tableau associatif contenant des statistiques pour chaque magasin présent dans le fichier. Les clés de ce tableau sont les identifiants des magasins et les valeurs sont des tableaux associatifs, représentants les statistiques d'un magasin, au format suivant:
  * `max_quantity` : La quantité maximum disponible dans le magasin.
  * `avg_quantity`: La quantité moyenne disponible dans le magasin.
  * `available_sku_count` : Le nombre de SKU disponibles (c'est à dire avec une quantité en stock supérieure à 0) dans le magasin.
  * `availability_rate`: Le taux de SKU disponibles (c'est à dire avec une quantité supérieure à 0), entre 0 et 1 (1 équivaut à 100%), dans le magasin.

Pour mieux comprendre ce format voici un extrait du résultat attendu :

```php
array (
  'store_count' => 185,
  'sku_count' => 25411,
  'max_quantity' => 21,
  'avg_quantity' => 4.5866074173027851,
  'availability_rate' => 0.90883582019704168,
  'stores' =>
  array (
    124 =>
    array (
      'max_quantity' => 17,
      'avg_quantity' => 4.5671953091180981,
      'availability_rate' => 0.90815001377356264,
      'available_sku_count' => 23077,
    ),
    125 =>
    array (
      'max_quantity' => 15,
      'avg_quantity' => 4.5704616111132976,
      'availability_rate' => 0.9080319546653024,
      'available_sku_count' => 23074,
    ),
    ...
  )
)
```

### Les contraintes
* le formattage du code doit être le plus proche possible de PSR-2 https://www.php-fig.org/psr/psr-2/.
* le nommage des variables ou autres identifiants doit être en anglais et clairement refléter leur contenu.
* le code doit être d'une manière générale lisible et le plus simple possible.
* le code doit être le plus efficace possible en terme de complexité algorithmique et mémoire.
* la consommation de la mémoire doit être autant que possible inférieure à 6MB et en aucun cas supérieure à 20MB. Pour information notre solution consomme 3.6MB avec PHP 5.6. Attention il s'agit là de la consommation de mémoire de la méthode et non de l'ensemble du test dont la valeur est affichée par PHPUnit à la fin de l'exécution du test.

### Quelques remarques
* la premiere execution du test unitaire sera plus longue, le temps de générer le fichier de stocks (dans le répertoire `tmp`). Les executions suivantes n'auront plus à le faire.
* le tableau de statistiques attendu est stocké au format JSON dans le fichier `tests/Ex1/expected_stats.json`.
* le contrôle du tableau de statistiques générées par rapport à celui attendu sera fait en tenant compte d'éventuelles erreurs d'arrondi relatives aux calculs sur des nombres à virgule flottante. Plus précisément, un epsilon de 0.04 sera utilisé pour les comparaisons de nombres.
