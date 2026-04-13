<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\Card;
use App\Models\Column;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $board1 = Board::create([
            'name'          => 'Разработка проекта'
        ]);

        $board1->members()->attach($user->id, ['role' => 'owner']);

        $col1 = Column::create([
            'board_id'        => $board1->id,
            'name'            => 'To do',
        ]);

        $col2 = Column::create([
            'board_id'        => $board1->id,
            'name'            => 'In process',
        ]);

        $col3 = Column::create([
            'board_id'        => $board1->id,
            'name'            => 'Done',
        ]);

        $card1 = Card::create([
            'column_id'     => $col1->id,
            'name'          => 'Настроить окружение',
            'text'          => 'Установить Docker, настроить Sail, создать базу данных.',
        ]);

        $card2 = Card::create([
            'column_id'     => $col1->id,
            'name'          => 'Написать миграции',
            'text'          => 'Создать таблицы boards, columns, cards и остальные.',
        ]);

        $card3 = Card::create([
            'column_id'     => $col2->id,
            'name'          => 'Реализовать политики доступа',
            'text'          => 'BoardPolicy, ColumnPolicy, CardPolicy, CommentPolicy.',
        ]);

        $card4 = Card::create([
            'column_id'     => $col2->id,
            'name'          => 'Написать контроллеры',
            'text'          => 'BoardController, ColumnController, CardController.',
        ]);

        $card5 = Card::create([
            'column_id'     => $col3->id,
            'name'          => 'Настроить авторизацию',
            'text'          => 'Laravel Breeze с Inertia + Vue.',
        ]);

        $board2 = Board::create([
            'name'          => 'Личные задачи'
        ]);

        $board2->members()->attach($user->id, ['role' => 'owner']);

        $col4 = Column::create([
            'board_id'        => $board2->id,
            'name'            => 'Сделать',
        ]);

        $col5 = Column::create([
            'board_id'        => $board2->id,
            'name'            => 'Сделано',
        ]);

        $card6 = Card::create([
            'column_id'     => $col4->id,
            'name'          => 'Купить продукты',
            'text'          => null,
        ]);

        $card7 = Card::create([
            'column_id'     => $col4->id,
            'name'          => 'Записаться к врачу',
            'text'          => null,
        ]);

        $card8 = Card::create([
            'column_id'     => $col5->id,
            'name'          => 'Оплатить счета',
            'text'          => null,
        ]);
    }
}
