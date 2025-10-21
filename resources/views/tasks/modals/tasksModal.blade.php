<div id="taskModal" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 bg-gray-900/60 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg">
        <form id="taskForm" class="p-6">
            @csrf
            <input type="hidden" id="task_id">
            <h2 class="text-lg font-semibold mb-4" id="modalTitle">Tambah Task</h2>

            <div class="mb-3">
                <label for="title" class="block text-sm font-medium text-gray-700">Judul</label>
                <input type="text" id="title" name="title"
                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>

            <div class="mb-3">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="description" name="description" rows="3"
                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
            </div>
            
            @php
                $authUser = auth()->user();
            @endphp

            <div class="mb-3">
                <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>

                @if ($authUser->role === 'admin')
                    <select id="user_id" name="user_id"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                        <option value="">-- Pilih User --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="hidden" id="user_id" name="user_id" value="{{ $authUser->id }}">
                    <input type="text" value="{{ $authUser->name }}"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed"
                        disabled>
                @endif
            </div>


            <div class="mb-3">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status"
                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    required>
                    <option value="to-do">To Do</option>
                    <option value="in-progress">In Progress</option>
                    <option value="done">Done</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="btnCloseModal"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
