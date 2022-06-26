<?php

namespace App\Modules\Categories;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;

class Categories
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllCategories(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // $sql = "
        //     SELECT 
        //     categories_translate.category_id, 
        //     categories_translate.title, 
        //     languages.code,
        //     categories.created_at, 
        //     categories.modified_at 
        //     FROM categories_translate
        //     JOIN categories 
        //     JOIN languages
        //     ON languages.id = categories_translate.language_id
        // ";
        $sql = "SELECT * FROM categories_translate";
        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function getCategoryById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $sql = "SELECT * FROM categories_translate WHERE category_id = '$id'";
        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function postCategory(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        // $lang = $data["lang"];
        // $title = $data["title"];

        $sql = "START TRANSACTION;			
                INSERT INTO `categories`(`id`) VALUES ('0');
                SET @cat_id = LAST_INSERT_ID();";

        if (is_array($data)) {
            $iterator = 0;
            foreach ($data as $dataValue) {
                $sql .= "INSERT INTO `categories_translate`(`category_id`, `language_id`, `title`)
                        SELECT @cat_id, id, '$dataValue[title]'
                        FROM `languages`
                        WHERE `code`='$dataValue[code]'
                        LIMIT 1;";

                $iterator++;
            }
        } else {
            $sql .= "INSERT INTO `categories_translate`(`category_id`, `language_id`, `title`)
                    SELECT @cat_id, id, '$data[title]'
                    FROM `languages`
                    WHERE `code`='$data[code]'
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

    public function getCategoriesByLang(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $sql = "
            SELECT
            categories_translate.category_id,
            categories_translate.title
            FROM categories_translate
            WHERE categories_translate.language_id = '$id'
        ";
        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function getCategoriesByLangs(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $from = $request->getAttribute('from');
        $to = $request->getAttribute('to');


        $sql = "
            SELECT
            categories_translate.id,
            categories_translate.category_id,
            categories_translate.title
            FROM categories_translate
            WHERE categories_translate.language_id = '$from'
        ";

        $sql2 = "
            SELECT
            categories_translate.id,
            categories_translate.category_id,
            categories_translate.title
            FROM categories_translate
            WHERE categories_translate.language_id = '$to'
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

    public function putCategoryById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        $title = $data["title"];

        $sql = "UPDATE `categories_translate` SET `title`=:title WHERE `id`='$id';";

        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':title', $title);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function deleteCategoryById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $sql = "DELETE FROM `categories` WHERE `id`='$id';";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
