<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\User\User_storeRequest;
use App\Http\Requests\User\User_updateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\UploadsImages;
use App\Models\Images;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

    public function index()
{
    $users = User::with('product', 'image')->get();
    $usersData = [];

    foreach ($users as $user) {
        $images = $user->image;

        $usersData[] = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'product' => $user->product,
            'images' => $images,
            'created_from' => $user->created_from,
        ];
    }

    return response()->json(['users' => $usersData]);
}




    public function create()
    {
        //
    }

    public function store(User_storeRequest $request)
    {
        $validatedData = $request->validated();

        // Check if the user already exists
        $existingUser = User::where('email', $validatedData['email'])->first();

        if ($existingUser) {
            return response()->json(['message' => 'User already exists'], Response::HTTP_CONFLICT);
        }

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $user = User::create($validatedData);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/User'), $imageName);
            $imageLink = asset('images/User/' . $imageName); // Add a slash after 'User'
            $user->image()->create(['url' => $imageLink]);

            return response()->json(['message' => 'User created successfully', 'user' => $user, 'image_link' => $imageLink], Response::HTTP_CREATED);
        }

        return response()->json(['message' => 'User created successfully', 'user' => $user], Response::HTTP_CREATED);
    }


    public function show(string $id)
    {
        $user = User::with('product', 'image')->findOrFail($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'product' => $user->product,
            'images' => $user->image,
            'created_from' => $user->created_from,
        ];

        return response()->json(['user' => $userData]);
    }

    public function edit($id)
    {
        //
    }



    public function update(User_updateRequest $request, string $id)
    {
        $validatedData = $request->validated();

        $user = User::findOrFail($id);

        $user->update($validatedData);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/category'), $imageName);

            $imageLink = asset('images/category' . $imageName);

            if ($user->image) {
                $user->image->update(['image' => $imageLink]);
            } else {
                $user->image()->create(['image' => $imageLink]);
            }
        }

        return response()->json(['message' => 'Category updated successfully', 'category' => $user, 'image_link' => $imageLink ?? null], Response::HTTP_OK);
    }



    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
