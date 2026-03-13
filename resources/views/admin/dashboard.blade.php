@extends('layouts.app')

@section('content')
    {{-- MODAL --}}
    <div id="modal" class="fixed inset-0 z-50 hidden overflow-y-auto w-full" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop Blur -->
        <div id="modal-backdrop" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
        
        <!-- Modal Dialog -->
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div id="modal-panel" class="relative bg-white rounded-xl shadow-2xl text-left overflow-hidden sm:my-8 sm:w-full sm:max-w-xl transform transition-all duration-300 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Modal Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 id="modal-title" class="text-xl font-bold text-gray-800">Thêm người dùng</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-1 rounded-md transition-colors focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Modal Body (Load user_form.blade.php) -->
                @include('admin.user_form')
                
            </div>
        </div>
    </div>

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
            <p class="text-sm text-gray-500 mt-1">Danh sách người dùng và nhân sự phòng khám</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <!-- Bộ lọc Vai trò -->
            <select id="roleFilter" class="bg-white border text-sm border-gray-300 text-gray-700 py-2 px-3 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                <option value="all">Tất cả vai trò</option>
                <option value="admin">Admin</option>
                <option value="bác sĩ">Bác sĩ</option>
                <option value="lễ tân">Lễ tân</option>
                <option value="nhân viên phát thuốc">Nhân viên phát thuốc</option>
            </select>
            
            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow transition-colors flex items-center gap-2 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" /></svg>
                Thêm người dùng
            </button>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="table">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-sm md:text-base text-gray-600 tracking-wider">
                        <th class="py-4 px-6 font-semibold w-16">ID</th>
                        <th class="py-4 px-6 font-semibold">Họ và tên</th>
                        <th class="py-4 px-6 font-semibold">Vai trò</th>
                        <th class="py-4 px-6 font-semibold text-right">Chức năng</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3 px-6 font-medium text-gray-900">#{{ $user->id }}</td>
                            <td class="py-3 px-6 flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-base shadow-sm border border-blue-200">
                                    {{ substr($user->full_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">{{ $user->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->username ?? '...' }}</div>
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                @if($user->role->name == 'Admin')
                                    <span class="inline-flex items-center bg-red-50 text-red-700 px-2.5 py-1 rounded-md text-xs font-semibold uppercase tracking-wider border border-red-100">{{ $user->role->name }}</span>
                                @elseif($user->role->name == 'Bác sĩ' || $user->role->name == 'Bác sĩ')
                                    <span class="inline-flex items-center bg-green-50 text-green-700 px-2.5 py-1 rounded-md text-xs font-semibold uppercase tracking-wider border border-green-100">{{ $user->role->name }}</span>
                                @elseif($user->role->name == 'Lễ tân' || $user->role->name == 'Lễ tân')
                                    <span class="inline-flex items-center bg-yellow-50 text-yellow-700 px-2.5 py-1 rounded-md text-xs font-semibold uppercase tracking-wider border border-yellow-100">{{ $user->role->name }}</span>
                                @else
                                    <span class="inline-flex items-center bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md text-xs font-semibold uppercase tracking-wider border border-blue-100">{{ $user->role->name }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="viewUser({{ $user->id }})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Xem / Sửa">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button onclick="deleteUser({{ $user->id }})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        const modal = document.getElementById('modal');

        function openModal() {
            modal.classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('modal-backdrop').classList.remove('opacity-0');
                const panel = document.getElementById('modal-panel');
                panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);
            toggleDoctorForm();
        }

        function closeModal() {
            document.getElementById('modal-backdrop').classList.add('opacity-0');
            const panel = document.getElementById('modal-panel');
            panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                resetForm();
            }, 300);
        }

        function resetForm() {

            // reset form
            document.getElementById('userForm').reset();
            form = document.getElementById("userForm");
            form.action = "{{ route('users.store') }}" ;
            document.getElementById('formMethod').value = "POST";

            // title
            document.getElementById('modal-title').innerText = "Thêm người dùng";

            // bỏ check gender
            document.getElementById('g_male').checked = false;
            document.getElementById('g_female').checked = false;

            // reset select nếu cần
            document.getElementById('f_status').value = "";
            document.getElementById('f_role').value = "";
            
            // reset button text & icon
            const submitBtnBtn = document.getElementById('submitBtn');
            if(submitBtnBtn) {
                submitBtnBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Lưu thông tin
                `;
            }

        }

        function viewUser(id) {

            fetch('/users/' + id)
                .then(res => res.json())
                .then(user => {

                    document.getElementById('f_username').value = user.username;
                    document.getElementById('f_fullname').value = user.full_name;
                    document.getElementById('f_birth').value = user.birth_year;
                    document.getElementById('f_phone').value = user.phone;

                    // gender
                    if (user.gender === 'male' || user.gender === 1 || user.gender === '1') {
                        document.getElementById('g_male').checked = true;
                    } else {
                        document.getElementById('g_female').checked = true;
                    }

                    // select
                    document.getElementById('f_status').value = user.status;
                    document.getElementById('f_role').value = user.role_id;
                    toggleDoctorForm();
                    
                    if (user.doctor) {
                        document.getElementById('specialty').value = user.doctor.specialty ?? '';
                        document.getElementById('is_free').value = user.doctor.is_free ?? '';
                    } else {
                        document.getElementById('specialty').value = '';
                        document.getElementById('is_free').value = '0';
                    }

                    // title
                    document.getElementById('modal-title').innerText = "Sửa người dùng";

                    // button text
                    const submitBtnBtn = document.getElementById('submitBtn');
                    submitBtnBtn.innerHTML = `
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Cập nhật
                    `;

                    // action
                    const form = document.getElementById("userForm");
                    form.action = "/users/" + id;

                    // method PUT
                    document.getElementById('formMethod').value = "PUT";

                    openModal();

                })
                .catch(e => {
                    console.error("Lỗi khi lấy thông tin User:", e);
                });
        }

        function deleteUser(id) {
            showDeleteConfirm(id, 'người dùng này', '/users');
        }

        function toggleDoctorForm() {
            const doctorForm = document.getElementById('doctor_form');
            const role = document.getElementById('f_role');

            if (role.value == 2) { // 2 là role_id của Bác sĩ
                doctorForm.classList.remove('hidden');
                // Thêm hiệu ứng fade in nhẹ
                doctorForm.classList.add('animate-pulse');
                setTimeout(() => doctorForm.classList.remove('animate-pulse'), 500);
            } else {
                doctorForm.classList.add('hidden');
            }
        }

        // Khởi tạo Lọc người dùng theo vai trò
        document.addEventListener('DOMContentLoaded', function() {
            const roleFilter = document.getElementById('roleFilter');
            if(roleFilter) {
                roleFilter.addEventListener('change', function() {
                    const selectedRole = this.value.toLowerCase().trim().normalize('NFC');
                    const rows = document.querySelectorAll('#table tbody tr');

                    rows.forEach(row => {
                        // Cột vai trò là cột thứ 3 (index 2)
                        const roleCell = row.querySelector('td:nth-child(3)');
                        if (roleCell) {
                            const roleText = roleCell.textContent.trim().toLowerCase().normalize('NFC');
                            
                            if (selectedRole === 'all') {
                                row.style.display = '';
                            } else {
                                if (roleText === selectedRole || roleText.includes(selectedRole)) {
                                    row.style.display = '';
                                } else {
                                    row.style.display = 'none';
                                }
                            }
                        }
                    });
                });
            }
        });
    </script>
    @if ($errors->any() || session('success'))
        <script>
            window.onload = function() {

                openModal();
            }
        </script>
    @endif
@endsection

