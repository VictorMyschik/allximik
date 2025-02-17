<?php

namespace Tests\Feature\Models;


use App\Models\Communicate;
use App\Models\User;
use Tests\TestBase;

class CommunicateTest extends TestBase
{
    public function testCommunicate(): void
    {
        /**
         * 'user_id',
         * 'kind',// тип: телефон, email, url...
         * 'address',
         * 'description'
         */

        $communicate = new Communicate();
        // 'FioID',
        $userID = self::randomIdFromClass(User::class);
        $communicate->setUserID($userID);
        // 'Kind',// Тип: телефон, email, факс...
        $kind = (int)array_rand(Communicate::getKindList());
        $communicate->setKind($kind);
        // 'Address',
        $Address = self::randomString();
        $communicate->setAddress($Address);
        // 'Description'
        $Description = self::randomString();
        $communicate->setDescription($Description);

        $communicateId = $communicate->save_mr();

        /// Asserts
        $communicate = Communicate::loadBy($communicateId);
        self::assertNotNull($communicate);
        self::assertEquals($userID, $communicate->getUser()->id());
        self::assertEquals($kind, $communicate->getKind());
        self::assertEquals($Address, $communicate->getAddress());
        self::assertEquals($Description, $communicate->getDescription());


        /// Update
        // 'FioID',
        $userID = self::randomIdFromClass(User::class);
        $communicate->setUserID($userID);
        // 'Kind',// Тип: телефон, email, факс...
        $kind = (int)array_rand(Communicate::getKindList());
        $communicate->setKind($kind);
        // 'Address',
        $Address = self::randomString();
        $communicate->setAddress($Address);
        // 'Description'
        $Description = self::randomString();
        $communicate->setDescription($Description);

        $communicateId = $communicate->save_mr();

        /// Asserts
        $communicate = Communicate::loadBy($communicateId);
        self::assertNotNull($communicate);
        self::assertEquals($userID, $communicate->getUser()->id());
        self::assertEquals($kind, $communicate->getKind());
        self::assertEquals($Address, $communicate->getAddress());
        self::assertEquals($Description, $communicate->getDescription());


        /// Set null
        $communicate->setDescription(null);
        $communicateId = $communicate->save_mr();

        /// Asserts
        $communicate = Communicate::loadBy($communicateId);
        self::assertNotNull($communicate);
        self::assertNull($communicate->getDescription());


        /// Delete
        $communicate->delete_mr();
        $communicate = Communicate::loadBy($communicateId);
        self::assertNull($communicate);
    }
}
