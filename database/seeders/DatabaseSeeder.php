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
            'after_column_id' => null,
        ]);

        $col2 = Column::create([
            'board_id'        => $board1->id,
            'name'            => 'In process',
            'after_column_id' => $col1->id,
        ]);

        $col3 = Column::create([
            'board_id'        => $board1->id,
            'name'            => 'Done',
            'after_column_id' => $col2->id,
        ]);

        $card1 = Card::create([
            'column_id'     => $col1->id,
            'name'          => 'Настроить окружение',
            'text'          => 'Установить Docker, настроить Sail, создать базу данных.',
            'after_card_id' => null,
        ]);

        $card2 = Card::create([
            'column_id'     => $col1->id,
            'name'          => 'Написать миграции',
            'text'          => 'Создать таблицы boards, columns, cards и остальные.',
            'after_card_id' => $card1->id,
        ]);

        $card3 = Card::create([
            'column_id'     => $col2->id,
            'name'          => 'Реализовать политики доступа',
            'text'          => 'BoardPolicy, ColumnPolicy, CardPolicy, CommentPolicy.',
            'after_card_id' => null,
        ]);

        $card4 = Card::create([
            'column_id'     => $col2->id,
            'name'          => 'Написать контроллеры',
            'text'          => 'BoardController, ColumnController, CardController.',
            'after_card_id' => $card3->id,
        ]);

        $card5 = Card::create([
            'column_id'     => $col3->id,
            'name'          => 'Настроить авторизацию',
            'text'          => 'Laravel Breeze с Inertia + Vue.',
            'after_card_id' => null,
        ]);

        $board2 = Board::create([
            'name'          => 'Личные задачи'
        ]);

        $board2->members()->attach($user->id, ['role' => 'owner']);

        $col4 = Column::create([
            'board_id'        => $board2->id,
            'name'            => 'Сделать',
            'after_column_id' => null,
        ]);

        $col5 = Column::create([
            'board_id'        => $board2->id,
            'name'            => 'Сделано',
            'after_column_id' => $col4->id,
        ]);

        $card6 = Card::create([
            'column_id'     => $col4->id,
            'name'          => 'Купить продукты',
            'text'          => null,
            'after_card_id' => null,
        ]);

        $card7 = Card::create([
            'column_id'     => $col4->id,
            'name'          => 'Записаться к врачу',
            'text'          => null,
            'after_card_id' => $card6->id,
        ]);

        $card8 = Card::create([
            'column_id'     => $col5->id,
            'name'          => 'Оплатить счета',
            'text'          => null,
            'after_card_id' => null,
        ]);
    }
}
