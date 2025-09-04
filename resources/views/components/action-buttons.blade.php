{{-- resources/views/components/action-buttons.blade.php --}}
@php
    use App\Helpers\RoleHelper;
    $userRole = auth()->user()->role ?? 'user';
@endphp

<div class="btn-group" role="group">
    {{-- View Button - Semua role bisa lihat --}}
    @if(isset($viewRoute))
        <a href="{{ $viewRoute }}" class="btn btn-sm btn-info" title="Lihat Detail">
            <i class="fas fa-eye"></i>
        </a>
    @endif

    {{-- Edit Button - Admin & Operator --}}
    @if(isset($editRoute) && RoleHelper::canAccess($userRole, 'update'))
        <a href="{{ $editRoute }}" class="btn btn-sm btn-warning" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
    @endif

    {{-- Delete Button - Admin Only --}}
    @if(isset($deleteRoute) && RoleHelper::canAccess($userRole, 'delete'))
        <form method="POST" action="{{ $deleteRoute }}" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    @endif

    {{-- Custom Actions --}}
    {{ $slot ?? '' }}
</div>