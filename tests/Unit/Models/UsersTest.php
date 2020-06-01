<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsersTest extends TestCase
{
    use DatabaseTransactions;

    public User $user;

    protected function setUp() : void
    {
        parent::setUp();
        $this->user = new User([
            'name' => 'bluesoul',
            'email' => 'ipunchbears@bluesoul.me',
            'password' => bcrypt('password1')
        ]);
        $this->user->save();
    }

    /** @test */
    public function a_user_can_be_created()
    {
        $this->assertEquals('bluesoul', $this->user->name);
    }

    /** @test */
    public function a_user_can_add_a_goal()
    {
        $new_goal = $this->user->addGoal([
            'title' => 'Test goal.',
            'start_date' => '2020-06-01',
            'due_date' => '2020-06-30',
            'target' => 50
        ]);
        $this->assertEquals(1, $this->user->goals->count());

        $this->assertEquals(0, $new_goal->progress);
        $this->assertEquals('', $new_goal->description);
        $this->assertEquals('2020-06-30', $new_goal->due_date->toDateString());
    }

    /** @test */
    public function a_user_should_not_be_able_to_set_an_invalid_date_for_a_goal()
    {
        $this->expectException("RangeException");

        $new_goal = $this->user->addGoal([
            'title' => '30 Pushups for 30 days of February(?!)',
            'start_date' => '2020-02-30',
            'target' => 50
        ]);

        $this->assertEquals(1, $this->user->goals->count());
    }
}
