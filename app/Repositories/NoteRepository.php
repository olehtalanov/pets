<?php

namespace App\Repositories;

use App\Data\Animal\NoteData;
use App\Models\Animal;
use App\Models\Note;
use Auth;
use Illuminate\Support\Collection;

class NoteRepository extends BaseRepository
{
    public function list(): Collection
    {
        return Auth::user()
            ->notes()
            ->select([
                'notes.*',
                'animal_name' => Animal::query()
                    ->whereColumn('user_id', 'notes.user_id')
                    ->select('name'),
            ])
            ->with('categories:id,uuid,name')
            ->latest()
            ->get();
    }

    public function one(Note $note): Note
    {
        return $note->load([
            'animal',
            'categories:id,uuid,name',
        ]);
    }

    public function store(NoteData $data): Note
    {
        /** @var Note $note */
        $note = Auth::user()
            ->notes()
            ->create($data->except('category_ids')->toArray());

        if ($data->category_ids) {
            $note->categories()->attach($data->category_ids);
        }

        return $this->one($note);
    }

    public function update(Note $note, NoteData $data): Note
    {
        tap($note)->update($data->except('category_ids')->toArray());

        if ($data->category_ids) {
            $note->categories()->sync(
                array_values($data->only('category_ids')->toArray())
            );
        }

        return $this->one($note);
    }

    public function destroy(Note $note): void
    {
        $note->delete();
    }
}
