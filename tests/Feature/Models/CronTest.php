<?php

namespace Models;

use App\Models\Cron;
use Tests\TestBase;

class CronTest extends TestBase
{
  public function testCron(): void
  {
    /**
     * 'name'
     * 'active'
     * 'last_work'
     * 'description'
     * 'cron_key'
     * 'created_at'
     * 'updated_at'
     */
    $cron = new Cron();
    $name = self::randomString(50);
    $cron->setName($name);
    $period = rand(0, 100);
    $cron->setPeriod($period);
    $cron->setActive(true);
    $lastWork = now();
    $cron->setLastWork($lastWork);
    $description = self::randomString();
    $cron->setDescription($description);
    $cronKey = self::randomString();
    $cron->setCronKey($cronKey);

    $cronID = $cron->save_mr();

    // Asserts
    $cron = Cron::loadBy($cronID);
    self::assertNotNull($cron);
    self::assertEquals($period, $cron->getPeriod());
    self::assertTrue($cron->isActive());
    self::assertEquals($name, $cron->getName());
    self::assertEquals($lastWork, $cron->getLastWork());
    self::assertEquals($description, $cron->getDescription());
    self::assertEquals($cronKey, $cron->getCronKey());

    // Update
    $name = self::randomString(50);
    $cron->setName($name);
    $period = rand(0, 100);
    $cron->setPeriod($period);
    $cron->setActive(false);
    $lastWork = now();
    $cron->setLastWork($lastWork);
    $description = self::randomString();
    $cron->setDescription($description);
    $cronKey = self::randomString();
    $cron->setCronKey($cronKey);

    $cronID = $cron->save_mr();

    // Asserts
    $cron = Cron::loadBy($cronID);
    self::assertNotNull($cron);
    self::assertEquals($period, $cron->getPeriod());
    self::assertFalse($cron->isActive());
    self::assertEquals($name, $cron->getName());
    self::assertEquals($lastWork, $cron->getLastWork());
    self::assertEquals($description, $cron->getDescription());
    self::assertEquals($cronKey, $cron->getCronKey());

    // Set null
    $cron->setDescription(null);

    $cronID = $cron->save_mr();

    // Asserts
    $cron = Cron::loadBy($cronID);
    self::assertNotNull($cron);
    self::assertNull($cron->getDescription());

    // Delete
    $cron->delete_mr();

    $cron = Cron::loadBy($cronID);
    self::assertNull($cron);
  }
}
