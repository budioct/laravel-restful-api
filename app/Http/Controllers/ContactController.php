<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactCreateRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function create(ContactCreateRequest $request): JsonResponse
    {
        $data = $request->validated(); // validasi

        $user = Auth::user(); // mengambil data user yang saat ini sedang login

        $contact = new Contact($data); // instance
        $contact->user_id = $user->id; // set user_id (FK) dari id user model
        $contact->save();

        return (new ContactResource($contact))
            ->response()
            ->setStatusCode(201);

    }

    public function getDetail(int $id): ContactResource
    {
        $user = Auth::user();

        // sql: select * from `contacts` where `id` = ? and `user_id` = ? limit 1
        $contact = Contact::query()
            ->where("id", "=", $id)
            ->where("user_id", "=", $user->id)
            ->first();

        // check apakah data ada, jika tidak ada akan exception
        if (!$contact) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ], 404));
        }

        return new ContactResource($contact);

    }

    public function update(int $id, ContactUpdateRequest $request): ContactResource
    {
        $user = Auth::user();

        // sql: select * from `contacts` where `id` = ? and `user_id` = ? limit 1
        $contact = Contact::query()
            ->where("id", "=", $id)
            ->where("user_id", "=", $user->id)
            ->first();

        // check apakah data ada, jika tidak ada akan exception
        if (!$contact) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ], 404));
        }

        $data = $request->validated(); // validasi

        $contact->fill($data); // binding data dto dengan model $fillable
        $contact->save();

        return new ContactResource($contact);

    }

}
