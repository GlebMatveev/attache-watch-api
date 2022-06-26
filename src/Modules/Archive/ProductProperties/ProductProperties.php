<?php

namespace App\Modules\ProductProperties;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;

class ProductProperties
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getProductPropertiesById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $sql = "SELECT * FROM product_properties_translate WHERE product_property_id = '$id'";
        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    // 

    public function postProductProperties(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        $log = date('Y-m-d H:i:s') . '\n' . print_r($data, true);
        file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);

        $sql = "START TRANSACTION;		
                INSERT INTO `product_properties`(`type`, `weight`, `filter`, `activity`) VALUES ('select','100','1','1');
                SET @prod_props_id = LAST_INSERT_ID();";

        if (is_array($data)) {
            $iterator = 0;
            foreach ($data as $dataValue) {
                $sql .= "INSERT INTO `product_properties_translate`(`product_property_id`, `language_id`, `title`)
                        SELECT @prod_props_id, id, '$dataValue[title]'
                        FROM `languages`
                        WHERE `code`='$dataValue[code]' /* `code`='RUS' */
                        LIMIT 1;";

                $iterator++;
            }
        } else {
            $sql .= "INSERT INTO `product_properties_translate`(`product_property_id`, `language_id`, `title`)
                    SELECT @prod_props_id, id, '$data[title]'
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


    public function getProductPropertiesByLangs(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $from = $request->getAttribute('from');
        $to = $request->getAttribute('to');


        $sql = "
            SELECT
            product_properties_translate.id,
            product_properties_translate.product_property_id,
            product_properties_translate.language_id,
            product_properties_translate.title
            FROM product_properties_translate
            WHERE product_properties_translate.language_id = '$from'
        ";

        $sql2 = "
            SELECT
            product_properties_translate.id,
            product_properties_translate.product_property_id,
            product_properties_translate.language_id,
            product_properties_translate.title
            FROM product_properties_translate
            WHERE product_properties_translate.language_id = '$to'
        ";



        $statement = $this->connection->query($sql);
        $result[] = $statement->fetchAll(PDO::FETCH_OBJ);

        $log = date('Y-m-d H:i:s') . ' ' . print_r($result, true);
        file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);




        $statement = $this->connection->query($sql2);
        $result[] = $statement->fetchAll(PDO::FETCH_OBJ);

        $log = date('Y-m-d H:i:s') . '\n' . print_r($result, true);
        file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);

        $response->getBody()->write(json_encode($result));

        $log = date('Y-m-d H:i:s') . '\n' . print_r($result, true);
        file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);

        $log = date('Y-m-d H:i:s') . '\n' . print_r(json_encode($result), true);
        file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function putProductPropertiesById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        $title = $data["title"];

        $sql = "UPDATE `product_properties_translate` SET `title`=:title WHERE `id`='$id';";

        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':title', $title);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function deleteProductPropertiesById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $sql = "DELETE FROM `product_properties` WHERE `id`='$id';";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
