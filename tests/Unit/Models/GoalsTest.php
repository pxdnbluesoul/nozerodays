<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\User;
use App\Goal;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GoalsTest extends TestCase
{
    use DatabaseTransactions;

    public User $user;
    public Goal $goal;

    protected function setUp() : void
    {
        parent::setUp();
        $this->user = new User([
            'name' => 'bluesoul',
            'email' => 'ipunchbears@bluesoul.me',
            'password' => bcrypt('password1')
        ]);
        $this->user->save();

        $this->goal = $this->user->addGoal([
            'title' => 'Do 5 things.',
            'start_date' => '2020-06-01',
            'due_date' => '2020-06-10',
            'target' => 5
        ]);
    }

    /** @test */
    public function a_goal_can_be_progressed()
    {
        $this->goal->addProgress(2);
        $this->assertEquals(2, $this->goal->progress);
    }

    /** @test */
    public function a_goal_can_be_completed_incrementally()
    {
        $this->goal->addProgress(2);
        $this->assertFalse($this->goal->isComplete());

        $this->goal->addProgress(1);
        $this->goal->addProgress(2);
        $this->assertTrue($this->goal->isComplete());
    }

    /** @test */
    public function a_goal_can_be_completed_at_once()
    {
        $this->goal->addProgress(2);
        $this->assertFalse($this->goal->isComplete());

        $this->goal->complete();
        $this->assertTrue($this->goal->isComplete());
    }

    /** @test */
    public function a_goal_can_have_its_progress_adjusted()
    {
        $this->goal->addProgress(2);
        $this->assertEquals(2, $this->goal->progress);

        $this->goal->addProgress(2);
        $this->goal->setProgress(3);
        $this->assertEquals(3, $this->goal->progress);
    }

    /** @test */
    public function a_goal_can_have_its_target_adjusted()
    {
        $this->goal->addProgress(1);
        $this->assertEquals("1", $this->goal->showProgress());
        $this->assertEquals("5", $this->goal->showTarget());
        $this->assertEquals("20%", $this->goal->showProgressPct());

        $this->goal->setTarget(10);
        $this->assertEquals("10", $this->goal->showTarget());
        $this->assertEquals("10%", $this->goal->showProgressPct());
    }
}
