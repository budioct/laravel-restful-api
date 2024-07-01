<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactCreateRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
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
}
