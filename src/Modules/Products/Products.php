<?php

namespace App\Modules\Products;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;

class Products
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllProductsByLang(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $lang = $request->getAttribute('lang');

        $sql = "SELECT 
            properties.id,
            properties.title_" . $lang . " " .
            "FROM properties;";

        $statement = $this->connection->query($sql);
        $properties = $statement->fetchAll(PDO::FETCH_ASSOC);
        $sql = 0;
        $statement = 0;


        $sql = "SELECT
            products.id,
            products.category_id AS 'category_id',
            categories.title_" . $lang . " AS 'category',
            products.subcategory_id AS 'subcategory_id',
            subcategories.title_" . $lang . " AS 'subcategory',
            products.title_" . $lang . ",
            products.subtitle_" . $lang . ",
            products.articul,
            products.social_link_" . $lang . ",
            products.price,
            products.photo_main,
            products.photo_add_1,
            products.photo_add_2,
            products.photo_back,
            products.photo_gallery_bg,
            products.photo_gallery_md_1,
            products.photo_gallery_md_2,
            products.photo_gallery_sm_1,
            products.photo_gallery_sm_2";

        foreach ($properties as $value) {
            $sql .= ", MAX(CASE WHEN property_values.property_id = '" . $value["id"] . "' AND property_values.product_id = products.id THEN property_values.title_" . $lang . " END) '" . $value["id"] . "'";
        }

        $sql .= "FROM products

            JOIN
            categories
            ON
            categories.id = products.category_id

            JOIN
            subcategories
            ON
            subcategories.id = products.subcategory_id

            JOIN
            property_values

            GROUP BY
            products.id;";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function getAllProducts(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $sql = "SELECT 
            properties.id,
            properties.title_rus,
            properties.title_eng
            FROM properties;";

        $statement = $this->connection->query($sql);
        $properties = $statement->fetchAll(PDO::FETCH_ASSOC);
        $sql = 0;
        $statement = 0;


        $sql = "SELECT
            products.id,
            products.category_id AS 'category_id',
            categories.title_rus AS 'category_rus',
            categories.title_eng AS 'category_eng',
            products.subcategory_id AS 'subcategory_id',
            subcategories.title_rus AS 'subcategory_rus',
            subcategories.title_eng AS 'subcategory_eng',
            products.title_rus,
            products.title_eng,
            products.subtitle_rus,
            products.subtitle_eng,
            products.articul,
            products.social_link_rus,
            products.social_link_eng,
            products.price,
            products.photo_main,
            products.photo_add_1,
            products.photo_add_2,
            products.photo_back,
            products.photo_gallery_bg,
            products.photo_gallery_md_1,
            products.photo_gallery_md_2,
            products.photo_gallery_sm_1,
            products.photo_gallery_sm_2";

        foreach ($properties as $value) {
            $sql .= ", MAX(CASE WHEN property_values.property_id = '" . $value["id"] . "' AND property_values.product_id = products.id THEN property_values.title_rus END) '" . $value["id"] . "_rus" . "'";
            $sql .= ", MAX(CASE WHEN property_values.property_id = '" . $value["id"] . "' AND property_values.product_id = products.id THEN property_values.title_eng END) '" . $value["id"] . "_eng" . "'";
        }

        $sql .= "FROM products

            JOIN
            categories
            ON
            categories.id = products.category_id

            JOIN
            subcategories
            ON
            subcategories.id = products.subcategory_id

            JOIN
            property_values

            GROUP BY
            products.id;";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function getProductsById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $sql = "SELECT 
            properties.id,
            properties.title_rus,
            properties.title_eng
            FROM properties;";

        $statement = $this->connection->query($sql);
        $properties = $statement->fetchAll(PDO::FETCH_ASSOC);
        $sql = 0;
        $statement = 0;


        $sql = "SELECT
            products.id,
            products.category_id AS 'category_id',
            categories.title_rus AS 'category_rus',
            categories.title_eng AS 'category_eng',
            products.subcategory_id AS 'subcategory_id',
            subcategories.title_rus AS 'subcategory_rus',
            subcategories.title_eng AS 'subcategory_eng',
            products.title_rus,
            products.title_eng,
            products.subtitle_rus,
            products.subtitle_eng,
            products.articul,
            products.social_link_rus,
            products.social_link_eng,
            products.price,
            products.photo_main,
            products.photo_add_1,
            products.photo_add_2,
            products.photo_back,
            products.photo_gallery_bg,
            products.photo_gallery_md_1,
            products.photo_gallery_md_2,
            products.photo_gallery_sm_1,
            products.photo_gallery_sm_2";

        foreach ($properties as $value) {
            $sql .= ", MAX(CASE WHEN property_values.property_id = '" . $value["id"] . "' AND property_values.product_id = products.id THEN property_values.title_rus END) '" . $value["id"] . "_rus" . "'";
            $sql .= ", MAX(CASE WHEN property_values.property_id = '" . $value["id"] . "' AND property_values.product_id = products.id THEN property_values.title_eng END) '" . $value["id"] . "_eng" . "'";
        }

        $sql .= "FROM products

            JOIN
            categories
            ON
            categories.id = products.category_id

            JOIN
            subcategories
            ON
            subcategories.id = products.subcategory_id

            JOIN
            property_values
            
            WHERE
            products.id = " . $id . ";";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function putProductsById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();

        $str = substr($data["imageMainUrl"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageMainUrl"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_main"] = $imagePath;
        }

        $str = substr($data["imageAdd1Url"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageAdd1Url"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_add_1"] = $imagePath;
        }

        $str = substr($data["imageAdd2Url"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageAdd2Url"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_add_2"] = $imagePath;
        }

        $str = substr($data["imageBackUrl"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageBackUrl"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_back"] = $imagePath;
        }

        $str = substr($data["imageGalleryBgUrl"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageGalleryBgUrl"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_gallery_bg"] = $imagePath;
        }

        $str = substr($data["imageGalleryMd1Url"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageGalleryMd1Url"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_gallery_md_1"] = $imagePath;
        }

        $str = substr($data["imageGalleryMd2Url"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageGalleryMd2Url"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_gallery_md_2"] = $imagePath;
        }

        $str = substr($data["imageGallerySm1Url"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageGallerySm1Url"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_gallery_sm_1"] = $imagePath;
        }

        $str = substr($data["imageGallerySm2Url"], 0, 4);
        if ($str == "data") {
            $arrImg = explode(',', $data["imageGallerySm2Url"]);
            $base64_decode  = base64_decode($arrImg[1]);
            $serverPath = $_SERVER['DOCUMENT_ROOT'];
            $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
            $dirSave = $serverPath . $imagePath;
            file_put_contents($dirSave, $base64_decode);
            $data["photo_gallery_sm_2"] = $imagePath;
        }

        $sql = "SELECT 
            properties.id,
            properties.title_rus,
            properties.title_eng
            FROM properties;";

        $statement = $this->connection->query($sql);
        $properties = $statement->fetchAll(PDO::FETCH_ASSOC);
        $sql = 0;
        $statement = 0;


        $sql = "UPDATE
            products
            SET 
            products.category_id = '" . $data["category_id"] . "', " .
            "products.subcategory_id = '" . $data["subcategory_id"] . "', " .
            "products.title_rus = '" . $data["title_rus"] . "', " .
            "products.title_eng = '" . $data["title_eng"] . "', " .
            "products.subtitle_rus = '" . $data["subtitle_rus"] . "', " .
            "products.subtitle_eng = '" . $data["subtitle_eng"] . "', " .
            "products.articul = '" . $data["articul"] . "', " .
            "products.social_link_rus = '" . $data["social_link_rus"] . "', " .
            "products.social_link_eng = '" . $data["social_link_eng"] . "', " .
            "products.price = '" . $data["price"] . "', " .
            "products.photo_main = '" . $data["photo_main"] . "', " .
            "products.photo_add_1 = '" . $data["photo_add_1"] . "', " .
            "products.photo_add_2 = '" . $data["photo_add_2"] . "', " .
            "products.photo_back = '" . $data["photo_back"] . "', " .
            "products.photo_gallery_bg = '" . $data["photo_gallery_bg"] . "', " .
            "products.photo_gallery_md_1 = '" . $data["photo_gallery_md_1"] . "', " .
            "products.photo_gallery_md_2 = '" . $data["photo_gallery_md_2"] . "', " .
            "products.photo_gallery_sm_1 = '" . $data["photo_gallery_sm_1"] . "', " .
            "products.photo_gallery_sm_2 = '" . $data["photo_gallery_sm_2"] . "' " .
            "WHERE
            products.id = '" . $id . "';";

        foreach ($properties as $value) {
            $sql .= "UPDATE
            property_values
            SET
            property_values.title_rus = '" . $data[$value["id"] . "_rus"] . "',
            property_values.title_eng = '" . $data[$value["id"] . "_eng"] . "'
            
            WHERE
            property_values.property_id = '" . $value["id"] . "'
            AND
            property_values.product_id = '" . $id . "';";
        }

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function postProducts(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        // var_dump($data);

        $sql = "SELECT auto_increment FROM information_schema.tables WHERE table_schema = 'u1448502_attache' AND table_name = 'products';";
        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $id = (int)$result[0]["auto_increment"];
        $sql = 0;
        $statement = 0;
        $result = 0;


        // print_r($id);

        $arrImg = explode(',', $data["imageMainUrl"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        if (!is_dir($serverPath . '/assets/img/products/' . $id)) {
            mkdir($serverPath . '/assets/img/products/' . $id);
        }
        file_put_contents($dirSave, $base64_decode);
        $data["photo_main"] = $imagePath;

        $arrImg = explode(',', $data["imageAdd1Url"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        file_put_contents($dirSave, $base64_decode);
        $data["photo_add_1"] = $imagePath;

        $arrImg = explode(',', $data["imageAdd2Url"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        file_put_contents($dirSave, $base64_decode);
        $data["photo_add_2"] = $imagePath;

        $arrImg = explode(',', $data["imageBackUrl"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        file_put_contents($dirSave, $base64_decode);
        $data["photo_back"] = $imagePath;

        $arrImg = explode(',', $data["imageGalleryBgUrl"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        file_put_contents($dirSave, $base64_decode);
        $data["photo_gallery_bg"] = $imagePath;

        $arrImg = explode(',', $data["imageGalleryMd1Url"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        file_put_contents($dirSave, $base64_decode);
        $data["photo_gallery_md_1"] = $imagePath;

        $arrImg = explode(',', $data["imageGalleryMd2Url"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        file_put_contents($dirSave, $base64_decode);
        $data["photo_gallery_md_2"] = $imagePath;

        $arrImg = explode(',', $data["imageGallerySm1Url"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        file_put_contents($dirSave, $base64_decode);
        $data["photo_gallery_sm_1"] = $imagePath;

        $arrImg = explode(',', $data["imageGallerySm2Url"]);
        $base64_decode  = base64_decode($arrImg[1]);
        $serverPath = $_SERVER['DOCUMENT_ROOT'];
        $imagePath = '/assets/img/products/' . $id . '/' . uniqid() . '.jpg';
        $dirSave = $serverPath . $imagePath;
        file_put_contents($dirSave, $base64_decode);
        $data["photo_gallery_sm_2"] = $imagePath;


        $sql = "SELECT 
            properties.id,
            properties.title_rus,
            properties.title_eng
            FROM properties;";

        $statement = $this->connection->query($sql);
        $properties = $statement->fetchAll(PDO::FETCH_ASSOC);
        $sql = 0;
        $statement = 0;

        // var_dump($properties);


        $sql = "INSERT INTO
        products(
            products.category_id,
            products.subcategory_id,
            products.title_rus,
            products.title_eng,
            products.subtitle_rus,
            products.subtitle_eng,
            products.articul,
            products.social_link_rus,
            products.social_link_eng,
            products.price,
            products.photo_main,
            products.photo_add_1,
            products.photo_add_2,
            products.photo_back,
            products.photo_gallery_bg,
            products.photo_gallery_md_1,
            products.photo_gallery_md_2,
            products.photo_gallery_sm_1,
            products.photo_gallery_sm_2
        ) VALUES (
            '$data[category_id]',
            '$data[subcategory_id]',
            '$data[title_rus]',
            '$data[title_eng]',
            '$data[subtitle_rus]',
            '$data[subtitle_eng]',
            '$data[articul]',
            '$data[social_link_rus]',
            '$data[social_link_eng]',
            '$data[price]',
            '$data[photo_main]',
            '$data[photo_add_1]',
            '$data[photo_add_2]',
            '$data[photo_back]',
            '$data[photo_gallery_bg]',
            '$data[photo_gallery_md_1]',
            '$data[photo_gallery_md_2]',
            '$data[photo_gallery_sm_1]',
            '$data[photo_gallery_sm_2]'
        );";

        foreach ($properties as $value) {
            $sql .= "INSERT INTO
            property_values(          
                property_values.title_rus,
                property_values.title_eng,
                property_values.property_id,
                property_values.product_id
            ) VALUES (
                '" . $data[$value["id"] . "_rus"] . "',
                '" . $data[$value["id"] . "_eng"] . "',
                '" . $value["id"] . "',
                '" . $id . "');";
        }

        print_r($data[$value["id"] . "_rus"]);
        print_r($data[$value["id"] . "_eng"]);

        $statement = $this->connection->prepare($sql);

        $result = $statement->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }

    public function deleteProductsById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $sql = "DELETE FROM products WHERE products.id = '" . $id . "';";
        $sql .= "DELETE FROM property_values WHERE property_values.product_id = '" . $id . "';";

        $statement = $this->connection->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    }
}
