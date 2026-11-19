<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý thông tin cá nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand fw-bold">QUẢN LÝ THÔNG TIN CÁ NHÂN</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-light small">{{ $user->email ?? '' }}</span>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light">Đăng xuất</button>
            </form>
        </div>
    </div>
</nav>

<div class="container pb-5">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 1. THÔNG TIN CÁ NHÂN --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-bold bg-white">1. Thông tin cá nhân</div>
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Họ và tên *</label>
                    <input type="text" name="full_name" class="form-control"
                           value="{{ old('full_name', $profile->full_name ?? '') }}" required>
                </div>

       <div class="col-md-6">
    <label class="form-label">Số điện thoại *</label>
    <input type="text" name="phone" class="form-control" required
           maxlength="10" minlength="10" pattern="\d{10}" inputmode="numeric"
           oninput="this.value = this.value.replace(/\D/g, '').slice(0, 10)"
           oninvalid="this.setCustomValidity('Vui lòng nhập đúng 10 chữ số')"
           onchange="this.setCustomValidity('')"
           value="{{ old('phone', $profile->phone ?? '') }}">
</div>

                <div class="col-md-4">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="birth_date" class="form-control"
                           value="{{ old('birth_date', $profile->birth_date ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Giới tính</label>
                    @php $gender = old('gender', $profile->gender ?? ''); @endphp
                    <select name="gender" class="form-select">
                        <option value="">-- Chọn --</option>
                        <option value="male" {{ $gender === 'male' ? 'selected' : '' }}>Nam</option>
                        <option value="female" {{ $gender === 'female' ? 'selected' : '' }}>Nữ</option>
                        <option value="other" {{ $gender === 'other' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>

                <div class="col-md-4">
    <label class="form-label">CCCD / CMND (12 số)</label>
    <input type="text" name="citizen_id" class="form-control"
           maxlength="12" pattern="[0-9]{12}" inputmode="numeric"
           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)"
           title="Số CCCD phải gồm đúng 12 chữ số"
           value="{{ old('citizen_id', $profile->citizen_id ?? '') }}">
</div>  

                @php
                    $savedProvince = old('province', $profile->province ?? '');
                    $savedWard = old('ward', $profile->ward ?? '');
                @endphp

                <div class="col-md-6 address-fields"
                     data-saved-province="{{ $savedProvince }}"
                     data-saved-ward="{{ $savedWard }}"
                     data-locations-url="{{ asset('assets/data/vietnam-administrative-units.json') }}">
                    <label class="form-label">Tỉnh / Thành phố</label>
                    <select id="profile-province" name="province" class="form-select">
                        <option value="">-- Chọn tỉnh/thành phố --</option>
                    </select>
                    <small class="address-status text-muted d-block mt-1">Đang tải danh sách tỉnh/thành phố...</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Phường / Xã</label>
                    <select id="profile-ward" name="ward" class="form-select" disabled>
                        <option value="">-- Chọn phường/xã --</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Giới thiệu bản thân</label>
                    <textarea name="bio" rows="3" class="form-control">{{ old('bio', $profile->bio ?? '') }}</textarea>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Lưu thông tin cá nhân</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. HỌC BẠ --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-bold bg-white">2. Học bạ</div>
        <div class="card-body">
            <form action="{{ route('transcripts.store') }}" method="POST" enctype="multipart/form-data" class="row g-2 mb-3">
                @csrf
                <div class="col-md-3">
                    <input type="text" name="title" class="form-control" placeholder="Tên học bạ *" value="{{ old('title') }}" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="school_name" class="form-control" placeholder="Tên trường" value="{{ old('school_name') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="academic_year" class="form-control" placeholder="Năm học (VD: 2025-2026)" value="{{ old('academic_year') }}">
                </div>
                <div class="col-md-3">
                    <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-success w-100">Thêm</button>
                </div>
                <div class="col-12">
                    <small class="text-muted">Hỗ trợ PDF, JPG, PNG; dung lượng tối đa 10 MB.</small>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tên học bạ</th>
                            <th>Trường</th>
                            <th>Năm học</th>
                            <th>File</th>
                            <th style="width: 170px;" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transcripts as $transcript)
                            <tr>
                                <td class="fw-semibold">{{ $transcript->title }}</td>
                                <td>{{ $transcript->school_name ?: '—' }}</td>
                                <td>{{ $transcript->academic_year ?: '—' }}</td>
                                <td>
                                    <span class="d-block text-truncate" style="max-width: 220px;" title="{{ $transcript->file_name }}">
                                        {{ $transcript->file_name }}
                                    </span>
                                    <small class="text-muted">{{ number_format($transcript->file_size / 1024, 1) }} KB</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('transcripts.download', $transcript->id) }}" class="btn btn-sm btn-outline-primary">Tải xuống</a>
                                    <form action="{{ route('transcripts.destroy', $transcript->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa học bạ này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Chưa có học bạ nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 3. HỌC VẤN --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-bold bg-white">3. Học vấn</div>
        <div class="card-body">
            <form action="{{ route('education.store') }}" method="POST" class="row g-2 mb-3">
                @csrf
                <div class="col-md-4">
                    <input type="text" name="school_name" class="form-control" placeholder="Tên trường *" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="major" class="form-control" placeholder="Chuyên ngành">
                </div>
                <div class="col-md-2">
                    <input type="number" name="start_year" class="form-control" placeholder="Năm bắt đầu">
                </div>
                <div class="col-md-2">
                    <input type="number" name="end_year" class="form-control" placeholder="Năm kết thúc">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-success w-100">Thêm</button>
                </div>
            </form>

            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tên trường</th>
                        <th>Chuyên ngành</th>
                        <th style="width: 160px;">Thời gian</th>
                        <th style="width: 140px;" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($educations as $edu)
                        <tr>
                            <td class="fw-semibold">{{ $edu->school_name }}</td>
                            <td>{{ $edu->major ?? '—' }}</td>
                            <td>{{ $edu->start_year ?? '?' }} - {{ $edu->end_year ?? '?' }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-warning"
                                        data-bs-toggle="modal" data-bs-target="#editEdu{{ $edu->id }}">
                                    Sửa
                                </button>
                                <form action="{{ route('education.destroy', $edu->id) }}" method="POST"
                                      class="d-inline" onsubmit="return confirm('Xóa mục học vấn này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Sửa Học vấn --}}
                        <div class="modal fade" id="editEdu{{ $edu->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('education.update', $edu->id) }}" method="POST" class="modal-content">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Sửa học vấn</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Tên trường *</label>
                                            <input type="text" name="school_name" class="form-control" value="{{ $edu->school_name }}" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Chuyên ngành</label>
                                            <input type="text" name="major" class="form-control" value="{{ $edu->major }}">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Năm bắt đầu</label>
                                            <input type="number" name="start_year" class="form-control" value="{{ $edu->start_year }}">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Năm kết thúc</label>
                                            <input type="number" name="end_year" class="form-control" value="{{ $edu->end_year }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Chưa có dữ liệu học vấn.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 4. KỸ NĂNG --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-bold bg-white">4. Kỹ năng</div>
        <div class="card-body">
            <form action="{{ route('skills.store') }}" method="POST" class="row g-2 mb-3">
                @csrf
                <div class="col-md-6">
                    <input type="text" name="skill_name" class="form-control" placeholder="Tên kỹ năng *" required>
                </div>
                <div class="col-md-4">
                    <select name="level" class="form-select" required>
                        <option value="Mới học">Mới học</option>
                        <option value="Cơ bản" selected>Cơ bản</option>
                        <option value="Khá">Khá</option>
                        <option value="Tốt">Tốt</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100">Thêm</button>
                </div>
            </form>

            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tên kỹ năng</th>
                        <th style="width: 160px;">Mức độ</th>
                        <th style="width: 140px;" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($skills as $skill)
                        <tr>
                            <td class="fw-semibold">{{ $skill->skill_name }}</td>
                            <td><span class="badge bg-primary">{{ $skill->level }}</span></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-warning"
                                        data-bs-toggle="modal" data-bs-target="#editSkill{{ $skill->id }}">
                                    Sửa
                                </button>
                                <form action="{{ route('skills.destroy', $skill->id) }}" method="POST"
                                      class="d-inline" onsubmit="return confirm('Xóa kỹ năng này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Sửa Kỹ năng --}}
                        <div class="modal fade" id="editSkill{{ $skill->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('skills.update', $skill->id) }}" method="POST" class="modal-content">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Sửa kỹ năng</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Tên kỹ năng *</label>
                                            <input type="text" name="skill_name" class="form-control" value="{{ $skill->skill_name }}" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Mức độ *</label>
                                            <select name="level" class="form-select" required>
                                                @foreach (['Mới học', 'Cơ bản', 'Khá', 'Tốt'] as $lvl)
                                                    <option value="{{ $lvl }}" {{ $skill->level === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Chưa có dữ liệu kỹ năng.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 5. SỞ THÍCH --}}
    <div class="card shadow-sm">
        <div class="card-header fw-bold bg-white">5. Sở thích</div>
        <div class="card-body">
            <form action="{{ route('hobbies.store') }}" method="POST" class="row g-2 mb-3">
                @csrf
                <div class="col-md-4">
                    <input type="text" name="hobby_name" class="form-control" placeholder="Tên sở thích *" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="description" class="form-control" placeholder="Mô tả ngắn">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100">Thêm</button>
                </div>
            </form>

            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tên sở thích</th>
                        <th>Mô tả</th>
                        <th style="width: 140px;" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hobbies as $hobby)
                        <tr>
                            <td class="fw-semibold">{{ $hobby->hobby_name }}</td>
                            <td>{{ $hobby->description ?? '—' }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-warning"
                                        data-bs-toggle="modal" data-bs-target="#editHobby{{ $hobby->id }}">
                                    Sửa
                                </button>
                                <form action="{{ route('hobbies.destroy', $hobby->id) }}" method="POST"
                                      class="d-inline" onsubmit="return confirm('Xóa sở thích này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Sửa Sở thích --}}
                        <div class="modal fade" id="editHobby{{ $hobby->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('hobbies.update', $hobby->id) }}" method="POST" class="modal-content">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Sửa sở thích</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Tên sở thích *</label>
                                            <input type="text" name="hobby_name" class="form-control" value="{{ $hobby->hobby_name }}" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Mô tả</label>
                                            <input type="text" name="description" class="form-control" value="{{ $hobby->description }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Chưa có dữ liệu sở thích.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Bắt buộc có bundle JS của Bootstrap 5 để nút Sửa mở được Modal --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
{{-- Tải danh sách tỉnh/thành và phường/xã --}}
<script src="{{ asset('assets/js/profile-address.js') }}"></script>
</body>
</html>