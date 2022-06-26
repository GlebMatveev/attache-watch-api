<?php

// use Psr\Http\Message\ResponseInterface;
// use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
// use Tuupola\Middleware\CorsMiddleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

return function (App $app) {

    // $app->get('/hello', function (ServerRequestInterface $request, ResponseInterface $response) {
    //     $response->getBody()->write('Hello World');
    //     return $response;
    // });
    // $app->add(CorsMiddleware::class);

    // CORS
    // $app->add(new Tuupola\Middleware\CorsMiddleware);

    $app->add(function (Request $request, RequestHandlerInterface $handler): Response {
        $routeContext = RouteContext::fromRequest($request);
        $routingResults = $routeContext->getRoutingResults();
        $methods = $routingResults->getAllowedMethods();
        $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

        $response = $handler->handle($request);

        $response = $response->withHeader('Access-Control-Allow-Origin', '*');
        $response = $response->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
        $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);

        // Optional: Allow Ajax CORS requests with Authorization header
        // $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');

        return $response;
    });

    // CATEGORIES
    $app->get('/categories', '\App\Modules\Categories\Categories:getAllCategories');
    $app->post('/categories', '\App\Modules\Categories\Categories:postCategories');
    $app->options('/categories', function (Request $request, Response $response): Response {
        return $response;
    });

    $app->get('/categories/{lang}', '\App\Modules\Categories\Categories:getAllCategoriesByLang');
    $app->put('/categories/{id}', '\App\Modules\Categories\Categories:putCategoriesById');
    $app->delete('/categories/{id}', '\App\Modules\Categories\Categories:deleteCategoriesById');
    $app->options('/categories/{lang}', function (Request $request, Response $response): Response {
        return $response;
    });


    // SUBCATEGORIES
    $app->get('/subcategories', '\App\Modules\Subcategories\Subcategories:getAllSubcategories');
    $app->post('/subcategories', '\App\Modules\Subcategories\Subcategories:postSubcategories');
    $app->options('/subcategories', function (Request $request, Response $response): Response {
        return $response;
    });

    $app->get('/subcategories/{lang}', '\App\Modules\Subcategories\Subcategories:getAllSubcategoriesByLang');
    $app->put('/subcategories/{id}', '\App\Modules\Subcategories\Subcategories:putSubcategoriesById');
    $app->delete('/subcategories/{id}', '\App\Modules\Subcategories\Subcategories:deleteSubcategoriesById');
    $app->options('/subcategories/{lang}', function (Request $request, Response $response): Response {
        return $response;
    });

    $app->get('/subcategories-by-category/{id}', '\App\Modules\Subcategories\Subcategories:getAllSubcategoriesByCategoryId');
    $app->options('/subcategories-by-category/{id}', function (Request $request, Response $response): Response {
        return $response;
    });


    // PROPERTIES
    $app->get('/properties', '\App\Modules\Properties\Properties:getAllProperties');
    $app->post('/properties', '\App\Modules\Properties\Properties:postProperties');
    $app->options('/properties', function (Request $request, Response $response): Response {
        return $response;
    });

    $app->get('/properties/{lang}', '\App\Modules\Properties\Properties:getAllPropertiesByLang');
    $app->put('/properties/{id}', '\App\Modules\Properties\Properties:putPropertiesById');
    $app->delete('/properties/{id}', '\App\Modules\Properties\Properties:deletePropertiesById');
    $app->options('/properties/{lang}', function (Request $request, Response $response): Response {
        return $response;
    });


    // DISTRIBUTORS
    $app->get('/distributors', '\App\Modules\Distributors\Distributors:getAllDistributors');
    $app->post('/distributors', '\App\Modules\Distributors\Distributors:postDistributors');
    $app->options('/distributors', function (Request $request, Response $response): Response {
        return $response;
    });

    $app->get('/distributors/{lang}', '\App\Modules\Distributors\Distributors:getAllDistributorsByLang');
    $app->put('/distributors/{id}', '\App\Modules\Distributors\Distributors:putDistributorsById');
    $app->delete('/distributors/{id}', '\App\Modules\Distributors\Distributors:deleteDistributorsById');
    $app->options('/distributors/{lang}', function (Request $request, Response $response): Response {
        return $response;
    });


    // SHOPS
    $app->get('/shops', '\App\Modules\Shops\Shops:getAllShops');
    $app->post('/shops', '\App\Modules\Shops\Shops:postShops');
    $app->options('/shops', function (Request $request, Response $response): Response {
        return $response;
    });

    $app->get('/shops/{lang}', '\App\Modules\Shops\Shops:getAllShopsByLang');
    $app->put('/shops/{id}', '\App\Modules\Shops\Shops:putShopsById');
    $app->delete('/shops/{id}', '\App\Modules\Shops\Shops:deleteShopsById');
    $app->options('/shops/{lang}', function (Request $request, Response $response): Response {
        return $response;
    });


    // NEWS
    $app->get('/news', '\App\Modules\News\News:getAllNews');
    $app->post('/news', '\App\Modules\News\News:postNews');
    $app->options('/news', function (Request $request, Response $response): Response {
        return $response;
    });

    $app->get('/news/{lang}', '\App\Modules\News\News:getAllNewsByLang');
    $app->options('/news/{lang}', function (Request $request, Response $response): Response {
        return $response;
    });

    $app->get('/news-item/{id}', '\App\Modules\News\News:getAllNewsById');
    $app->put('/news-item/{id}', '\App\Modules\News\News:putNewsById');
    $app->delete('/news-item/{id}', '\App\Modules\News\News:deleteNewsById');
    $app->options('/news-item/{id}', function (Request $request, Response $response): Response {
        return $response;
    });



    // PRODUCTS
    $app->get('/products', '\App\Modules\Products\Products:getAllProducts');
    $app->post('/products', '\App\Modules\Products\Products:postProducts');
    $app->options('/products', function (Request $request, Response $response): Response {
        return $response;
    });

    $app->get('/products/{lang}', '\App\Modules\Products\Products:getAllProductsByLang');
    $app->options('/products/{lang}', function (Request $request, Response $response): Response {
        return $response;
    });

    $app->get('/products-item/{id}', '\App\Modules\Products\Products:getProductsById');
    $app->put('/products-item/{id}', '\App\Modules\Products\Products:putProductsById');
    $app->delete('/products-item/{id}', '\App\Modules\Products\Products:deleteProductsById');
    $app->options('/products-item/{id}', function (Request $request, Response $response): Response {
        return $response;
    });




    // $app->get('/languages', '\App\Modules\Languages\Languages:getAllLanguages');

    // $app->post('/languages', '\App\Modules\Languages\Languages:postLanguage');

    // $app->options('/languages', function (Request $request, Response $response): Response {
    //     return $response;
    // });


    // $app->get('/languages/activity', '\App\Modules\Languages\Languages:getAllActivityLanguages');

    // $app->options('/languages/activity', function (Request $request, Response $response): Response {
    //     return $response;
    // });


    // $app->get('/languages/{id}', '\App\Modules\Languages\Languages:getOneLanguage');

    // $app->put('/languages/{id}', '\App\Modules\Languages\Languages:putLanguage');

    // $app->delete('/languages/{id}', '\App\Modules\Languages\Languages:deleteLanguage');

    // $app->options('/languages/{id}', function (Request $request, Response $response): Response {
    //     return $response;
    // });


    // // !!! доделать запрос
    // $app->get('/categories', '\App\Modules\Categories\Categories:getAllCategories');

    // $app->post('/categories', '\App\Modules\Categories\Categories:postCategory');

    // $app->options('/categories', function (Request $request, Response $response): Response {
    //     return $response;
    // });


    // $app->get('/categories/{id}', '\App\Modules\Categories\Categories:getCategoryById');

    // $app->put('/categories/{id}', '\App\Modules\Categories\Categories:putCategoryById');

    // $app->delete('/categories/{id}', '\App\Modules\Categories\Categories:deleteCategoryById');

    // $app->options('/categories/{id}', function (Request $request, Response $response): Response {
    //     return $response;
    // });


    // $app->get('/categories/lang/{id}', '\App\Modules\Categories\Categories:getCategoriesByLang');

    // $app->options('/categories/lang/{id}', function (Request $request, Response $response): Response {
    //     return $response;
    // });


    // $app->get('/categories/lang/{from}/{to}', '\App\Modules\Categories\Categories:getCategoriesByLangs');

    // $app->options('/categories/lang/{from}/{to}', function (Request $request, Response $response): Response {
    //     return $response;
    // });



    // $app->post('/subcategories/category/{id}', '\App\Modules\Subcategories\Subcategories:postSubcategoryByCategory');

    // $app->options('/subcategories/category/{id}', function (Request $request, Response $response): Response {
    //     return $response;
    // });


    // $app->get('/subcategories/lang/{from}/{to}', '\App\Modules\Subcategories\Subcategories:getSubcategoriesByLangs');

    // $app->options('/subcategories/lang/{from}/{to}', function (Request $request, Response $response): Response {
    //     return $response;
    // });


    // $app->get('/subcategories/{id}', '\App\Modules\Subcategories\Subcategories:getSubcategoryById');

    // $app->put('/subcategories/{id}', '\App\Modules\Subcategories\Subcategories:putSubcategoryById');

    // $app->delete('/subcategories/{id}', '\App\Modules\Subcategories\Subcategories:deleteSubcategoryById');

    // $app->options('/subcategories/{id}', function (Request $request, Response $response): Response {
    //     return $response;
    // });





    // $app->post('/product-properties', '\App\Modules\ProductProperties\ProductProperties:postProductProperties');

    // $app->options('/product-properties', function (Request $request, Response $response): Response {
    //     return $response;
    // });


    // $app->get('/product-properties/{id}', '\App\Modules\ProductProperties\ProductProperties:getProductPropertiesById');

    // $app->put('/product-properties/{id}', '\App\Modules\ProductProperties\ProductProperties:putProductPropertiesById');

    // $app->delete('/product-properties/{id}', '\App\Modules\ProductProperties\ProductProperties:deleteProductPropertiesById');

    // $app->options('/product-properties/{id}', function (Request $request, Response $response): Response {
    //     return $response;
    // });


    // $app->get('/product-properties/lang/{from}/{to}', '\App\Modules\ProductProperties\ProductProperties:getProductPropertiesByLangs');

    // $app->options('/product-properties/lang/{from}/{to}', function (Request $request, Response $response): Response {
    //     return $response;
    // });




    // $app->post('/stores', '\App\Modules\Stores\Stores:postStores');

    // $app->options('/stores', function (Request $request, Response $response): Response {
    //     return $response;
    // });


    // $app->get('/stores/{id}', '\App\Modules\Stores\Stores:getStoresById');

    // $app->put('/stores/{id}', '\App\Modules\Stores\Stores:putStoresById');

    // $app->delete('/stores/{id}', '\App\Modules\Stores\Stores:deleteStoresById');

    // $app->options('/stores/{id}', function (Request $request, Response $response): Response {
    //     return $response;
    // });


    // $app->get('/stores/lang/{from}/{to}', '\App\Modules\Stores\Stores:getStoresByLangs');

    // $app->options('/stores/lang/{from}/{to}', function (Request $request, Response $response): Response {
    //     return $response;
    // });



    // $app->get('/products/{id}', '\App\Modules\Products\Products:getProductById');

    // $app->options('/products/{id}', function (Request $request, Response $response): Response {
    //     return $response;
    // });
};
