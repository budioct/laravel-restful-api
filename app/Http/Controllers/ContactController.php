<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactCreateRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Builder;
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
        $user = Auth::user(); // mengambil data user yang saat ini sedang login

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
        $user = Auth::user(); // mengambil data user yang saat ini sedang login

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

    public function delete(int $id): JsonResponse
    {
        $user = Auth::user(); // mengambil data user yang saat ini sedang login

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

        $contact->delete();

        return response()
            ->json([
            "data" => true,
        ])->setStatusCode(200);

    }

    public function search(Request $request): ContactCollection
    {
        $user = Auth::user(); // mengambil data user yang saat ini sedang login

        // get value dari http web atau http api // lewat query param, form-data, dan request body
        $page = $request->input("page", 1); // input(key, nilai_default_jika_tidak_di_set_client) // get value dari http request
        $size = $request->input("size", 10);

        // sql: select * from `contacts `user_id` = ? limit 1
        $contacts = Contact::query()->where("user_id", $user->id);

        // buat builder search gunakan like query
        $contacts = $contacts->where(function (Builder $builder) use ($request) {

            $name = $request->input("name");
            if ($name) {
                $builder->where(function (Builder $builder) use ($name) {
                    $builder->orWhere("first_name", "like", "%" . $name . "%");
                    $builder->orWhere("last_name", "like", "%" . $name . "%");
                });
            }

            $email = $request->input("email");
            if ($email) {
                $builder->where("email", "like", "%" . $email . "%");
            }

            $phone = $request->input("phone");
            if ($phone) {
                $builder->where("phone", "like", "%" . $phone . "%");
            }
        });

        $contacts = $contacts->paginate(perPage: $size, page: $page); // buat pagination

        return new ContactCollection($contacts);
    }

}
