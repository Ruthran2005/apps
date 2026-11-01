@extends('layouts.app')

@section('title', 'Library Management - SchoolNexus')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-slate-900 tracking-tight">Library Management</h1>
            <p class="text-sm text-slate-500 mt-1">Book catalog, barcode/ISBN tracking, lending records, returns, and inventory counts.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="$dispatch('open-modal-book')" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Book</span>
            </button>
            <button @click="$dispatch('open-modal-issue')" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-600/30 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span>Issue Book</span>
            </button>
        </div>
    </div>

    <!-- Library Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Catalog Titles</div>
            <div class="text-2xl font-bold font-display text-slate-900 mt-1">{{ $stats['total_titles'] }}</div>
            <div class="text-xs text-indigo-600 font-medium mt-1">Unique ISBN accessions</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Copies (Volumes)</div>
            <div class="text-2xl font-bold font-display text-emerald-600 mt-1">{{ $stats['total_volumes'] }}</div>
            <div class="text-xs text-slate-500 mt-1">In institutional holding</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Currently Issued</div>
            <div class="text-2xl font-bold font-display text-indigo-600 mt-1">{{ $stats['currently_issued'] }}</div>
            <div class="text-xs text-slate-500 mt-1">Circulating with borrowers</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Overdue Returns</div>
            <div class="text-2xl font-bold font-display text-rose-600 mt-1">{{ $stats['overdue_count'] }}</div>
            <div class="text-xs text-rose-600 font-medium mt-1">Pending return reminders</div>
        </div>
    </div>

    <!-- Book Catalog Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Book Catalog & Shelf Locations</h2>
                <p class="text-xs text-slate-500">Accession entries, authors, available copies, and rack IDs.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Title & Author</th>
                        <th class="px-5 py-3.5">ISBN</th>
                        <th class="px-5 py-3.5">Category</th>
                        <th class="px-5 py-3.5">Total Copies</th>
                        <th class="px-5 py-3.5">Available</th>
                        <th class="px-5 py-3.5">Shelf Location</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($books as $b)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-slate-900">{{ $b->title }}</div>
                            <div class="text-xs text-slate-500">by {{ $b->author }}</div>
                        </td>
                        <td class="px-5 py-3.5 font-mono text-xs text-slate-600">{{ $b->isbn ?? 'N/A' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">{{ $b->category }}</span>
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $b->total_copies }}</td>
                        <td class="px-5 py-3.5">
                            <span class="font-bold {{ $b->available_copies > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $b->available_copies }} left
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-xs font-semibold text-slate-600">{{ $b->shelf_location ?? 'General Stacks' }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <form action="{{ route('admin.library.books.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Remove book from catalog?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-semibold p-1 hover:bg-rose-50 rounded">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">No books registered in the catalog yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $books->links() }}
        </div>
    </div>

    <!-- Active Lending Records -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Current Lending Records & Returns</h2>
                <p class="text-xs text-slate-500">Track borrower names, due dates, overdue statuses, and record book check-ins.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="px-5 py-3.5">Book Title</th>
                        <th class="px-5 py-3.5">Borrower</th>
                        <th class="px-5 py-3.5">Issue Date</th>
                        <th class="px-5 py-3.5">Due Date</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Return Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($issues as $iss)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3.5 font-bold text-slate-900">{{ $iss->book->title ?? 'N/A' }}</td>
                        <td class="px-5 py-3.5">
                            <div class="font-medium text-slate-800">{{ $iss->user->name ?? 'User' }}</div>
                            <div class="text-[11px] text-slate-400 capitalize">{{ $iss->user_type }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">{{ Carbon\Carbon::parse($iss->issue_date)->format('M d, Y') }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-600 font-semibold">{{ Carbon\Carbon::parse($iss->due_date)->format('M d, Y') }}</td>
                        <td class="px-5 py-3.5">
                            @if($iss->status === 'returned')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Returned</span>
                            @elseif($iss->status === 'overdue')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">Overdue</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">Issued</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            @if($iss->status !== 'returned')
                            <form action="{{ route('admin.library.return', $iss->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 text-xs font-semibold rounded-lg transition">
                                    Mark Returned
                                </button>
                            </form>
                            @else
                                <span class="text-xs text-slate-400">Returned {{ $iss->return_date ? Carbon\Carbon::parse($iss->return_date)->format('M d') : '' }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-400 text-sm">No lending records logged.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $issues->links() }}
        </div>
    </div>
</div>

<!-- Modal 1: Add Book -->
<div x-data="{ open: false }" @open-modal-book.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Add Library Book</h3>
            <p class="text-xs text-slate-500 mb-4">Register a new volume into the central catalog.</p>
            <form action="{{ route('admin.library.books.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Book Title</label>
                    <input type="text" name="title" required placeholder="e.g. Clean Architecture" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Author</label>
                        <input type="text" name="author" required placeholder="Robert C. Martin" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">ISBN Code</label>
                        <input type="text" name="isbn" placeholder="978-0134494166" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Category</label>
                        <input type="text" name="category" required placeholder="Computer Science, Physics..." class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Total Copies</label>
                        <input type="number" name="total_copies" value="5" min="1" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Shelf / Rack Location</label>
                    <input type="text" name="shelf_location" placeholder="e.g. Rack CS-02" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-sm font-semibold hover:bg-slate-800">Add to Catalog</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Issue Book -->
<div x-data="{ open: false }" @open-modal-issue.window="open = true" x-cloak x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative z-10 shadow-2xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Issue Book to User</h3>
            <p class="text-xs text-slate-500 mb-4">Lend a book to a registered student or faculty member.</p>
            <form action="{{ route('admin.library.issue') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Book Title (Available Copies)</label>
                    <select name="book_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                        @foreach($books->where('available_copies', '>', 0) as $bk)
                            <option value="{{ $bk->id }}">{{ $bk->title }} ({{ $bk->available_copies }} available)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Borrower (Student / Teacher)</label>
                    <select name="user_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ ucfirst($u->role) }} - {{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Return Due Date</label>
                    <input type="date" name="due_date" required value="{{ Carbon\Carbon::now()->addDays(14)->toDateString() }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Remarks / Note</label>
                    <input type="text" name="remarks" placeholder="Optional lending condition note..." class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700">Confirm Issue</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
