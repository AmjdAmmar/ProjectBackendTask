<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\UploadsImages;
use App\Models\Images;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    use UploadsImages;


    public function index()
    {
        $users = User::with('product')->get();

        if (!$users) {
            return response()->json(['error' => 'user not found'], 404);
        }
        $filteredUsers = $users->flatMap(function ($user) {
            return $user->product->filter(function ($product) {
                return $product->price >= 150;
            });
        });
        $UsersArray = [];
        foreach ($filteredUsers as $user) {
            $UsersArray[] = [
                'Users' =>  $user,
                'image1' => $user->image,
                'created_from' => $user->created_from,
            ];
        }

        return response()->json(['filtered_users' => $UsersArray]);
    }

    public function create()
    {
        //
    }

    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();

        $existingCategory = User::where('name', $validatedData['name'])
            ->where('email', $validatedData['email'])
            ->where('status', $validatedData['status'])
            ->first();

        if ($existingCategory) {
            return response()->json(['message' => 'User already exists'], Response::HTTP_CONFLICT);
        }
        $validatedData['password'] = Hash::make($validatedData['password']);
        $path1 = $request->file('image1')->store('public/images');

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->status = $validatedData['status'];
        $user->save();


        $mergedImage = new Images([
            'imageable_type' => User::class,
            'imageable_id' => $user->id,
            'image1' => $path1,
        ]);
        $mergedImage->save();

        // Return a response indicating success
        return response()->json(['message' => 'Category created successfully', 'product' => $user, 'mergedImage' => $mergedImage], Response::HTTP_CREATED);
    }

    public function show(string $id)
    {
        $users = User::with('product.category')->findOrFail($id);

        if (!$users) {
            return response()->json(['error' => 'user not found'], 404);
        }



        $filteredusers = $users->product->filter(function ($products) {
            return $products->price >= 150;
        });

        $UsersArray = [];
        foreach ($filteredusers as $user) {
            $UsersArray[] = [
                'Users' =>  $user,
                'image1' => $user->image,
                'created_from' => $user->created_from,
            ];
        }

        return response()->json(['filtered_users' => $filteredusers, 'created_from' => $users->created_from]);
    }

    public function edit($id)
    {
        //
    }

    public function update(UserRequest $request, string $id)
    {
        $User = User::findOrFail($id);

        $User->name = $request->name;
        $User->email = $request->email;
        $User->password = $request->password;
        $User->status = $request->status;

        $User->save();
        // dd('sdsd');
        // if ($request->hasFile('image1')) {
        //     $image1 = $request->file('image1');
        //     $image2 = $request->hasFile('image2') ? $request->file('image2') : null;
        //     $this->uploadImage($image1, $image2, $User);
        // }


        // return response()->json(['message' => 'Product update successfully', 'product' => $User, 'mergedImage' => $this->uploadImage($image1, $image2, $User)], Response::HTTP_CREATED);
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $this->uploadImages($images, $User);
        }



        // return response()->json(['message' => 'Category update successfully', 'product' => $Category, 'mergedImage' => $this->uploadImage($image1, $image2, $Category)], Response::HTTP_CREATED);
        return response()->json(['message' => 'Product update successfully', 'product' => $User, 'mergedImage' => $this->uploadImages($images, $User)], Response::HTTP_CREATED);
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
