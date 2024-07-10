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
   public function profile()
   {
      $userID = Auth::get('user')->id;
      $user = User::with('info')->findOrFail($userID);
      $title = "Profile Page";
      return view('user.profile', compact('user', 'title'));
      // dd($user);
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
      return view('user.update-profile', ['user' => $user, 'title' => 'Update Profile']);
   }


   public function update_profile(Request $request)
   {
      $userId = Auth::get('user')->id;
      $user = User::findOrFail($userId);
      $rules = [
         'address' => 'required',
         'contact' => 'required|max:11|min:11',
      ];

      $validator = new Validator();
      $input = [
         'address' => $request->input('address'),
         'contact' => $request->input('contact')
      ];

      $profilePicture = $request->file('profilePicture');
      $storedFilePath = null;

      if ($profilePicture) {
         $destinationDirectory = '/images/';
         $storedFilePath = storeAs($profilePicture, $destinationDirectory);
      }

      if (!$validator->validate($input, $rules)) {
         $errors = $validator->errors();
         return view('user.update-profile', ['user' => $user, 'input' => $input, 'title' => 'Update Profile', 'errors' => $errors]);
      }

      try {
         $userInfo = UserInfo::where('user_id', '=', $userId)->first();

         if (!$userInfo) {
            $updated = UserInfo::create([
               'user_id' => $userId,
               'contact' => $request->input('contact'),
               'address' => $request->input('address'),
               'profile_picture' => $storedFilePath,
            ]);
         } else {
            if ($profilePicture && $userInfo->profile_picture) {
               unlink(BASE_PATH . '/public' . $userInfo->profile_picture);
            }

            $updateData = [
               'contact' => $request->input('contact'),
               'address' => $request->input('address'),
            ];

            if ($storedFilePath) {
               $updateData['profile_picture'] = $storedFilePath;
            }

            $updated = UserInfo::update($updateData, ['user_id' => $userId]);
         }

         if ($updated) {
            Flash::add('success', 'Profile has been Updated');
            return redirect('/profile', ['user' => $user, 'title' => 'Update Profile']);
         }
      } catch (SqlExecutionException $e) {
         $errorMessage = $e->getMessage();
         Flash::add('error', $errorMessage);
         return view('errors.sql-execution-error', ['title' => 'SQLExeception Error', 'errorMessages' => $errorMessage]);
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
