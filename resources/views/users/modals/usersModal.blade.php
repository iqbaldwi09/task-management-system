<div id="userModal" class="hidden fixed inset-0 flex items-center justify-center bg-black/40 z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
        <h2 id="modalTitle" class="text-lg font-semibold mb-4">Tambah User</h2>

        <form id="userForm">
            @csrf
            <input type="hidden" name="id" id="user_id">

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" id="name" required
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                </div>

                <div id="passwordContainer" class="relative">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-2 pr-10 border rounded-md border-gray-300 focus:ring focus:ring-blue-300 focus:outline-none">

                    <button type="button" id="togglePassword"
                        class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center text-gray-500 hover:text-gray-700">
                        <svg id="eyeShow" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eyeHide" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 013.042-4.362m4.687-2.074A9.993 9.993 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.977 9.977 0 01-1.249 2.592M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </button>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="role"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end mt-6 space-x-2">
                <button type="button" id="btnCloseUserModal" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
