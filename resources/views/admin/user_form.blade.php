<div class="p-6">
    <form id="userForm" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="formMethod" name="_method" value="POST">
        
        <div class="flex flex-col md:flex-row gap-6 mb-6">
            <!-- Avatar Section -->
            <div class="flex-shrink-0 flex flex-col items-center gap-3">
                <div class="relative group">
                    <img id="avatarPreview" src="https://ui-avatars.com/api/?name=User&background=random" class="h-32 w-32 rounded-xl object-cover border-2 border-gray-200 shadow-sm" alt="Avatar Preview">
                    <label for="f_avatar" class="absolute inset-0 flex items-center justify-center bg-black/40 text-white rounded-xl opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </label>
                </div>
                <input id="f_avatar" type="file" name="avatar" class="hidden" accept="image/*" onchange="previewImage(this)">
                <button type="button" onclick="document.getElementById('f_avatar').click()" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Chọn ảnh đại diện</button>
                @error('avatar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Tên đăng nhập -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tên đăng nhập <span class="text-red-500">*</span></label>
                <input id="f_username" type="text" name="username" placeholder="Nhập tên đăng nhập" value="{{ old('username') }}" class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Mật khẩu -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Mật khẩu <span class="text-red-500">*</span></label>
                <input id="f_password" type="password" name="password" placeholder="Nhập mật khẩu" class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Họ tên -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Họ và tên <span class="text-red-500">*</span></label>
                <input id="f_fullname" type="text" name="full_name" placeholder="Nhập họ và tên" value="{{ old('full_name') }}" class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Số điện thoại -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Số điện thoại</label>
                <input id="f_phone" type="text" name="phone" placeholder="Nhập số điện thoại" class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
            </div>

            <!-- Năm sinh -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Năm sinh</label>
                <input id="f_birth" type="number" name="birth_year" placeholder="VD: 1990" class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
            </div>

            <!-- Giới tính -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Giới tính</label>
                <div class="flex items-center gap-4 mt-2">
                    <label class="flex items-center text-sm text-gray-700 cursor-pointer">
                        <input id="g_male" type="radio" name="gender" value="1" {{ old('gender') == '1' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                        <span class="ml-2">Nam</span>
                    </label>
                    <label class="flex items-center text-sm text-gray-700 cursor-pointer">
                        <input id="g_female" type="radio" name="gender" value="0" {{ old('gender') == '0' ? 'checked' : '' }} class="h-4 w-4 text-pink-500 focus:ring-pink-500 border-gray-300">
                        <span class="ml-2">Nữ</span>
                    </label>
                </div>
                @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Trạng thái -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Trạng thái</label>
                <select id="f_status" name="status" class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors bg-white">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Còn làm</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Đã nghỉ</option>
                </select>
                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Vai trò -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Vai trò</label>
                <select id="f_role" name="role_id" onchange="toggleDoctorForm()" class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors bg-white">
                    <option value="1">Admin</option>
                    <option value="2">Bác sĩ</option>
                    <option value="3">Lễ tân</option>
                    <option value="4">Nhân viên phát thuốc</option>
                </select>
            </div>
        </div>
        </div>
        
        <!-- DOCTOR SPECIFIC FIELDS -->
        <div id="doctor_form" class="hidden mb-6 bg-blue-50/50 p-4 rounded-xl border border-blue-100">
            <h4 class="text-sm font-bold text-blue-800 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Thông tin chuyên môn Bác sĩ
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Chuyên môn</label>
                    <input type="text" id="specialty" name="specialty" placeholder="VD: Da liễu thẩm mỹ" class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Trạng thái khám</label>
                    <select id="is_free" name="is_free" class="px-3 py-2 mt-1 w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors bg-white">
                        <option value="0">Đang rảnh (Có thể nhận lịch)</option>
                        <option value="1">Đang khám (Bận)</option>
                    </select>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-5 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h5 class="font-medium text-sm">Thành công</h5>
                    <p class="text-sm opacity-90">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Footer Buttons -->
        <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-gray-100">
            <button type="button" onclick="closeModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-gray-900 font-medium transition-colors shadow-sm">
                Hủy bỏ
            </button>
            <button id="submitBtn" type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Lưu thông tin
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('avatarPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

