![Static Badge](https://img.shields.io/badge/PHP:8.3-blue)

# Challengers (Desafios)
Projeto para gerenciar e executar desafios encontrados na internet.

# Instalação

Clonar Repositório:

```bash
git clone git@github.com:Vitor-Guedes/challenges.git .
```

Instalar Dependência:

```bash
composer install
```

# Execução

```bash
{diretorio-raiz}/public/cli.php -c {$codigo} -f {$arquivo}
```

# Implementar Novos

### Estrutura do projeto
* [src](./src/)
    * [Challenges](./src/Challenges/) 
    * [files](./src/files/)
    * [MapperChallenges.json](./src/MapperChallenges.json)

---

#### /src/Challenges - Diretório com as classes que resolvem os desafios

*src/Challenges/NumericalExpressions.php*

```php
<?php
namespace Guedes\Challenges\Challenges;

use Guedes\Challenges\Challenge;

/**
 * https://{site-do-desafio}.com/{url}
 * 
 * Desafio - {Nome-Do-Desafio}
 */
class NumericalExpressions extends Challenge
{   
    public function resolve()
    {
        // implementação do desafio
    }
}
```

--- 

#### /src/files - Diretório coms os arquivos que devem ser resolvidos

*src/files/numeric_expressions_file.txt*

```
1 + 3
2 - 3 * 2
2 ^ 3 / 4
0 * 5 * (4 + 1)
5 + 5 / 0
5 + + 1
```

---

#### /src/MapperChallenges.json - Arquivo que mapeia os comandos e as classes de solução

*src/MapperChallenges.json*

```json
{
    "commands": {
        "ne": "\\Guedes\\Challenges\\Challenges\\NumericalExpressions",
        "pot": "\\Guedes\\Challenges\\Challenges\\PowerOfTwo"
    }
}
```

--- 

### Exemplo prático

```bash
php public/cli.php -c ne -f numeric_expressions_file.txt
```
### Resultado

```json
{
    "1 + 3": "4",
    "2 - 3 * 2": "-4",
    "2 ^ 3 \/ 4": "2",
    "0 * 5 * (4 + 1)": "0",
    "5 + 5 \/ 0": "N\u00e3o pode dividir por 0",
    "5 + + 1": "#3 - Sintaxy error: 5++1"
}
```