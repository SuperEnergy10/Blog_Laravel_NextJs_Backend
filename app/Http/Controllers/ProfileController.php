<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


    
        public function AdminProfile(){
            $id = Auth::user()->id;
            $profileData = User::find($id);
    
            return view('admin.profile', compact('profileData'));
        }
    
    
    
        public function ProfileStore(Request $request){
            $id = Auth::user()->id;
            $data = User::find($id);
        
            $data->name = $request->name;
            $data->email = $request->email;
    
            $oldPhotoPath = $data->image;
        
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('upload/user_images'), $filename);
                $data->image = $filename;
    
    
                if($oldPhotoPath && $oldPhotoPath !== $filename){
                    $this->deleteOldImage($oldPhotoPath);
                }
            }
        
            $data->save();
    
    
            $notification = array(
                'message' => 'Profile Updated Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->back()->with($notification);
        }
    
    
    
        private function deleteOldImage(string $oldPhotoPath) :  void {
            $fullPath = public_path('upload/user_images'. $oldPhotoPath);
    
            if(file_exists($fullPath)){
                unlink($fullPath);
            }
        }
    
    
    
    
        public function PasswordUpdate(Request $request)
        {
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|confirmed'
            ]);
        
            $user = Auth::user();
        
            if (!Hash::check($request->old_password, $user->password)) {
                $notification = array(
                    'message' => 'Old Password does not Match!',
                    'alert-type' => 'error'
                );
                return back()->with($notification);
            }
        
            User::whereId($user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
        
            Auth::logout();
        
            $notification = array(
                'message' => 'Password Changed Successfully',
                'alert-type' => 'success'
            );
        
            return redirect()->route('login')->with($notification);
        }
        
    
}
