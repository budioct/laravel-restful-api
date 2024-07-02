<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressCreateRequest;
use App\Http\Requests\AddressUpdateRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function create(int $idContact, AddressCreateRequest $request): JsonResponse
    {
        $user = Auth::user();// mengambil data user yang saat ini sedang login

        $contact = $this->getContact($user, $idContact); // find contact_id (FK) address

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

        $contact = $this->getContact($user, $idContact); // find contact_id (FK) address
        $address = $this->getAddress($contact, $idAddress); // find id (PK) address

        return new AddressResource($address);

    }

    public function update(int $idContact, int $idAddress, AddressUpdateRequest $request): AddressResource
    {
        $user = Auth::user(); // mengambil data user yang saat ini sedang login

        $contact = $this->getContact($user, $idContact); // find contact_id (FK) address
        $address = $this->getAddress($contact, $idAddress); // find id (PK) address

        $data = $request->validated(); // validasi

        $address->fill($data); // fill(attribute) // binding model/entity dari resource/DTO dengan property $fillable
        $address->save();

        return new AddressResource($address);
    }

    public function delete(int $idContact, int $idAddress): JsonResponse
    {
        $user = Auth::user(); // mengambil data user yang saat ini sedang login

        $contact = $this->getContact($user, $idContact); // find contact_id (FK) address
        $address = $this->getAddress($contact, $idAddress); // find id (PK) address

        $address->delete();

        return response()->json([
            "data" => true,
        ])->setStatusCode(200);

    }

    public function getList(int $idContact): JsonResponse
    {
        $user = Auth::user(); // mengambil data user yang saat ini sedang login

        $contact = $this->getContact($user, $idContact); // find contact_id (FK) address

        $addresses = Address::query()
            ->where("contact_id", "=", $contact->id)
            ->get(); // find contact_id (FK) address. get list collection [{},{}]

        return (AddressResource::collection($addresses)
            ->response()
            ->setStatusCode(200)
        );

    }


    // function refactor
    private function getContact(User $user, int $idContact): Contact
    {

        $contact = Contact::
              where("user_id", "=", $user->id)
            ->where("id", "=", $idContact)
            ->first();

        // jika id contact tidak ada maka exception not found 404
        if (!$contact) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "contact not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        return $contact;

    }

    // function refactor
    private function getAddress(Contact $contact, int $idAddress): Address
    {
        $address = Address::
              where('contact_id', $contact->id)
            ->where('id', $idAddress)
            ->first();

        // jika id contact tidak ada maka exception not found 404
        if (!$address) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "address not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        return $address;

    }

}
