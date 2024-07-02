<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressCreateRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Models\Contact;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function create(int $idContact, AddressCreateRequest $request): JsonResponse
    {
        $user = Auth::user();// mengambil data user yang saat ini sedang login

        $contact = Contact::query()
            ->where("user_id", "=", $user->id)
            ->where("id", "=", $idContact)
            ->first();

        // jika id contact tidak ada maka exception not found 404
        if (!$contact){
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ], 404));
        }

        $data = $request->validated(); // validasi
        $address = new Address($data); // instance // binding model/entity dari resource/DTO dengan property $fillable
        $address->contact_id = $contact->id; // set contact_id (FK) dari id contact model
        $address->save();

        return (new AddressResource($address))
            ->response()
            ->setStatusCode(201);

    }

    public function getDetail(int $idContact, int $idAddress): AddressResource
    {
        $user = Auth::user(); // mengambil data user yang saat ini sedang login

        $contact = Contact::query()
            ->where("user_id", "=", $user->id)
            ->where("id", "=", $idContact)
            ->first();

        // jika id contact tidak ada maka exception not found 404
        if (!$contact){
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ], 404));
        }

        $address = Address::query()
            ->where("contact_id", "=", $contact->id)
            ->where("id", "=" , $idAddress)
            ->first();

        // jika id address tidak ada maka exception not found 404
        if (!$address){
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        return new AddressResource($address);

    }

}
