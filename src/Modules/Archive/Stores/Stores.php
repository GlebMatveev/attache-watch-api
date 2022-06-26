<?php

namespace App\Modules\Stores;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;

class Stores
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getStoresById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $sql = "SELECT * FROM stores_translate WHERE stores_id = '$id'";
        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    // 

    public function postStores(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        $sql = "START TRANSACTION;		
                INSERT INTO `stores`(`id`) VALUES ('0');
                SET @st_id = LAST_INSERT_ID();";

        if (is_array($data)) {
            $iterator = 0;
            foreach ($data as $dataValue) {
                $sql .= "INSERT INTO `stores_translate`(`product_property_id`, `language_id`,  `entity`, `city`, `store`, `address`, `phone`, `hours`)
                        SELECT @st_id, id, '$dataValue[entity]',  '$dataValue[city]', '$dataValue[store]', '$dataValue[address]', '$dataValue[phone]', '$dataValue[hours]'
                        FROM `languages`
                        WHERE `code`='$dataValue[code]' /* `code`='RUS' */
                        LIMIT 1;";

                $iterator++;
            }
        } else {
            $sql .= "INSERT INTO `stores_translate`(`product_property_id`, `language_id`,  `entity`, `city`, `store`, `address`, `phone`, `hours`)
                    SELECT @st_id, id, '$data[entity]',  '$data[city]', '$data[store]', '$data[address]', '$data[phone]', '$data[hours]'
                    FROM `languages`
                    WHERE `code`='$data[code]' /* `code`='RUS' */
                    LIMIT 1;";
        }

        $sql .= "COMMIT;";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }


    public function getStoresByLangs(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $from = $request->getAttribute('from');
        $to = $request->getAttribute('to');


        $sql = "
            SELECT * FROM stores_translate
            WHERE stores_translate.language_id = '$from'
        ";

        $sql2 = "
            SELECT * FROM stores_translate
            WHERE stores_translate.language_id = '$to'
        ";

        $statement = $this->connection->query($sql);
        $result[] = $statement->fetchAll(PDO::FETCH_OBJ);

        $statement = $this->connection->query($sql2);
        $result[] = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));

        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function putStoresById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        $entity = $data["entity"];
        $city = $data["city"];
        $store = $data["store"];
        $address = $data["address"];
        $phone = $data["phone"];
        $hours = $data["hours"];

        $sql = "UPDATE `stores_translate` SET `entity`=:entity, `city`=:city, `store`=:store, `address`=:address, `phone`=:phone, `hours`=:hours,  WHERE `id`='$id';";

        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':entity', $entity);
        $statement->bindParam(':city', $city);
        $statement->bindParam(':store', $store);
        $statement->bindParam(':address', $address);
        $statement->bindParam(':phone', $phone);
        $statement->bindParam(':hours', $hours);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function deleteStoresById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $sql = "DELETE FROM `stores_translate` WHERE `id`='$id';";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
