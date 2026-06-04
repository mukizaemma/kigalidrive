<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Subscriber;
use App\Models\Car;
use App\Models\CarRental;
use Illuminate\Http\Request;
use App\Models\Articlecomment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Mail\CommentApprovalNotification;
use App\Models\BlogComment;
use App\Models\Message;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();

        $data = Setting::first();

        $totalCars = Car::count();
        $carBookingsQuery = CarRental::query()->where('rental_status', '!=', 'cancelled');
        $totalCarBookings = (clone $carBookingsQuery)->count();
        $totalCarBookingRevenue = (clone $carBookingsQuery)->sum('total_amount');

        $latestCarBookings = CarRental::with('car')
            ->latest()
            ->take(10)
            ->get();

        $setting = Setting::first();
        return view('admin.dashboard', [
            'users' => $users,
            'data' => $data,
            'setting' => $setting,
            'totalCars' => $totalCars,
            'totalCarBookings' => $totalCarBookings,
            'totalCarBookingRevenue' => $totalCarBookingRevenue,
            'latestCarBookings' => $latestCarBookings,
        ]);
    }

    /**
     * Display users page - accessible ONLY to super admin (email = admin@iremetech.com)
     * Regular admins cannot access the Users section at all.
     */
    public function users(Request $request){
        $isSuperAdmin = Auth::check() && Auth::user()->email === 'admin@iremetech.com';
        if (!$isSuperAdmin) {
            return redirect()->route('dashboard')->with('error', 'Only the super admin can access the Users section.');
        }

        $withCount = [
            'properties',
            'hotelBookings as guest_bookings_count',
        ];
        if (Schema::hasTable('hotels')) {
            $withCount[] = 'ownedHotels';
        }

        $query = User::query()->withCount($withCount);

        if (Schema::hasTable('hotel_bookings')) {
            $hostBookingsSql = '(
                SELECT COUNT(*) FROM hotel_bookings hb
                WHERE hb.deleted_at IS NULL
                AND (
                    hb.property_id IN (SELECT id FROM properties WHERE owner_id = users.id AND deleted_at IS NULL)
                    OR hb.unit_id IN (
                        SELECT u.id FROM units u
                        INNER JOIN properties p ON u.property_id = p.id
                        WHERE p.owner_id = users.id AND u.deleted_at IS NULL AND p.deleted_at IS NULL
                    )';
            if (Schema::hasTable('hotels') && Schema::hasTable('hotel_rooms')) {
                $hostBookingsSql .= '
                    OR hb.hotel_id IN (SELECT id FROM hotels WHERE added_by = users.id AND deleted_at IS NULL)
                    OR hb.room_id IN (
                        SELECT r.id FROM hotel_rooms r
                        INNER JOIN hotels h ON r.hotel_id = h.id
                        WHERE h.added_by = users.id AND r.deleted_at IS NULL AND h.deleted_at IS NULL
                    )';
            }
            $hostBookingsSql .= '
                )
            ) as host_bookings_count';
            $query->addSelect(DB::raw($hostBookingsSql));
        }
        
        // Check if current user is super admin (email = admin@iremetech.com)
        // Note: role == 1 means admin, role != 1 or null means regular user
        $isSuperAdmin = Auth::check() && Auth::user()->email === 'admin@iremetech.com';
        
        // Filter by admin status
        $filter = $request->input('filter', 'all'); // all, admins, users
        
        // Only super admin can view admins filter
        if ($filter === 'admins' && !$isSuperAdmin) {
            $filter = 'all'; // Reset to all if non-super-admin tries to access admins
        }
        
        if ($filter === 'admins' && $isSuperAdmin) {
            // Super admin viewing admins only (role == 1)
            $query->where('role', 1);
        } elseif ($filter === 'users') {
            // Viewing regular users only (role != 1 or null)
            $query->where(function($q) {
                $q->where('role', '!=', 1)->orWhereNull('role');
            });
        } elseif ($filter === 'all') {
            // View all - but exclude admins (role == 1) for non-super-admins
            if (!$isSuperAdmin) {
                // Regular admins should not see other admins (role == 1) in "all" view
                $query->where(function($q) {
                    $q->where('role', '!=', 1)->orWhereNull('role');
                });
            }
            // Super admin can see everyone including admins (no filter needed)
        }
        
        // Search functionality - search in both name and email
        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $segment = $request->input('segment');
        $nonAdminScope = function ($q) {
            $q->where(function ($w) {
                $w->where('role', '!=', 1)->orWhereNull('role');
            });
        };

        if ($segment === 'unverified') {
            $query->whereNull('email_verified_at')->where($nonAdminScope);
        } elseif ($segment === 'verified_no_property') {
            $query->whereNotNull('email_verified_at')
                ->whereDoesntHave('properties')
                ->where($nonAdminScope);
            if (Schema::hasTable('hotels')) {
                $query->whereDoesntHave('ownedHotels');
            }
        } elseif ($segment === 'with_properties') {
            $query->where(function ($q) {
                $q->whereHas('properties');
                if (Schema::hasTable('hotels')) {
                    $q->orWhereHas('ownedHotels');
                }
            })->where($nonAdminScope);
        }

        $segmentCounts = null;
        if ($isSuperAdmin) {
            $segmentCounts = [
                'unverified' => User::query()->where($nonAdminScope)->whereNull('email_verified_at')->count(),
                'verified_no_property' => tap(User::query()->where($nonAdminScope)
                    ->whereNotNull('email_verified_at')
                    ->whereDoesntHave('properties'), function ($q) {
                        if (Schema::hasTable('hotels')) {
                            $q->whereDoesntHave('ownedHotels');
                        }
                    })->count(),
                'with_properties' => User::query()->where($nonAdminScope)
                    ->where(function ($q) {
                        $q->whereHas('properties');
                        if (Schema::hasTable('hotels')) {
                            $q->orWhereHas('ownedHotels');
                        }
                    })
                    ->count(),
            ];
        }
        
        $users = $query->latest()->get();
        $setting = Setting::first();

        $canBulkDeleteSelected = Auth::check() && Auth::user()->email === 'admin@iremetech.com';
        
        return view('admin.users',[
            'users'=>$users,
            'setting'=>$setting,
            'filter'=>$filter,
            'search'=>$search,
            'segment'=>$segment,
            'segmentCounts'=>$segmentCounts,
            'isSuperAdmin'=>$isSuperAdmin,
            'canBulkDeleteSelected'=>$canBulkDeleteSelected,
        ]);
    }

    /**
     * Bulk-delete users (primary super admin only). Never deletes admins or the current user.
     */
    public function bulkDeleteUsers(Request $request)
    {
        $canBulkDelete = Auth::check() && Auth::user()->email === 'admin@iremetech.com';
        if (!$canBulkDelete) {
            return redirect()->route('dashboard')->with('error', 'Only admin@iremetech.com may bulk-delete users.');
        }

        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:users,id',
        ]);

        $ids = collect($validated['ids'])->unique()->filter(fn ($id) => (int) $id !== (int) Auth::id())->values();

        if ($ids->isEmpty()) {
            return redirect()->back()->with('info', 'No users selected.');
        }

        $deleted = User::query()
            ->whereIn('id', $ids)
            ->where(function ($q) {
                $q->where('role', '!=', 1)->orWhereNull('role');
            })
            ->delete();

        return redirect()->back()->with('success', $deleted.' user account(s) removed.');
    }

    public function showUser($id){
        $isSuperAdmin = Auth::check() && Auth::user()->email === 'admin@iremetech.com';
        if (!$isSuperAdmin) {
            return redirect()->route('dashboard')->with('error', 'Only the super admin can access the Users section.');
        }

        $user = User::with(['properties' => function($query) {
            $query->with('category', 'images')->latest();
        }, 'hotelBookings' => function($query) {
            $query->with('property', 'unit')->latest();
        }])->findOrFail($id);

        $setting = Setting::first();
        return view('admin.users.show', [
            'user' => $user,
            'setting' => $setting,
            'isSuperAdmin' => true
        ]);
    }

    /**
     * Update role and account access (super admin only).
     */
    public function updateUser(Request $request, $id)
    {
        $isSuperAdmin = Auth::check() && Auth::user()->email === 'admin@iremetech.com';
        if (!$isSuperAdmin) {
            return redirect()->route('dashboard')->with('error', 'Only the super admin can access the Users section.');
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'role' => 'required|in:0,1,2',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ((int) $user->id === (int) Auth::id() && $validated['status'] === 'Inactive') {
            return redirect()->back()->with('error', 'You cannot suspend your own account.');
        }

        $user->role = $validated['role'];
        $user->status = $validated['status'];
        $user->save();

        return redirect()->back()->with('success', 'User account updated successfully.');
    }

    /**
     * Set a new password directly for a user (super admin only).
     */
    public function setUserPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ((int) $user->id === (int) Auth::id()) {
            return redirect()->route('my.profile')
                ->with('info', 'Use My Profile to change your own password.');
        }

        $validated = $request->validate([
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->forceFill([
            'password' => \Illuminate\Support\Facades\Hash::make($validated['new_password']),
        ])->save();

        return redirect()->back()->with('success', 'Password updated for ' . $user->email . '.');
    }

    /**
     * Send a password reset link to the user's email (super admin only).
     */
    public function sendUserPasswordReset($id)
    {
        $isSuperAdmin = Auth::check() && Auth::user()->email === 'admin@iremetech.com';
        if (! $isSuperAdmin) {
            return redirect()->route('dashboard')->with('error', 'Only the super admin can access the Users section.');
        }

        $user = User::findOrFail($id);

        if ((int) $user->id === (int) Auth::id()) {
            return redirect()->back()->with('info', 'Use your profile or the forgot-password page to change your own password.');
        }

        $status = Password::broker()->sendResetLink(['email' => $user->email]);

        if ($status === Password::RESET_LINK_SENT) {
            return redirect()->back()->with('success', 'A password reset link has been sent to '.$user->email.'.');
        }

        return redirect()->back()->with('error', __($status));
    }

    public function verifyUserEmail($id){
        $isSuperAdmin = Auth::check() && Auth::user()->email === 'admin@iremetech.com';
        if (!$isSuperAdmin) {
            return redirect()->route('dashboard')->with('error', 'Only the super admin can access the Users section.');
        }

        $user = User::findOrFail($id);

        if (! $user->hasVerifiedEmail()) {
            $user->forceFill(['email_verified_at' => now()])->save();

            return redirect()->back()->with('success', 'User email has been verified (no notification sent).');
        }

        return redirect()->back()->with('info', 'User email is already verified.');
    }

    public function makeAdmin($id){
        $isSuperAdmin = Auth::check() && Auth::user()->email === 'admin@iremetech.com';
        if (!$isSuperAdmin) {
            return redirect()->route('dashboard')->with('error', 'Only the super admin can access the Users section.');
        }
        
        $user = User::findOrFail($id);
        $user->role = 1; // Set role to 1 (admin)
        $user->save();

        return redirect()->back()->with('success','User is now an admin');
    }

    
    public function deleteUser($id)
    {
        $isSuperAdmin = Auth::check() && Auth::user()->email === 'admin@iremetech.com';
        if (!$isSuperAdmin) {
            return redirect()->route('dashboard')->with('error', 'Only the super admin can access the Users section.');
        }

        $post = User::findOrFail($id);
        $post->delete();

        return redirect()->back()->with('success', 'User has been deleted');
    }


    public function blogsComment(Request $request)
    {
        $filter = $request->input('filter', 'all'); // Get the filter type, default to 'all'
    
        $comments = BlogComment::query();
    
        if ($filter === 'published') {
            $comments->where('status', 'Published');
        } elseif ($filter === 'unpublished') {
            $comments->where('status', 'Unpublished');
        }
    
        $comments = $comments->latest()->paginate(2);
    
        return view('admin.posts.comments', [
            'comments' => $comments,
            'filter' => $filter, // Pass the current filter to the view
        ]);
    }
    

    public function commentApprove(BlogComment $comment){

        if($comment->status !=='Published'){
            $comment->status='Published';
            $comment->save();

            $user = $comment->user;

            if($user){
                $details = [
                    'greeting' => 'Hello ' . $user->name . '!',
                    'body' => 'Thank you so much for your helpful comment',
                    'lastline' => 'Blessings!',
                ];
                Mail::to($user->email)->queue(new CommentApprovalNotification($details));
                return redirect()->route('blogsComment')->with('success', 'Comment approved successfully');
            }
        }
        return redirect()->back()->with('error', 'Unable to approve comment');

    }

    public function destroyBlogComment($id)
    {
        $comment = BlogComment::find($id); 
        $comment->delete($id);
        return back()
            ->with('success', 'Comment deleted successfully');
    }

    public function subscribers(){
        $setting = Setting::first();
        $subscribers = Subscriber::latest()->paginate(20);
        return view('admin.posts.subscribers',[
            'subscribers'=>$subscribers,
            'setting'=>$setting,
        ]);
    }

    
    public function destroySub($id)
    {
        $subscriber = Subscriber::find($id); 
        $subscriber->delete($id);
        return back()
            ->with('success', 'Subscriber deleted successfully');
    }
    public function getMessages(){
        $messages = Message::latest()->paginate(10);
        return view('admin.posts.messages',[
            'messages'=>$messages,
        ]);
    }

    
    public function deleteMessages($id)
    {
        $subscriber = Message::find($id); 
        $subscriber->delete($id);
        return back()
            ->with('success', 'Message deleted successfully');
    }

    // public function visits()
    // {
    //     $totalVisits = DB::table('visits')->count();
    //     $uniqueVisitors = DB::table('visits')->distinct('ip_address')->count();


    //     return view('admin.dashboard',[
    //         'totalVisits'=>$totalVisits,
    //         'uniqueVisitors'=>$uniqueVisitors,
    //     ]);
    // }

    /**
     * Logout the authenticated user
     */
    public function logouts()
    {
        Auth::logout();
        
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }

}
