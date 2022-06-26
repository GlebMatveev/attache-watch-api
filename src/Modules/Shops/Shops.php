<?php

namespace App\Modules\Shops;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;

class Shops
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllShops(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $sql = "SELECT
        shops.id,
        shops.entity_rus,
        shops.entity_eng,
        shops.city_rus,
        shops.city_eng,
        shops.shop_rus,
        shops.shop_eng,
        shops.address_rus,
        shops.address_eng,
        shops.phone_rus,
        shops.phone_eng,
        shops.hours_rus,
        shops.hours_eng
        FROM shops;";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function getAllShopsByLang(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $lang = $request->getAttribute('lang');

        $sql = "SELECT
            shops.id,
            shops.entity_" . $lang . ", " .
            "shops.city_" . $lang . ", " .
            "shops.shop_" . $lang . ", " .
            "shops.address_" . $lang . ", " .
            "shops.phone_" . $lang . ", " .
            "shops.hours_" . $lang . " " .
            "FROM shops;";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function postShops(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        $sql = "INSERT INTO
        shops(
            shops.entity_rus,
            shops.entity_eng,
            shops.city_rus,
            shops.city_eng,
            shops.shop_rus,
            shops.shop_eng,
            shops.address_rus,
            shops.address_eng,
            shops.phone_rus,
            shops.phone_eng,
            shops.hours_rus,
            shops.hours_eng
        ) VALUES (
            '$data[entity_rus]',
            '$data[entity_eng]',
            '$data[city_rus]',
            '$data[city_eng]',
            '$data[shop_rus]',
            '$data[shop_eng]',
            '$data[address_rus]',
            '$data[address_eng]',
            '$data[phone_rus]',
            '$data[phone_eng]',
            '$data[hours_rus]',
            '$data[hours_eng]'
        );";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function deleteShopsById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $sql = "DELETE FROM shops WHERE shops.id = '" . $id . "';";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function putShopsById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();

        $sql = "UPDATE
            shops
            SET 
            shops.entity_rus = '" . $data["entity_rus"] . "', " .
            "shops.entity_eng = '" . $data["entity_eng"] . "', " .
            "shops.city_rus = '" . $data["city_rus"] . "', " .
            "shops.city_eng = '" . $data["city_eng"] . "', " .
            "shops.shop_rus = '" . $data["shop_rus"] . "', " .
            "shops.shop_eng = '" . $data["shop_eng"] . "', " .
            "shops.address_rus = '" . $data["address_rus"] . "', " .
            "shops.address_eng = '" . $data["address_eng"] . "', " .
            "shops.phone_rus = '" . $data["phone_rus"] . "', " .
            "shops.phone_eng = '" . $data["phone_eng"] . "', " .
            "shops.hours_rus = '" . $data["hours_rus"] . "', " .
            "shops.hours_eng = '" . $data["hours_eng"] . "' " .
            "WHERE
            shops.id = '" . $id . "';";

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
