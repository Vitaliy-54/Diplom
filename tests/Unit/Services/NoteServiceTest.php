<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\Note;
use App\Models\Tag;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoteServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }
        
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test_' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);
        
        Auth::login($this->user);
    }

    protected function tearDown(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
        parent::tearDown();
    }

    /**
     * Создание заметки должно сохранять её в БД с правильными данными.
     */
    public function test_create_note_should_save_note_with_valid_data()
    {
        $note = Note::create([
            'title' => 'Тестовая заметка',
            'description' => '<p>Содержимое тестовой заметки</p>',
            'is_public' => true,
            'user_id' => $this->user->id
        ]);

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => 'Тестовая заметка',
            'is_public' => true,
            'user_id' => $this->user->id
        ]);
    }

    /**
     * Создание заметки с тегами должно привязывать теги к заметке.
     */
    public function test_create_note_should_save_note_with_tags_when_tags_provided()
    {
        // Создаём теги
        $tag1 = Tag::create(['name' => 'Laravel']);
        $tag2 = Tag::create(['name' => 'PHP']);
        $tag3 = Tag::create(['name' => 'Testing']);

        $note = Note::create([
            'title' => 'Заметка с тегами',
            'description' => 'Содержимое заметки с тегами',
            'is_public' => true,
            'user_id' => $this->user->id
        ]);

        $note->tags()->attach([$tag1->id, $tag2->id, $tag3->id]);

        foreach ([$tag1->id, $tag2->id, $tag3->id] as $tagId) {
            $this->assertDatabaseHas('note_tag', [
                'note_id' => $note->id,
                'tag_id' => $tagId
            ]);
        }

        $this->assertEquals(3, $note->tags()->count());
    }

    /**
     * Получение публичных заметок должно возвращать только публичные записи.
     */
    public function test_get_public_notes_should_return_only_public_notes()
    {
        for ($i = 0; $i < 3; $i++) {
            Note::create([
                'title' => 'Public Note ' . $i,
                'description' => 'Description',
                'is_public' => true,
                'user_id' => $this->user->id
            ]);
        }

        for ($i = 0; $i < 2; $i++) {
            Note::create([
                'title' => 'Private Note ' . $i,
                'description' => 'Description',
                'is_public' => false,
                'user_id' => $this->user->id
            ]);
        }

        $publicNotes = Note::where('is_public', true)
            ->where('user_id', $this->user->id)
            ->get();

        $this->assertEquals(3, $publicNotes->count(), 'Должно быть 3 публичных заметки');
        
        foreach ($publicNotes as $note) {
            $this->assertTrue((bool) $note->is_public, 'Заметка должна быть публичной');
        }
    }

    /**
     * Удаление заметки должно удалять и связанные теги.
     */
    public function test_delete_note_should_detach_tags()
    {
        $note = Note::create([
            'title' => 'Note to delete',
            'description' => 'Description',
            'is_public' => true,
            'user_id' => $this->user->id
        ]);
        
        $tags = [];
        for ($i = 0; $i < 3; $i++) {
            $tags[] = Tag::create(['name' => 'Tag ' . $i]);
        }
        
        $note->tags()->attach(array_map(fn($tag) => $tag->id, $tags));

        $this->assertEquals(3, $note->tags()->count());

        $noteId = $note->id;
        $note->delete();
        $this->assertDatabaseMissing('notes', ['id' => $noteId]);
        
        foreach ($tags as $tag) {
            $exists = DB::table('note_tag')
                ->where('note_id', $noteId)
                ->where('tag_id', $tag->id)
                ->exists();
            
            $this->assertFalse($exists, "Связь заметки {$noteId} с тегом {$tag->id} должна быть удалена");
        }
    }
}