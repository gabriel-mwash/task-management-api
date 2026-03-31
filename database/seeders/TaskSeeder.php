<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      Task::create([
        'title'=>'Finish reviewing documents',
        'due_date'=>'2026-06-21',
        'priority'=>'high',
        'status'=>'done',
      ]);

      Task::create([
        'title'=>'prepare the business presentation',
        'due_date'=>'2026-05-20',
        'priority'=>'medium',
        'status'=>'in_progress',
      ]);

      Task::create([
        'title'=>'Deploy the website before October',
        'due_date'=>'2026-04-20',
        'priority'=>'high',
        'status'=>'pending',
      ]);

      Task::create([
        'title'=>'write a report on the event',
        'due_date'=>'2026-07-20',
        'priority'=>'medium',
        'status'=>'done',
      ]);

      Task::create([
        'title'=>'shopping for grandma and grandpa',
        'due_date'=>'2026-05-01',
        'priority'=>'low',
        'status'=>'pending',
      ]);
    }
}
