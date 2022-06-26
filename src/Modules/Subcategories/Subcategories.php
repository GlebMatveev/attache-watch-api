<?php

namespace App\Modules\Subcategories;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;

class Subcategories
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllSubcategories(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $sql = "SELECT
        subcategories.id,
        subcategories.title_rus,
        subcategories.title_eng
        FROM subcategories";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function getAllSubcategoriesByLang(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $lang = $request->getAttribute('lang');

        $sql = "SELECT
            subcategories.id,
            subcategories.title_" . $lang . " " .
            "FROM subcategories;";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function getAllSubcategoriesByCategoryId(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $sql = "SELECT
            products.subcategory_id AS 'subcategory_id',
            subcategories.title_rus AS 'title_rus',
            subcategories.title_eng AS 'title_eng'
            
            FROM products
            
            JOIN
            subcategories
            ON
            subcategories.id = products.subcategory_id
            
            WHERE
            products.category_id = '" . $id . "'
            
            GROUP BY
            products.subcategory_id";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function postSubcategories(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        $sql = "INSERT INTO
        subcategories(
            subcategories.title_rus,
            subcategories.title_eng
        ) VALUES (
            '$data[title_rus]',
            '$data[title_eng]'
        );";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function deleteSubcategoriesById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $sql = "DELETE FROM subcategories WHERE subcategories.id = '" . $id . "';";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function putSubcategoriesById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();

        $sql = "UPDATE
            subcategories
            SET 
            subcategories.title_rus = '" . $data["title_rus"] . "', " .
            "subcategories.title_eng = '" . $data["title_eng"] . "' " .
            "WHERE
            subcategories.id = '" . $id . "';";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
