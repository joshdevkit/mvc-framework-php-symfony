<?php

namespace App\Controller\User;

use App\Auth\Auth;
use App\Controller\Controller;
use App\Exceptions\SqlExecutionException;
use App\Framework\Http\Request;
use App\Framework\Session\Flash;
use App\Mail\Mailer;
use App\Models\User;
use App\Models\UserInfo;
use App\Util\Validator;
use Exception;

class UserController extends Controller
{
   public function dashboard()
   {
      $title = 'Admin Dashboard';
      return view('admin.admin', compact('title'));
   }
   public function profile()
   {
      $userID = Auth::user()->id;
      // $user = User::with('info')->findOrFail($userID);
      $user = User::with('info')->findOrFail($userID);
      $title = "Profile Page";
      return view('user.profile', compact('user', 'title'));
      dd($user);
   }

   public function show(Request $request, $slug)
   {
      dd($slug);
   }

   public function showBySlug(Request $request, $name)
   {
      $users = User::where('slug', '=', $name)->first();
      if (!$users) {
         return view('404', ['title' => 'User not Found with the search value ' . $name . ' ']);
      }
      return view('user.show', ['title' => 'User Profile', 'users' => $users]);
      // dd($users);
   }

   public function update()
   {
      $userId = Auth::get('user')->id;
      $user = User::with('info')->findOrFail($userId);
      $title = 'Update Profile';
      return view('user.update-profile', compact('user', 'title'));
   }


   public function update_profile(Request $request)
   {
      $userId = Auth::user()->id;
      $user = User::findOrFail($userId);

      // Validation rules
      $rules = [
         'address' => 'required',
         'contact' => 'required|max:11|min:11',
      ];

      $validator = new Validator();
      $input = [
         'address' => $request->input('address'),
         'contact' => $request->input('contact'),
      ];

      // Validate inputs
      if (!$validator->validate($input, $rules)) {
         $errors = $validator->errors();
         return view('user.update-profile', [
            'user' => $user,
            'input' => $input,
            'title' => 'Update Profile',
            'errors' => $errors,
         ]);
      }

      try {
         $userInfo = UserInfo::where('user_id', '=', $userId)->first();

         // Initialize update data
         $updateData = [
            'contact' => $request->input('contact'),
            'address' => $request->input('address'),
         ];

         // Handle profile picture upload
         $profilePicture = $request->file('profilePicture');
         if ($profilePicture && $profilePicture['name'] !== "") {
            $destinationDirectory = '/images/';
            $storedFilePath = storeAs($profilePicture, $destinationDirectory);

            // Remove old profile picture if exists
            if ($userInfo && $userInfo->profile_picture) {
               unlink(BASE_PATH . '/public' . $userInfo->profile_picture);
            }

            // Update profile picture path
            $updateData['profile_picture'] = $storedFilePath;
         }

         // Update or create user info
         if ($userInfo) {
            UserInfo::update($updateData, ['user_id' => $userId]);
         } else {
            $updateData['user_id'] = $userId;
            UserInfo::create($updateData);
         }

         Flash::add('success', 'Profile has been updated');
         return redirect('/profile', ['user' => $user, 'title' => 'Update Profile']);
      } catch (SqlExecutionException $e) {
         $errorMessage = $e->getMessage();
         Flash::add('error', $errorMessage);
         return view('errors.sql-execution-error', [
            'title' => 'SQL Execution Error',
            'errorMessages' => $errorMessage,
         ]);
      } catch (Exception $ex) {
         throw $ex;
      }
   }



   public function mail()
   {
      return view('mail', ['title' => 'Mailer']);
   }

   // public function samplemail(Request $request)
   // {
   //    $userId = Auth::get('user')->id;
   //    $user = User::findOrFail($userId);
   //    $subject = "Notification Subject";
   //    $message = "Hello, this is a notification message.";

   //    if ($user instanceof User) {
   //       // Proceed with notify method
   //       $user->notify($subject, $message);
   //       Flash::add('success', 'Email sent successfully!');
   //    } else {
   //       // Handle error or debug further
   //       Flash::add('error', 'User object not found or incorrect type.');
   //    }

   //    return view('mail', ['title' => 'Mailer']);
   // }
}
