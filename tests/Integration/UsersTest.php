<?php

namespace Notion\Test\Integration;

use Notion\Exceptions\ApiException;
use Notion\Notion;
use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase
{
    public function test_find_current_user(): void
    {
        $token = getenv("NOTION_TOKEN");
        if (!$token) {
            $this->markTestSkipped("Notion token is required to run integration tests.");
        }

        $client = Notion::create($token);

        $user = $client->users()->me();
        $sameUser = $client->users()->find($user->id);

        $this->assertTrue($user->isBot());
        $this->assertEquals($user, $sameUser);
    }

    public function test_find_all_users(): void
    {
        $token = getenv("NOTION_TOKEN");
        if (!$token) {
            $this->markTestSkipped("Notion token is required to run integration tests.");
        }
        $client = Notion::create($token);

        $users = $client->users()->findAll();

        $this->assertCount(2, $users);
        $this->assertTrue($users[0]->isPerson());
        $this->assertTrue($users[1]->isBot());
    }

    public function test_find_inexistent_user(): void
    {
        $token = getenv("NOTION_TOKEN");
        if (!$token) {
            $this->markTestSkipped("Notion token is required to run integration tests.");
        }
        $client = Notion::create($token);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage(
            "Could not find user with ID: 7c3bd31e-63fa-4c60-956d-2264ceb2c522."
        );
        $client->users()->find("7c3bd31e-63fa-4c60-956d-2264ceb2c522");
    }
}
