<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Hobby;
use App\Models\PersonalInformation;
use App\Models\Skill;
use App\Models\Transcript;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $profile = PersonalInformation::find($userId);
        $educations = Education::where('user_id', $userId)->get();
        $skills = Skill::where('user_id', $userId)->get();
        $hobbies = Hobby::where('user_id', $userId)->get();
        $transcripts = Transcript::where('user_id', $userId)->latest()->get();

        return view('profile.index', compact('user', 'profile', 'educations', 'skills', 'hobbies', 'transcripts'));
    }

    public function updateProfile(Request $request)
{
    $data = $request->validate([
        'full_name' => 'required|string|max:120',
        'phone' => 'required|digits:10',
        'birth_date' => 'nullable|date',
        'gender' => 'nullable|in:male,female,other',
        'address' => 'nullable|string|max:255',
        'ward' => 'nullable|string|max:100',
        'province' => 'nullable|string|max:80',
        'citizen_id' => 'nullable|digits:12',
        'bio' => 'nullable|string',
    ], [
        'full_name.required' => 'Vui lòng nhập họ và tên.',
        'phone.digits' => 'Số điện thoại phải gồm đúng 10 chữ số.',
        'citizen_id.digits' => 'Số CCCD phải gồm đúng 12 chữ số.',
    ]);

    PersonalInformation::updateOrCreate(['user_id' => Auth::id()], $data);

    return redirect()->route('profile.index')->with('success', 'Đã cập nhật thông tin cá nhân.');
}

    // --- HỌC VẤN ---
    public function storeEducation(Request $request)
    {
        $data = $request->validate([
            'school_name' => 'required|string|max:150',
            'major' => 'nullable|string|max:120',
            'start_year' => 'nullable|integer|min:1950|max:2100',
            'end_year' => 'nullable|integer|min:1950|max:2100',
        ]);
        $data['user_id'] = Auth::id();
        Education::create($data);

        return redirect()->route('profile.index')->with('success', 'Đã thêm học vấn.');
    }

    public function updateEducation(Request $request, $id)
    {
        $data = $request->validate([
            'school_name' => 'required|string|max:150',
            'major' => 'nullable|string|max:120',
            'start_year' => 'nullable|integer|min:1950|max:2100',
            'end_year' => 'nullable|integer|min:1950|max:2100',
        ]);

        Education::where('user_id', Auth::id())->where('id', $id)->update($data);

        return redirect()->route('profile.index')->with('success', 'Đã cập nhật học vấn.');
    }

    public function deleteEducation($id)
    {
        Education::where('user_id', Auth::id())->where('id', $id)->delete();
        return redirect()->route('profile.index')->with('success', 'Đã xóa học vấn.');
    }

    // --- KỸ NĂNG ---
    public function storeSkill(Request $request)
    {
        $data = $request->validate([
            'skill_name' => 'required|string|max:100',
            'level' => 'required|in:Mới học,Cơ bản,Khá,Tốt',
        ]);
        $data['user_id'] = Auth::id();
        Skill::create($data);

        return redirect()->route('profile.index')->with('success', 'Đã thêm kỹ năng.');
    }

    public function updateSkill(Request $request, $id)
    {
        $data = $request->validate([
            'skill_name' => 'required|string|max:100',
            'level' => 'required|in:Mới học,Cơ bản,Khá,Tốt',
        ]);

        Skill::where('user_id', Auth::id())->where('id', $id)->update($data);

        return redirect()->route('profile.index')->with('success', 'Đã cập nhật kỹ năng.');
    }

    public function deleteSkill($id)
    {
        Skill::where('user_id', Auth::id())->where('id', $id)->delete();
        return redirect()->route('profile.index')->with('success', 'Đã xóa kỹ năng.');
    }

    // --- SỞ THÍCH ---
    public function storeHobby(Request $request)
    {
        $data = $request->validate([
            'hobby_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);
        $data['user_id'] = Auth::id();
        Hobby::create($data);

        return redirect()->route('profile.index')->with('success', 'Đã thêm sở thích.');
    }

    public function updateHobby(Request $request, $id)
    {
        $data = $request->validate([
            'hobby_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        Hobby::where('user_id', Auth::id())->where('id', $id)->update($data);

        return redirect()->route('profile.index')->with('success', 'Đã cập nhật sở thích.');
    }

    public function deleteHobby($id)
    {
        Hobby::where('user_id', Auth::id())->where('id', $id)->delete();
        return redirect()->route('profile.index')->with('success', 'Đã xóa sở thích.');
    }

    // --- HỌC BẠ ---
    public function storeTranscript(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:150',
            'school_name' => 'nullable|string|max:150',
            'academic_year' => 'nullable|string|max:20',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ], [
            'title.required' => 'Vui lòng nhập tên học bạ.',
            'file.required' => 'Vui lòng chọn file học bạ.',
            'file.mimes' => 'Chỉ hỗ trợ file PDF hoặc hình ảnh JPG/PNG.',
            'file.max' => 'File học bạ không được vượt quá 10 MB.',
        ]);

        $file = $request->file('file');
        $path = $file->store('transcripts/'.Auth::id(), 'public');

        Transcript::create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'school_name' => $data['school_name'] ?? null,
            'academic_year' => $data['academic_year'] ?? null,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return redirect()->route('profile.index')->with('success', 'Đã thêm học bạ.');
    }

    public function downloadTranscript($id)
    {
        $transcript = Transcript::where('user_id', Auth::id())->findOrFail($id);

        abort_unless(Storage::disk('public')->exists($transcript->file_path), 404);

        return Storage::disk('public')->download($transcript->file_path, $transcript->file_name);
    }

    public function deleteTranscript($id)
    {
        $transcript = Transcript::where('user_id', Auth::id())->findOrFail($id);

        Storage::disk('public')->delete($transcript->file_path);
        $transcript->delete();

        return redirect()->route('profile.index')->with('success', 'Đã xóa học bạ.');
    }
}