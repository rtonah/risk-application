 <table class="table user-table table-hover align-items-center">
    <thead>
        <tr>
            <th class="border-bottom">
                <div class="form-check dashboard-check">
                    <input class="form-check-input" type="checkbox" value="" id="userCheck55">
                    <label class="form-check-label" for="userCheck55">
                    </label>
                </div>
            </th>
            <th class="border-bottom">Name</th>
            <th class="border-bottom">CIN</th>
            <th class="border-bottom">Document</th>
            <th class="border-bottom">Date Created</th>
            <th class="border-bottom">Status</th>
            <th class="border-bottom">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($blacklists as $bl)
            <tr class="border-t">
                <td>
                    <div class="form-check dashboard-check">
                        <input class="form-check-input" type="checkbox" value="" id="userCheck1">
                        <label class="form-check-label" for="userCheck1">
                        </label>
                    </div>
                </td>
                <td class="p-2">{{ $bl->full_name }}</td>
                <td class="p-2">{{ $bl->national_id }}</td>
                <td class="p-2">
                    @if($bl->document_path)
                        <a href="{{ asset('storage/' . $bl->document_path) }}" target="_blank" class="text-blue-600 underline"> <button class="btn btn-outline-success" type="button">Consulter</button></a>
                    @else
                        —
                    @endif
                </td>
                <td class="p-2">{{ $bl->created_at->translatedFormat('d F Y') }}</td>
                {{-- <td class="p-2">
                    <span class="{{ $bl->status == 'blacklisted' ? 'text-red-600' : 'text-green-600' }}">
                        {{ ucfirst($bl->status) }}
                    </span>
                </td> --}}
                <td class="p-2>
                    @if ($bl->status == 1)
                        <span class="text-danger">Blacklisted</span>
                    @elseif ($bl->status == 2)
                        <span class="text-success">Unblocked</span>
                    @endif
                </td>
                
                <td class="p-2">
                    @if($bl->status === '1')
                    <form action="{{ route('blacklists.unblock', $bl->id) }}" method="POST" onsubmit="return confirm('Unblock this client?')">
                        @csrf
                        <button class="btn btn-outline-danger">Unblock</button>
                    </form>
                    @else
                        <span class="text-gray-500 text-sm">Unblocked</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>