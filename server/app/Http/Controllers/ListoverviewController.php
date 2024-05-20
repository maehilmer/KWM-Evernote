<?php

namespace App\Http\Controllers;

use App\Models\Listoverview;
use App\Models\Note;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListoverviewController extends Controller
{
    // GET ALL LISTS
    public function index(): JsonResponse {
        $listoverviews = Listoverview::with(['notes', 'users'])
            ->get();
        return response()->json($listoverviews, 200);
    }

    // SPEICHERN
    public function save(Request $request) {

        DB::beginTransaction();
        try {
            $listoverview = Listoverview::create($request->all());

            // notes
            if (isset($request['notes']) && is_array($request['notes'])) {
                foreach ($request['notes'] as $note) {
                    $notes = Note::firstOrNew ([
                        'title' => $note['title'],
                        'description' => $note['description']
                    ]);
                    $listoverview->notes()->save($notes);
                }
            }

            // users
            if (isset($request['users']) && is_array($request['users'])) {
                foreach ($request['users'] as $user) {
                    $user = User::firstOrNew (['name' => $user['name']]);
                    $listoverview->users()->save($user);
                }
            }

            DB::commit();
            return response()->json($listoverview, 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json('saving Label failed: '.$e->getMessage(), 500);
        }
    }


    // UPDATEN
    public function update(Request $request, int $listoverview_id) {
        DB::beginTransaction();
        try {
            $listoverview = Listoverview::with(['notes', 'users'])->where('id', $listoverview_id)->first();

            if ($listoverview != null){
                $listoverview->update($request->all());

                // notes
                if (isset($request['notes']) && is_array($request['notes'])) {
                    foreach ($request['notes'] as $note) {
                        $notes = Note::firstOrNew ([
                            'title' => $note['title'],
                            'description' => $note['description']
                        ]);
                        $listoverview->notes()->save($notes);
                    }
                }

                // user
                $ids = [];
                if (isset($request['users']) && is_array($request['users'])) {
                    foreach ($request['users'] as $user) {
                        array_push($ids, $user['id']);
                    }
                }
                $listoverview->users()->sync($ids);


                $listoverview->save();
            }

            DB::commit();
            $listoverview1 = Listoverview::with(['notes', 'users'])->where('id', $listoverview_id)->first();
            return response()->json($listoverview1, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json('saving listoverview failed: '.$e->getMessage(), 500);
        }
    }


    // LÖSCHEN
    public function delete (int $listoverview_id) {
        $listoverview = Listoverview::where('id', $listoverview_id)->first();
        if ($listoverview != null) {
            $listoverview->delete();
            return response()->json("list (' . $listoverview_id . ') successfully deleted", 200);
        } else {
            return response()->json("list (' . $listoverview_id . ') couldn't be deleted", 422);
        }
    }

    // Liste durch ID finden
    public function findListByID(int $id): JsonResponse
    {
        $listoverview = Listoverview::where('id', $id)->with(['notes', 'users'])->first();
        return $listoverview != null ? response()->json($listoverview, 200) : response()->json(null, 200);
    }

}
