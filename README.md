#Siht Framework

Siht é um pequeno framework desenvolvido com objetivo de criar a camada de interceptação e manipulação da requisição RESTful. De forma a separar a criação de objetos modelo, validações e persistência.

O Siht framework utiliza como base o [Slim Framework] (https://packagist.org/packages/slim/slim) para criar a camada de RESTful.

###Como funciona:

1. O Slim receber a requisição e através da rotas é criada uma instância do **Controller** da **Classe** e o **Método* desejado.
2. O **Controller** da classe recebe a chamada do método e executa o método com o mesmo nome e parametro nas classes **Factory**, **HandleIn**, **Repository** e **HandleOut** nesta sequência.

- As classes e métodos **Factory**, **HandleIn**, **Repository** e **HandleOut** são opcionais.
- Qualquer uma destas classes pode retornar uma informação para o solicitante, seja de Erro ou Sucesso.

### Classes e seus objetivos:

- **Controller**: Identificar o método invocado, interceptar e direcionar para a demais classes de manipulação.
- **Factory** *[opicional]*: Fabricar objetos que são enviados em outros formatos diferente de objetos modelo do PHP.
- **HandleIn** *[opicional]*: Manipular as informações enviadas antes de enviar para o Repository, pode-se gerar um Exception neste momento e parar a requisição. Normalmente é utilizada para validar as informações enviadas.
- **Repository** *[opicional]*: Realizar a ~interação com banco de dados.
- **HandleOut** *[opicional]*: Manipular as informações retornadas do Repository, pode-se gera um Exception neste momento e parar a requisição.
- **Model** *[opicional]*: Criar objetos a partir de classe com o atributos e relações entre classes.

# Começando

## Instalar

Recomendamos que você faça a instalação do Siht Framework utilizando o Composer.

[Visite o Siht Framework no Composer] (https://packagist.org/packages/siht/siht)

#### composer.json
```
{
    "require": {
        "siht/siht": "dev-master"
    }    
}
```

## Requerimentos do Sistema

É necessário o **PHP >= 5.3.0**

# Tutorial para um CRUD de Cidade

[Caso preferir, você também pode realizar o download deste exemplo abaixo no Github] (https://github.com/luizdeangeli/SihtTest)

#### Hierarquia de Diretórios

```
\vendor\
\Application\    
    \City\
        \Controller.php
        \Factory.php
        \HandleIn.php
        \Repository.php
        \HandleOut.php    
        \City.php
    \autoload.php
index.php
.htaccess
```

### Conteúdo dos arquivos

##### \index.php
```php
<?php
require_once './vendor/autoload.php';
require_once './Application/autoload.php';
$app = new \Slim\Slim();
$app->group('/City', function () use ($app) {
    $app->get('/', function () use ($app) {
        try {
            $controller = new \Application\City\Controller();
            $result = $controller->findAll();
            $app->status(200);
            $app->response()->setBody(json_encode($result));
        } catch (\Exception $e) {
            $app->status(400);
            $app->response()->setBody(json_encode($e->getMessage()));
        }
    });
    $app->get('/:id', function ($id) use ($app) {
        try {
            $controller = new \Application\City\Controller();
            $result = $controller->findById($id);
            $app->status(200);
            $app->response()->setBody(json_encode($result));
        } catch (\Exception $e) {
            $app->status(400);
            $app->response()->setBody(json_encode($e->getMessage()));
        }
    });
    $app->post('/', function () use ($app) {
        try {
            $request = json_decode($app->request()->getBody());
            $controller = new \Application\City\Controller();
            $result = $controller->create($request);
            $app->status(201); //created
            $app->response()->setBody(json_encode($result));
        } catch (\Exception $e) {
            $app->status(400);
            $app->response()->setBody(json_encode($e->getMessage()));
        }
    });
    $app->put('/:id', function () use ($app) {
        try {
            $request = json_decode($app->request()->getBody());
            $controller = new \Application\City\Controller();
            $result = $controller->update($request);
            $app->status(201);
            $app->response()->setBody(json_encode($result));
        } catch (\Exception $e) {
            $app->status(400);
            $app->response()->setBody(json_encode($e->getMessage()));
        }
    });
    $app->delete('/:id', function ($id) use ($app) {
        try {
            $controller = new \Application\City\Controller();
            $result = $controller->remove((int) $id);
            $app->status(200);
            $app->response()->setBody(json_encode($result));
        } catch (\Exception $e) {
            $app->status(400);
            $app->response()->setBody(json_encode($e->getMessage()));
        }
    });
});
$app->run();
```

##### \ .htaccess
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]
```

##### \Application\autoload.php
```php
<?php
function AutoLoadApplication($pClassName) {
    $fileClass = "./" . $pClassName . ".php";
    if (file_exists($fileClass))
        require_once $fileClass;
}
spl_autoload_register("AutoLoadApplication");
```

##### \Application\City\Controller.php
```php
<?php
namespace Application\City;
class Controller extends \Siht\Controller {
    
}
```

##### \Application\City\Factory.php
```php
<?php
namespace Application\City;
class Factory extends \Siht\Factory {
    public function create(\stdClass $object) {
        $factory = new City();
        $factory->setName(isset($object->name) ? $object->name : FALSE);
        return $factory;
    }
    public function update(\stdClass $object) {
        $factory = new City();
        $factory->setId(isset($object->id) ? (int) $object->id : FALSE);
        $factory->setName(isset($object->name) ? $object->name : FALSE);
        return $factory;
    }
    public function findById($id) {
        return (int) $id;
    }
    public function remove($id) {
        return (int) $id;
    }
}
```

##### \Application\City\HandleIn.php
```php
<?php
namespace Application\City;
use Siht\Validate;
class HandleIn extends \Siht\Handle {
    public function create(City $object) {
        Validate::expected($object->getName(), "Field Name Required")->isNotNull()->isNotEmpty();
    }
    public function update(City $object) {
        Validate::expected($object->getId(), "Field Id Required")->isInteger()->isNotNull()->isNotEmpty();
        Validate::expected($object->getName(), "Field Name Required")->isNotNull()->isNotEmpty();
    }
    public function findById($id) {
        Validate::expected($id, "Field Id Required")->isInteger()->isNotNull()->isNotEmpty();
    }
    public function remove($id) {
        Validate::expected($id, "Field Id Required")->isInteger()->isNotNull()->isNotEmpty();
    }
}
```

##### \Application\City\Repository.php
```php
<?php
namespace Application\City;
class Repository extends \Siht\Repository {
    private $pdo;
    public function __construct() {
        if ($this->pdo = new \PDO('sqlite:mysqlitedb.db')) {
            $sql = "CREATE TABLE city (id INTEGER PRIMARY KEY AUTOINCREMENT, name varchar(100));";
            $this->pdo->query($sql);
        } else {
            die($sqliteerror);
        }
    }
    public function findAll() {
        $fetchs = array();
        $sql = "SELECT id, name FROM city";
        $result = $this->pdo->prepare($sql);
        $result->execute();
        while ($fetch = $result->fetch(\PDO::FETCH_OBJ)) {
            $city = new City();
            $city->setId($fetch->id);
            $city->setName($fetch->name);
            $fetchs[] = $city;
        }
        return $fetchs;
    }
    public function findById($id) {
        $sql = "SELECT id, name FROM city WHERE id=:id";
        $result = $this->pdo->prepare($sql);
        $result->bindValue(":id", $id, \PDO::PARAM_INT);
        $result->execute();
        if ($fetch = $result->fetch(\PDO::FETCH_OBJ)) {
            $city = new City();
            $city->setId($fetch->id);
            $city->setName($fetch->name);
            return $city;
        } else {
            return false;
        }
    }
    public function create(City $city) {
        $sql = "INSERT INTO city(name) VALUES(:name)";
        $result = $this->pdo->prepare($sql);
        $result->bindValue(":name", $city->getName(), \PDO::PARAM_STR);
        $result->execute();
        $id = $this->pdo->lastInsertId();
        return $this->findById($id);
    }
    public function update(City $city) {
        $sql = "UPDATE city SET name=:name WHERE id=:id";
        $result = $this->pdo->prepare($sql);
        $result->bindValue(":id", $city->getId());
        $result->bindValue(":name", $city->getName());
        $result->execute();
        return $this->findById($city->getId());
    }
    public function remove($id) {
        $sql = "DELETE FROM city WHERE id=:id";
        $result = $this->pdo->prepare($sql);
        $result->bindValue(":id", $id);
        $result->execute();
        return $result->rowCount() == 1;
    }
}
```

##### \Application\City\HandleOut.php
```php
<?php
namespace Application\City;
class HandleOut extends \Siht\Handle {
    public function findById($response) {
        if (!$response)
            $this->exception("No records found!");
        return $response;
    }
    public function remove($response) {
        if (!$response)
            $this->exception("No records found!");
        return $response;
    }
}
```

##### \Application\City\City.php
```php
<?php
namespace Application\City;
class City extends \Siht\Model implements \JsonSerializable {
    private $id;
    private $name;
    public function jsonSerialize() {
        return get_object_vars($this);
    }
    function getId() {
        return $this->id;
    }
    function getName() {
        return $this->name;
    }
    function setId($id) {
        $this->id = $id;
    }
    function setName($name) {
        $this->name = $name;
    }
}

```

### Testes das URLs criadas

##### Criar uma Cidade
```
- Método: POST
- URL: /City/
- Conteúdo: {"name":"New York"}
```

##### Alterar uma Cidade
```
- Método: PUT
- URL: /City/
- Conteúdo: {"id":"1","name":"Las Vegas"}
```

##### Recuperar uma Cidade
```
- Método: GET
- URL: /City/1
```

##### Excluido uma Cidade
```
- Método: DELETE
- URL: /City/1
```

##### Listar todas as Cidade
```
- Método: GET
- URL: /City/
```