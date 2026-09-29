<?php
// Mock Data - Updated statuses to Active, Locked, Pending
$customers = [
    ['id' => 'CUST-001', 'name' => 'Juan Dela Cruz', 'status' => 'Active', 'created_at' => '2023-01-15'],
    ['id' => 'CUST-002', 'name' => 'Maria Santos', 'status' => 'Locked', 'created_at' => '2023-03-22'],
    ['id' => 'CUST-003', 'name' => 'Pedro Penduko', 'status' => 'Pending', 'created_at' => '2024-05-10'],
    ['id' => 'CUST-004', 'name' => 'Ana de los Reyes', 'status' => 'Active', 'created_at' => '2024-08-01'],
    ['id' => 'CUST-005', 'name' => 'Jose Rizal', 'status' => 'Locked', 'created_at' => '2022-12-30'],
];
?>

<div class="page-container">
    
    <!-- Header Section -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Customer Information</h1>
        <p class="subtitle mt-1">Manage customer accounts, access levels, and verification status.</p>
    </div>

    <!-- Toolbar: Search & Filter -->
    <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6 shadow-sm">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            
            <!-- Left: Inputs -->
            <div class="flex flex-1 w-full md:w-auto gap-3">
                <div class="relative flex-1 max-w-xs">
                    <input type="text" id="searchInput" placeholder="Search by name or ID..." 
                           class="toolbar-input pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-none text-sm w-full transition-shadow">
                    <!-- Search Icon -->
                    <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                
                <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white cursor-pointer min-w-[140px]">
                    <option value="all">All Statuses</option>
                    <option value="Active">Active</option>
                    <option value="Pending">Pending</option>
                    <option value="Locked">Locked</option>
                </select>
            </div>

            <!-- Right: Bulk Actions (Hidden by default) -->
            <div id="bulkActions" class="hidden bulk-actions-bar animate-fade-in">
                <span class="text-sm text-gray-600 font-medium mr-2"><span id="selectedCount">0</span> selected</span>
                <button onclick="deleteSelected()" class="btn-danger-outline">
                    Delete Selected
                </button>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="w-12 px-6 py-4 text-left">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer w-4 h-4">
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer No.</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created Date</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="customerTableBody">
                    <?php foreach ($customers as $c): ?>
                    <tr class="hover:bg-gray-50 transition-colors duration-150 group" data-id="<?= htmlspecialchars($c['id']) ?>" data-name="<?= strtolower(htmlspecialchars($c['name'])) ?>" data-status="<?= htmlspecialchars($c['status']) ?>">
                        
                        <!-- Checkbox Cell -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" class="row-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer w-4 h-4" value="<?= htmlspecialchars($c['id']) ?>">
                        </td>

                        <!-- ID Cell -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">#<?= htmlspecialchars($c['id']) ?></td>

                        <!-- Name Cell -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($c['name']) ?></td>

                        <!-- Status Badge Cell -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                            // Map status to specific badge classes
                            $badgeClass = match($c['status']) {
                                'Active'   => 'badge-active',
                                'Pending'  => 'badge-pending',
                                'Locked'   => 'badge-locked', // Renamed from blocked
                                default     => 'badge-inactive'
                            };
                            ?>
                            <span class="badge <?= $badgeClass ?>">
                                <?= htmlspecialchars($c['status']) ?>
                            </span>
                        </td>

                        <!-- Date Cell -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($c['created_at']) ?></td>

                        <!-- Actions Cell (Text Buttons) -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                
                                <!-- View Button (Blue) -->
                                <button onclick="viewCustomer('<?= htmlspecialchars($c['id']) ?>')" class="action-text-btn text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-2 py-1 rounded transition-colors">
                                    View
                                </button>
                                
                                <!-- Edit Button (Orange/Yellow) -->
                                <button onclick="editCustomer('<?= htmlspecialchars($c['id']) ?>')" class="action-text-btn text-orange-600 hover:text-orange-800 hover:bg-orange-50 px-2 py-1 rounded transition-colors">
                                    Edit
                                </button>
                                
                                <!-- Delete Button (Red) -->
                                <button onclick="deleteCustomer('<?= htmlspecialchars($c['id']) ?>')" class="action-text-btn text-red-600 hover:text-red-800 hover:bg-red-50 px-2 py-1 rounded transition-colors">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <!-- Empty State Row -->
                    <tr id="noResultsRow" class="hidden">
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500 bg-gray-50/50">
                            <p class="font-medium">No customers found</p>
                            <p class="mt-1 text-gray-400">Try adjusting your search or filter criteria.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Footer -->
        <div class="pagination-footer bg-white px-6 py-4 border-t border-gray-200">
            <div class="flex-1 flex justify-between sm:hidden">
                <a href="#" class="page-link">Previous</a>
                <a href="#" class="page-link ml-3">Next</a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">1</span> to <span class="font-medium"><?= count($customers) ?></span> of <span class="font-medium"><?= count($customers) ?></span> results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <a href="#" class="page-link rounded-l-md">
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                        </a>
                        <a href="#" class="page-link active">1</a>
                        <a href="#" class="page-link">2</a>
                        <a href="#" class="page-link rounded-r-md">
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const selectAllCb = document.getElementById('selectAll');
        const rows = document.querySelectorAll('#customerTableBody tr:not(#noResultsRow)');
        const noResultsRow = document.getElementById('noResultsRow');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCountSpan = document.getElementById('selectedCount');

        if (!searchInput) return;

        // 1. Filter Logic
        function applyFilters() {
            const term = searchInput.value.toLowerCase();
            const status = statusFilter.value;
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.dataset.name || "";
                const rowStatus = row.dataset.status || "";
                
                const matchesSearch = name.includes(term);
                const matchesStatus = (status === 'all') || (rowStatus === status);

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                noResultsRow.classList.remove('hidden');
            } else {
                noResultsRow.classList.add('hidden');
            }
            
            selectAllCb.checked = false;
            updateBulkUI();
        }

        searchInput.addEventListener('input', applyFilters);
        statusFilter.addEventListener('change', applyFilters);

        // 2. Selection Logic
        function updateBulkUI() {
            const checked = document.querySelectorAll('.row-checkbox:checked');
            const count = checked.length;
            
            selectedCountSpan.textContent = count;
            
            if (count > 0) {
                bulkActions.classList.remove('hidden');
                bulkActions.classList.add('flex');
            } else {
                bulkActions.classList.add('hidden');
                bulkActions.classList.remove('flex');
            }
        }

        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.addEventListener('change', () => {
                const totalVisible = Array.from(rows).filter(r => r.style.display !== 'none').length;
                const checkedVisible = Array.from(document.querySelectorAll('.row-checkbox:checked')).filter(c => c.closest('tr').style.display !== 'none').length;
                
                selectAllCb.checked = (checkedVisible === totalVisible && totalVisible > 0);
                updateBulkUI();
            });
        });

        selectAllCb.addEventListener('change', (e) => {
            const isChecked = e.target.checked;
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    const cb = row.querySelector('.row-checkbox');
                    if (cb) cb.checked = isChecked;
                }
            });
            updateBulkUI();
        });

        // 3. Global Actions
        window.viewCustomer = (id) => alert(`Viewing details for ${id}`);
        window.editCustomer = (id) => alert(`Editing record for ${id}`);
        window.deleteCustomer = (id) => {
            if(confirm(`Are you sure you want to delete Customer #${id}? This action cannot be undone.`)) {
                alert(`Deleted ${id}`);
                // In real app: Remove row from DOM here
            }
        };
        window.deleteSelected = () => {
            const ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
            if(ids.length === 0) return;
            if(confirm(`Delete ${ids.length} selected customers?`)) {
                alert(`Batch deleted: ${ids.join(', ')}`);
            }
        };
    });
</script>