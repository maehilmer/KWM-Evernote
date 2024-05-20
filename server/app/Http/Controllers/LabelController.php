<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Note;
use App\Models\Todo;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabelController extends Controller
{

    // GET ALL LABELS
    public function index(): JsonResponse {
        $labels = Label::with(['notes', 'todos', 'user']) // notes und todos maybe löschen??
            ->get();
        return response()->json($labels, 200);
    }

    // Label darf es nur einmal geben, deshalb wird Name gecheckt!!
    public function checkName (string $name) {
        $name = Label::where('name', $name)->first();
        return $name != null ? response()->json(true,200):response()->json(false,200);
    }

    // SPEICHERN
    public function save(Request $request) {

        DB::beginTransaction();
        try {
            $label = Label::create($request->all());

            // notes
            if (isset($request['notes']) && is_array($request['notes'])) {
                foreach ($request['notes'] as $note) {
                    $notes = Note::firstOrNew ([
                        'title' => $note['title'],
                        'description' => $note['description']
                    ]);
                    $label->notes()->save($notes);
                }
            }

            //todos
            if (isset($request['todos']) && is_array($request['todos'])) {
                foreach ($request['todos'] as $todo) {
                    $todos = Todo::firstOrNew ([
                        'title' => $todo['title'],
                        'description' => $todo['description'],
                        'due' => $todo['due'],
                        'isPublic' => $todo['isPublic'],
                    ]);
                    $label->todos()->save($todos);
                }
            }

            // users
            if (isset($request['users']) && is_array($request['users'])) {
                foreach ($request['users'] as $user) {
                    $user = User::firstOrNew (['name' => $user['name']]);
                    $label->users()->save($user);
                }
            }

            DB::commit();
            return response()->json($label, 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json('saving Label failed: '.$e->getMessage(), 500);
        }
    }


    // UPDATEN
    public function update(Request $request, int $label_id) {
        DB::beginTransaction();
        try {
            $label = Label::with(['notes', 'todos', 'user'])->where('id', $label_id)->first();

            if ($label != null){
                $label->update($request->all());

                // notes
                $idsN = [];
                if (isset($request['notes']) && is_array($request['notes'])) {
                    foreach ($request['notes'] as $note) {
                        array_push($ids, $note['id']);
                    }
                }
                $label->notes()->sync($idsN);

                // todos
                $ids = [];
                if (isset($request['todos']) && is_array($request['todos'])) {
                    foreach ($request['todos'] as $todo) {
                        array_push($ids, $todo['id']);
                    }
                }
                $label->todos()->sync($ids);

                // user
                if (isset($request['users']) && is_array($request['users'])) {
                    foreach ($request['users'] as $user) {
                        $user = User::firstOrNew (['name' => $user['name']]);
                        $label->users()->save($user);
                    }
                }

                $label->save();
            }

            DB::commit();
            $label1 = Label::with(['notes', 'todos', 'user'])->where('id', $label_id)->first();
            return response()->json($label1, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json('saving todo failed: '.$e->getMessage(), 500);
        }
    }


    // LÖSCHEN
    public function delete (int $label_id) {
        $label = Label::where('id', $label_id)->first();
        if ($label != null) {
            $label->delete();
            return response()->json("label (' . $label_id . ') successfully deleted", 200);
        } else {
            return response()->json("label (' . $label_id . ') couldn't be deleted", 422);
        }
    }

    // Label durch ID finden
    public function findLabelByID(int $id): JsonResponse
    {
        $label = Label::where('id', $id)->with(['notes', 'todos', 'user'])->first();
        return $label != null ? response()->json($label, 200) : response()->json(null, 200);
    }

    public function findBySearchTerm(string $searchTerm):JsonResponse{
        $label = Label::with(['notes', 'todos', 'user'])
            ->where('title','LIKE','%'.$searchTerm.'%')
            ->orWhereHas('user',function($query) use ($searchTerm){
                $query->where('name','LIKE','%'.$searchTerm.'%');
            })->get();
        return response()->json($label, 200);
    }

}
