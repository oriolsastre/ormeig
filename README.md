# ORMEIG ⛵

> *1. Conjunt de les eines i instruments d’algun ofici o art.*

Entre un ORM (*Object Relational Mapping*) i una DBAL (*Database Abstraction Layer*) simple per a projectes petits que tinc que no requereixen de grans funcionalitats. M'he inspirat sobretot amb [Doctrine](https://www.doctrine-project.org/).

Funcionaria amb [Sqlite](https://sqlite.org/) i... mysql?

> [!CAUTION]
> Encara no funciona, està en desenvolupament.

## Instal·lació

### Composer ###

Afageix aquest repositori al teu `composer.json`
```json
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:oriolsastre/ormeig.git"
    }
],
"require": {
    "sastreo/ormeig": "dev-master"
}
```

I executa `composer update`.

## Documentació

### Models

Cal anotar els models i han d'extendre la classe `Sastreo\Ormeig\Model`.

```php
<?php

declare(strict_types=1);

namespace El\Teu\Projecte;

use Sastreo\Ormeig\Atributs\Columna;
use Sastreo\Ormeig\Atributs\Pk;
use Sastreo\Ormeig\Atributs\Taula;
use Sastreo\Ormeig\Model;

#[Taula('user')]
class Usuari extends Model
{
    #[Columna]
    #[Pk]
    public int $userId;

    #[Columna(nom: 'name')]
    public string $nom;

    #[Columna(unica: true)]
    public string $email;

    #[Columna(nom: 'password')]
    public string $contrassenya;
}
```
#### Atributs
- **Taula**: Si és diferent, donar el nom de la taula a la base de dades.
- **Columna**: Si és diferent, donar el nom de la columna a la base de dades.
- **Pk**: Indicar les columnes que siguin la clau primària.

### Ormeig

L'objecte Ormeig connecta amb la base de dades.
 ```php
use Sastreo\Ormeig\Enums\Driver;

$ormeig = new Ormeig(
    driver: Driver::SQLITE,
    dbname: ':memory:',
);
 ```

### Gestor

El Gestor permet interactuar amb un model. SELECT, INSERT, UPDATE, DELETE.
```php
use El\Teu\Projecte\Usuari;

$ormeig = new Ormeig(
    diver: DRIVER::SQLITE,
    dbname: ':memory:',
);
$gestorUsuari = $ormeig->getGestor(Usuari::class);
```

### Consultes

Crea consultes des del gestor.
```php
$consulta = $gestorUsuari->consulta()
    ->condicio()
    ->condicio()
    ->ordena()
    ->limit();
$resultat = $gestorUsuari->executaConsulta($consulta);
```