<?php

namespace App\Http\Controllers;

use App\Models\Note;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\Image;
use App\Models\Todo;
use App\Models\Label;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{
    // GET ALL NOTES
    public function index(): JsonResponse {
        $notes = Note::with(['images','labels','todos','user'])
            ->get();
        return response()->json($notes, 200);
    }

    // SPEICHERN
    public function save(Request $request) {

        DB::beginTransaction();
        try {
            $note = Note::create($request->all());

            if (isset($request['images']) && is_array($request['images'])) {
                foreach ($request['images'] as $img) {
                    $image = Image::firstOrNew ([
                        'url' => $img['url'],
                        'title' => $img['title']
                    ]);
                    $note->images()->save($image);
                }
            }

            // labels
            if (isset($request['labels']) && is_array($request['labels'])) {
                foreach ($request['labels'] as $label) {
                    $label = Label::firstOrNew (['name' => $label['name']]);
                    $note->labels()->save($label);
                }
            }

            //todos
           if (isset($request['todos']) && is_array($request['todos'])) {
                foreach ($request['todos'] as $todo) {
                    $todo = Todo::firstOrNew ([
                        'title' => $todo['title'],
                        'description' => $todo['description'],
                        'due' => $todo['due'],
                        'isPublic' => $todo['isPublic'],
                    ]);
                    $note->todos()->save($todo);
                }
            }

            DB::commit();
            return response()->json($note, 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json('saving Note failed: '.$e->getMessage(), 500);
        }
    }

    // UPDATEN
    public function update(Request $request, int $note_id) {
        DB::beginTransaction();
        try {
            $note = Note::with(['images', 'labels','todos','user'])->where('id', $note_id)->first();

            if ($note != null){
                $note->update($request->all());
                $note->images()->delete();

                // bilder
                if (isset($request['images']) && is_array($request['images'])) {
                    foreach ($request['images'] as $img) {
                        $image = Image::firstOrNew ([
                            'url' => $img['url'],
                            'title' => $img['title']
                        ]);
                        $note->images()->save($image);
                    }
                }

                // Labels
                $ids = [];
                if (isset($request['labels']) && is_array($request['labels'])) {
                    foreach ($request['labels'] as $label) {
                        array_push($ids, $label['id']);
                    }
                }

                $note->labels()->sync($ids);


                // Todos
                $note->todos()->delete();

                if (isset($request['todos']) && is_array($request['todos'])) {
                    foreach ($request['todos'] as $td) {
                        $todo = Todo::firstOrNew ([
                            'name' => $td['name'],
                            'description' => $td['description'],
                            'due' => $td['due'],
                            'isPublic' => $td['isPublic'],
                        ]);
                        $note->todos()->save($todo);
                    }
                }
                $note->save();
            }

            DB::commit();
            $note1 = Note::with(['images', 'labels','todos','user'])->where('id', $note_id)->first();
            return response()->json($note1, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json('saving note failed: '.$e->getMessage(), 500);
        }
    }

    // LÖSCHEN
    public function delete (int $note_id) {
        $note = Note::where('id', $note_id)->first();
        if ($note != null) {
            $note->delete();
            return response()->json("note (' . $note_id . ') successfully deleted", 200);
        } else {
            return response()->json("note (' . $note_id . ') couldn't be deleted", 422);
        }
    }

    // Notiz mit Suchbegriff suchen
    public function findBySearchTerm(string $searchTerm):JsonResponse{
        $notes = Note::with(['images', 'labels','todos','user'])
            ->where('title','LIKE','%'.$searchTerm.'%')
            ->orWhere('description','LIKE','%'.$searchTerm.'%')
            ->orWhereHas('user',function($query) use ($searchTerm){
                $query->where('name','LIKE','%'.$searchTerm.'%');
            })->get();
        return response()->json($notes, 200);
    }

    // Notiz durch ID finden
    public function findNoteByID(int $id): JsonResponse
    {
        $note = Note::where('id', $id)->with(['images', 'labels','todos','user'])->first();
        return $note != null ? response()->json($note, 200) : response()->json(null, 200);
    }

}
