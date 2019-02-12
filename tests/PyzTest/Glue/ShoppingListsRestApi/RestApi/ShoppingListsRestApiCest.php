<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\ShoppingListsRestApi\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group ShoppingListsRestApi
 * @group RestApi
 * @group ShoppingListsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ShoppingListsRestApiCest
{
    /**
     * @var \PyzTest\Glue\ShoppingListsRestApi\RestApi\ShoppingListsApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function _authorize(ShoppingListsApiTester $I): void
    {
        $I->amAuthorizedGlueCompanyUser(
            $this->fixtures->getGlueToken(),
            $this->fixtures->getCustomer()
        );
    }

    /**
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(ShoppingListsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(ShoppingListsApiFixtures::class);
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function requestShoppingLists(ShoppingListsApiTester $I): void
    {
        $I->sendGET('shopping-lists');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('first shopping list type and id are correct')->whenI()->seeResponseJsonPathContains([
            'type' => 'shopping-lists',
            'id' => $this->fixtures->getFirstShoppingList()->getUuid(),
        ], '$.data[0]');

        $I->amSure('first shopping list attributes are correct')->whenI()->seeResponseJsonPathContains([
            'name' => $this->fixtures->getFirstShoppingList()->getName(),
            'numberOfItems' => 0,
            'owner' => $this->fixtures->getCustomer()->getFirstName() . ' ' . $this->fixtures->getCustomer()->getLastName(),
        ], '$.data[0].attributes');

        $I->amSure('second shopping list links are correct')->whenI()->seeResponseJsonPathContains([
            'self' => $I->formatFullUrl('shopping-lists/{idShoppingList}', [
                'idShoppingList' => $this->fixtures->getFirstShoppingList()->getUuid(),
            ]),
        ], '$.data[0].links');

        $I->amSure('second shopping list type and id are correct')->whenI()->seeResponseJsonPathContains([
            'type' => 'shopping-lists',
            'id' => $this->fixtures->getSecondShoppingList()->getUuid(),
        ], '$.data[1]');

        $I->amSure('second shopping list attributes are correct')->whenI()->seeResponseJsonPathContains([
            'name' => $this->fixtures->getSecondShoppingList()->getName(),
            'numberOfItems' => 3,
            'owner' => $this->fixtures->getCustomer()->getFirstName() . ' ' . $this->fixtures->getCustomer()->getLastName(),
        ], '$.data[1].attributes');

        $I->amSure('second shopping list links are correct')->whenI()->seeResponseJsonPathContains([
            'self' => $I->formatFullUrl('shopping-lists/{idShoppingList}', [
                'idShoppingList' => $this->fixtures->getSecondShoppingList()->getUuid(),
            ]),
        ], '$.data[1].links');

        $I->amSure('response has self link')->whenI()->seeResponseJsonPathContains([
            'self' => $I->formatFullUrl('shopping-lists'),
        ], '$.links');
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function requestShoppingListsToReceiveEmptyList(ShoppingListsApiTester $I): void
    {
        $I->sendGET('shopping-lists');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('list of shopping lists is empty')->whenI()->seeResponseJsonPathContains([
            'data' => [],
        ], '$');

        $I->amSure('response has self link')->whenI()->seeResponseJsonPathContains([
            'self' => $I->formatFullUrl('shopping-lists'),
        ], '$.links');
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function requestShoppingListsNotAuthorized(ShoppingListsApiTester $I): void
    {
        $I->haveHttpHeader('Authorization', '');
        $I->sendGET('shopping-lists');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function requestShoppingListsWithoutCompanyUserHeader(ShoppingListsApiTester $I): void
    {
        $I->haveHttpHeader('X-Company-User-Id', '');
        $I->sendGET('shopping-lists');
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        // TODO define 4xx response
        //$I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function requestShoppingListsWithWrongCompanyUser(ShoppingListsApiTester $I): void
    {
        $I->haveHttpHeader('X-Company-User-Id', 'non-existing-company-user');
        $I->sendGET('shopping-lists');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function requestShoppingListByUuid(ShoppingListsApiTester $I): void
    {
        $I->sendGET($I->formatUrl('shopping-lists/{shoppingListId}', [
            'shoppingListId' => $this->fixtures->getSecondShoppingList()->getUuid(),
        ]), [
            'include' => 'shopping-list-items,concrete-products',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('shopping list type and id are correct')->whenI()->seeResponseJsonPathContains([
            'type' => 'shopping-lists',
            'id' => $this->fixtures->getSecondShoppingList()->getUuid(),
        ], '$.data');

        $I->amSure('shopping list attributes are correct')->whenI()->seeResponseJsonPathContains([
            'name' => $this->fixtures->getSecondShoppingList()->getName(),
            'numberOfItems' => 3,
            'owner' => $this->fixtures->getCustomer()->getFirstName() . ' ' . $this->fixtures->getCustomer()->getLastName(),
        ], '$.data.attributes');

        $I->amSure('shopping list self link are correct')->whenI()->seeResponseJsonPathContains([
            'self' => $I->formatFullUrl('shopping-lists/{idShoppingList}', [
                'idShoppingList' => $this->fixtures->getSecondShoppingList()->getUuid(),
            ]),
        ], '$.data.links');

        $I->amSure('shopping list relations are correct')->whenI()->seeResponseJsonPathContains([
            'type' => 'shopping-list-items',
        ], '$.data.relationships.shopping-list-items.data[*]');

        $I->amSure('included contains first item')->whenI()->seeResponseJsonPathContains(
            [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'sku' => $this->fixtures->getFirstItemInSecondShoppingList()->getSku(),
                    'quantity' => $this->fixtures->getFirstItemInSecondShoppingList()->getQuantity(),
                ],
                'links' => [
                    'self' => $I->formatFullUrl(
                        'shopping-lists/{idShoppingList}/shopping-list-items/{idShoppingListItem}',
                        [
                            'idShoppingList' => $this->fixtures->getSecondShoppingList()->getUuid(),
                            'idShoppingListItem' => $this->fixtures->getFirstItemInSecondShoppingList()->getUuid(),
                        ]
                    ),
                ],
            ],
            sprintf(
                '$.included[?(@.id == %s and @.type == %s)]',
                json_encode($this->fixtures->getFirstItemInSecondShoppingList()->getUuid()),
                json_encode('shopping-list-items')
            )
        );

        $I->amSure('included contains second item')->whenI()->seeResponseJsonPathContains(
            [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'sku' => $this->fixtures->getSecondItemInSecondShoppingList()->getSku(),
                    'quantity' => $this->fixtures->getSecondItemInSecondShoppingList()->getQuantity(),
                ],
                'links' => [
                    'self' => $I->formatFullUrl(
                        'shopping-lists/{idShoppingList}/shopping-list-items/{idShoppingListItem}',
                        [
                            'idShoppingList' => $this->fixtures->getSecondShoppingList()->getUuid(),
                            'idShoppingListItem' => $this->fixtures->getSecondItemInSecondShoppingList()->getUuid(),
                        ]
                    ),
                ],
            ],
            sprintf(
                '$.included[?(@.id == %s and @.type == %s)]',
                json_encode($this->fixtures->getSecondItemInSecondShoppingList()->getUuid()),
                json_encode('shopping-list-items')
            )
        );

        $I->amSure('included contains concrete product for first item')->whenI()->seeResponseJsonPathContains(
            [
                'type' => 'concrete-products',
                'attributes' => [
                    'sku' => $this->fixtures->getFirstItemInSecondShoppingList()->getSku(),
                ],
            ],
            sprintf(
                '$.included[?(@.id == %s and @.type == %s)]',
                json_encode($this->fixtures->getFirstItemInSecondShoppingList()->getSku()),
                json_encode('concrete-products')
            )
        );

        $I->amSure('included contains concrete product for second item')->whenI()->seeResponseJsonPathContains(
            [
                'type' => 'concrete-products',
                'attributes' => [
                    'sku' => $this->fixtures->getSecondItemInSecondShoppingList()->getSku(),
                ],
            ],
            sprintf(
                '$.included[?(@.id == %s and @.type == %s)]',
                json_encode($this->fixtures->getSecondItemInSecondShoppingList()->getSku()),
                json_encode('concrete-products')
            )
        );
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function requestShoppingListByUuidThatDoesNotExist(ShoppingListsApiTester $I): void
    {
        $I->sendGET($I->formatUrl('shopping-lists/{shoppingListId}', ['shoppingListId' => 'unknown-shopping-list']));
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function requestShoppingListByUuidNotAuthorized(ShoppingListsApiTester $I): void
    {
        $I->haveHttpHeader('Authorization', '');
        $I->sendGET($I->formatUrl('shopping-lists/{shoppingListId}', [
            'shoppingListId' => $this->fixtures->getFirstShoppingList()->getUuid(),
        ]));
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function requestShoppingListByUuidWithoutCompanyUserHeader(ShoppingListsApiTester $I): void
    {
        $I->haveHttpHeader('X-Company-User-Id', '');
        $I->sendGET($I->formatUrl('shopping-lists/{shoppingListId}', [
            'shoppingListId' => $this->fixtures->getFirstShoppingList()->getUuid(),
        ]));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        // TODO define 4xx response
        //$I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function requestShoppingListByUuidWithWrongCompanyUser(ShoppingListsApiTester $I): void
    {
        $I->haveHttpHeader('X-Company-User-Id', 'non-existing-company-user');
        $I->sendGET($I->formatUrl('shopping-lists/{shoppingListId}', [
            'shoppingListId' => $this->fixtures->getFirstShoppingList()->getUuid(),
        ]));
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function createShoppingList(ShoppingListsApiTester $I): void
    {
        $shoppingListName = 'List 4';

        $I->sendPOST('shopping-lists', [
            'data' => [
                'type' => 'shopping-lists',
                'attributes' => [
                    'name' => $shoppingListName,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('shopping list type and id are correct')->whenI()->seeResponseJsonPathContains([
            'type' => 'shopping-lists',
        ], '$.data');

        $I->amSure('shopping list attributes are correct')->whenI()->seeResponseJsonPathContains([
            'name' => $shoppingListName,
            'numberOfItems' => 0,
            'owner' => $this->fixtures->getCustomer()->getFirstName() . ' ' . $this->fixtures->getCustomer()->getLastName(),
        ], '$.data.attributes');
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function createShoppingListWithoutName(ShoppingListsApiTester $I): void
    {
        $I->sendPOST('shopping-lists', [
            'data' => [
                'type' => 'shopping-lists',
                'attributes' => [
                    'name' => '',
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function createShoppingListWithLongName(ShoppingListsApiTester $I): void
    {
        $I->sendPOST('shopping-lists', [
            'data' => [
                'type' => 'shopping-lists',
                'attributes' => [
                    'name' => str_repeat('a', 256),
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function createShoppingListWithNameThatAlreadyExists(ShoppingListsApiTester $I): void
    {
        $I->sendPOST('shopping-lists', [
            'data' => [
                'type' => 'shopping-lists',
                'attributes' => [
                    'name' => $this->fixtures->getFirstShoppingList()->getName(),
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function createShoppingListNotAuthorized(ShoppingListsApiTester $I): void
    {
        $I->haveHttpHeader('Authorization', '');
        $I->sendPOST('shopping-lists', [
            'data' => [
                'type' => 'shopping-lists',
                'attributes' => [
                    'name' => 'List 5',
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function updateShoppingList(ShoppingListsApiTester $I): void
    {
        $newShoppingListName = 'List 3 renamed';

        $I->sendPATCH($I->formatUrl('shopping-lists/{idShoppingList}', [
            'idShoppingList' => $this->fixtures->getThirdShoppingList()->getUuid(),
        ]), [
            'data' => [
                'type' => 'shopping-lists',
                'attributes' => [
                    'name' => $newShoppingListName,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('shopping list type and id are correct')->whenI()->seeResponseJsonPathContains([
            'type' => 'shopping-lists',
        ], '$.data');

        $I->amSure('shopping list attributes are correct')->whenI()->seeResponseJsonPathContains([
            'name' => $newShoppingListName,
            'numberOfItems' => 0,
            'owner' => $this->fixtures->getCustomer()->getFirstName() . ' ' . $this->fixtures->getCustomer()->getLastName(),
        ], '$.data.attributes');

        /*
        $I->amSure('included does not contain items')->whenI()->dontSeeResponseMatchesJsonPath(
            sprintf('$.included[?(@.type == %s)]', json_encode('shopping-list-items'))
        );
        */
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function updateShoppingListThatDoesNotExists(ShoppingListsApiTester $I): void
    {
        $I->sendPATCH($I->formatUrl('shopping-lists/{idShoppingList}', [
            'idShoppingList' => 'unknown-shopping-list',
        ]), [
            'data' => [
                'type' => 'shopping-lists',
                'attributes' => [
                    'name' => 'New name',
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function updateShoppingListWithoutName(ShoppingListsApiTester $I): void
    {
        $I->sendPATCH($I->formatUrl('shopping-lists/{idShoppingList}', [
            'idShoppingList' => $this->fixtures->getFirstShoppingList()->getUuid(),
        ]), [
            'data' => [
                'type' => 'shopping-lists',
                'attributes' => [
                    'name' => '',
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function updateShoppingListWithNameThatAlreadyExists(ShoppingListsApiTester $I): void
    {
        $I->sendPATCH($I->formatUrl('shopping-lists/{idShoppingList}', [
            'idShoppingList' => $this->fixtures->getFirstShoppingList()->getUuid(),
        ]), [
            'data' => [
                'type' => 'shopping-lists',
                'attributes' => [
                    'name' => $this->fixtures->getSecondShoppingList()->getName(),
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function updateShoppingListWithoutUuid(ShoppingListsApiTester $I): void
    {
        $I->sendPATCH('shopping-lists', [
            'data' => [
                'type' => 'shopping-lists',
                'attributes' => [
                    'name' => 'List 6',
                ],
            ],
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function updateShoppingListNotAuthorized(ShoppingListsApiTester $I): void
    {
        $I->haveHttpHeader('Authorization', '');
        $I->sendPATCH($I->formatUrl('shopping-lists/{idShoppingList}', [
            'idShoppingList' => $this->fixtures->getFirstShoppingList()->getUuid(),
        ]), [
            'data' => [
                'type' => 'shopping-lists',
                'attributes' => [
                    'name' => 'List 7',
                ],
            ],
        ]);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function deleteShoppingList(ShoppingListsApiTester $I): void
    {
        $I->sendDELETE($I->formatUrl('shopping-lists/{idShoppingList}', [
            'idShoppingList' => $this->fixtures->getThirdShoppingList()->getUuid(),
        ]));

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function deleteShoppingListThatDoesNotExists(ShoppingListsApiTester $I): void
    {
        $I->sendDELETE($I->formatUrl('shopping-lists/{idShoppingList}', [
            'idShoppingList' => 'unknown-shopping-list',
        ]));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function deleteShoppingListWithoutUuid(ShoppingListsApiTester $I): void
    {
        $I->sendDELETE('shopping-lists');
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function deleteShoppingListNotAuthorized(ShoppingListsApiTester $I): void
    {
        $I->haveHttpHeader('Authorization', '');
        $I->sendDELETE($I->formatUrl('shopping-lists/{idShoppingList}', [
            'idShoppingList' => $this->fixtures->getFirstShoppingList()->getUuid(),
        ]));
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function addShoppingListItemToShoppingList(ShoppingListsApiTester $I): void
    {
        $sku = $this->fixtures->getFirstProduct()->getSku();

        $I->sendPOST($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items/?include={include}', [
            'idShoppingList' => $this->fixtures->getFirstShoppingList()->getUuid(),
            'include' => 'concrete-products',
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'sku' => $sku,
                    'quantity' => 2,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('shopping list item type and id are correct')->whenI()->seeResponseJsonPathContains([
            'type' => 'shopping-list-items',
        ], '$.data');

        $I->amSure('shopping list attributes are correct')->whenI()->seeResponseJsonPathContains([
            'sku' => $sku,
            'quantity' => 2,
        ], '$.data.attributes');

        $I->amSure('included contains concrete product for item')->whenI()->seeResponseJsonPathContains(
            [
                'type' => 'concrete-products',
                'attributes' => [
                    'sku' => $sku,
                ],
            ],
            sprintf('$.included[?(@.id == %s and @.type == %s)]', json_encode($sku), json_encode('concrete-products'))
        );
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function addShoppingListItemToReadOnlyShoppingList(ShoppingListsApiTester $I): void
    {
        $sku = $this->fixtures->getFirstProduct()->getSku();

        $I->sendPOST($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items', [
            'idShoppingList' => $this->fixtures->getSharedShoppingList()->getUuid(),
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'sku' => $sku,
                    'quantity' => 2,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function addShoppingListItemToShoppingListWithNotAvailableProduct(ShoppingListsApiTester $I): void
    {
        $sku = $this->fixtures->getNotAvailableProduct()->getSku();

        $I->sendPOST($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items', [
            'idShoppingList' => $this->fixtures->getFirstShoppingList()->getUuid(),
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'sku' => $sku,
                    'quantity' => 2,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function addShoppingListItemToShoppingListWithDeactivatedProduct(ShoppingListsApiTester $I): void
    {
        $sku = $this->fixtures->getNotActiveProduct()->getSku();

        $I->sendPOST($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items', [
            'idShoppingList' => $this->fixtures->getFirstShoppingList()->getUuid(),
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'sku' => $sku,
                    'quantity' => 2,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function addShoppingListItemToShoppingListWithWrongSku(ShoppingListsApiTester $I): void
    {
        $sku = 'WRONG-SKU';

        $I->sendPOST($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items', [
            'idShoppingList' => $this->fixtures->getFirstShoppingList()->getUuid(),
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'sku' => $sku,
                    'quantity' => 2,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function addShoppingListItemToShoppingListWithZeroQuantity(ShoppingListsApiTester $I): void
    {
        $sku = $this->fixtures->getSecondProduct()->getSku();

        $I->sendPOST($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items', [
            'idShoppingList' => $this->fixtures->getFirstShoppingList()->getUuid(),
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'sku' => $sku,
                    'quantity' => 0,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function addShoppingListItemToShoppingListWithBigQuantity(ShoppingListsApiTester $I): void
    {
        $sku = $this->fixtures->getSecondProduct()->getSku();

        $I->sendPOST($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items', [
            'idShoppingList' => $this->fixtures->getFirstShoppingList()->getUuid(),
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'sku' => $sku,
                    'quantity' => 2147483650,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function addShoppingListItemToShoppingListThatDoesNotExists(ShoppingListsApiTester $I): void
    {
        $sku = $this->fixtures->getSecondProduct()->getSku();

        $I->sendPOST($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items', [
            'idShoppingList' => 'unknown-shopping-list',
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'sku' => $sku,
                    'quantity' => 2,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function addShoppingListItemToShoppingListWithoutUuid(ShoppingListsApiTester $I): void
    {
        $sku = $this->fixtures->getSecondProduct()->getSku();

        $I->sendPOST($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items', [
            'idShoppingList' => '',
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'sku' => $sku,
                    'quantity' => 2,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function addShoppingListItemToShoppingListNotAuthorized(ShoppingListsApiTester $I): void
    {
        $sku = $this->fixtures->getSecondProduct()->getSku();

        $I->haveHttpHeader('Authorization', '');
        $I->sendPOST($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items', [
            'idShoppingList' => $this->fixtures->getFirstShoppingList()->getUuid(),
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'sku' => $sku,
                    'quantity' => 2,
                ],
            ],
        ]);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function addShoppingListItemToShoppingListWithoutCompanyUserHeader(ShoppingListsApiTester $I): void
    {
        $sku = $this->fixtures->getSecondProduct()->getSku();

        $I->haveHttpHeader('X-Company-User-Id', '');
        $I->sendPOST($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items', [
            'idShoppingList' => $this->fixtures->getFirstShoppingList()->getUuid(),
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'sku' => $sku,
                    'quantity' => 2,
                ],
            ],
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function updateShoppingListItem(ShoppingListsApiTester $I): void
    {
        $sku = $this->fixtures->getFirstProduct()->getSku();

        $I->sendPATCH($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items/{idShoppingListItem}', [
            'idShoppingList' => $this->fixtures->getSecondShoppingList()->getUuid(),
            'idShoppingListItem' => $this->fixtures->getFirstItemInSecondShoppingList()->getUuid(),
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'sku' => $this->fixtures->getSecondProduct()->getSku(), // Make sure SKU is not changed
                    'quantity' => 5,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('shopping list item type and id are correct')->whenI()->seeResponseJsonPathContains([
            'type' => 'shopping-list-items',
        ], '$.data');

        $I->amSure('shopping list attributes are correct')->whenI()->seeResponseJsonPathContains([
            'sku' => $sku,
            'quantity' => 5,
        ], '$.data.attributes');

        $I->amSure('included contains concrete product for item')->whenI()->seeResponseJsonPathContains(
            [
                'type' => 'concrete-products',
                'attributes' => [
                    'sku' => $sku,
                ],
            ],
            sprintf('$.included[?(@.id == %s and @.type == %s)]', json_encode($sku), json_encode('concrete-products'))
        );
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function updateShoppingListItemWithZeroQuantity(ShoppingListsApiTester $I): void
    {
        $I->sendPATCH($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items/{idShoppingListItem}', [
            'idShoppingList' => $this->fixtures->getSecondShoppingList()->getUuid(),
            'idShoppingListItem' => $this->fixtures->getFirstItemInSecondShoppingList()->getUuid(),
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'quantity' => 0,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function updateShoppingListItemInShoppingListThatDoesNotExists(ShoppingListsApiTester $I): void
    {
        $I->sendPATCH($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items/{idShoppingListItem}', [
            'idShoppingList' => 'unknown-shopping-list',
            'idShoppingListItem' => $this->fixtures->getFirstItemInSecondShoppingList()->getUuid(),
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'quantity' => 2,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function updateShoppingListItemThatDoesNotExists(ShoppingListsApiTester $I): void
    {
        $I->sendPATCH($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items/{idShoppingListItem}', [
            'idShoppingList' => $this->fixtures->getSecondShoppingList()->getUuid(),
            'idShoppingListItem' => 'unknown-shopping-list-item',
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'quantity' => 5,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function updateShoppingListItemWithoutUuid(ShoppingListsApiTester $I): void
    {
        $I->sendPATCH($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items/{idShoppingListItem}', [
            'idShoppingList' => $this->fixtures->getSecondShoppingList()->getUuid(),
            'idShoppingListItem' => '',
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'quantity' => 2,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function updateShoppingListItemToShoppingListNotAuthorized(ShoppingListsApiTester $I): void
    {
        $I->haveHttpHeader('Authorization', '');
        $I->sendPATCH($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items/{idShoppingListItem}', [
            'idShoppingList' => $this->fixtures->getSecondShoppingList()->getUuid(),
            'idShoppingListItem' => $this->fixtures->getFirstItemInSecondShoppingList()->getUuid(),
        ]), [
            'data' => [
                'type' => 'shopping-list-items',
                'attributes' => [
                    'quantity' => 2,
                ],
            ],
        ]);

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function deleteShoppingListItem(ShoppingListsApiTester $I): void
    {
        $I->sendDELETE($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items/{idShoppingListItem}', [
            'idShoppingList' => $this->fixtures->getSecondShoppingList()->getUuid(),
            'idShoppingListItem' => $this->fixtures->getSecondItemInSecondShoppingList()->getUuid(),
        ]));

        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function deleteShoppingListItemInShoppingListThatDoesNotExists(ShoppingListsApiTester $I): void
    {
        $I->sendDELETE($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items/{idShoppingListItem}', [
            'idShoppingList' => 'unknown-shopping-list',
            'idShoppingListItem' => $this->fixtures->getFirstItemInSecondShoppingList()->getUuid(),
        ]));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function deleteShoppingListItemThatDoesNotExists(ShoppingListsApiTester $I): void
    {
        $I->sendDELETE($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items/{idShoppingListItem}', [
            'idShoppingList' => $this->fixtures->getSecondShoppingList()->getUuid(),
            'idShoppingListItem' => 'unknown-shopping-list-item',
        ]));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function deleteShoppingListItemWithoutUuid(ShoppingListsApiTester $I): void
    {
        $I->sendDELETE($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items/{idShoppingListItem}', [
            'idShoppingList' => $this->fixtures->getSecondShoppingList()->getUuid(),
            'idShoppingListItem' => '',
        ]));

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    public function deleteShoppingListItemToShoppingListNotAuthorized(ShoppingListsApiTester $I): void
    {
        $I->haveHttpHeader('Authorization', '');
        $I->sendDELETE($I->formatUrl('shopping-lists/{idShoppingList}/shopping-list-items/{idShoppingListItem}', [
            'idShoppingList' => $this->fixtures->getSecondShoppingList()->getUuid(),
            'idShoppingListItem' => $this->fixtures->getFirstItemInSecondShoppingList()->getUuid(),
        ]));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }
}
