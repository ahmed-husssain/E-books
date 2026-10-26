<?php
include "../../Config/db.php";
include "../Components/checkifadmin.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management | E - Books</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        slate: {
                            950: '#020617',
                            900: '#0f172a',
                            800: '#1e293b',
                            700: '#334155',
                            600: '#475569',
                            500: '#64748b',
                            400: '#94a3b8',
                            300: '#cbd5e1'
                        },
                        emerald: {
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857'
                        }
                    },
                    borderRadius: {
                        none: "0px",
                        sm: "4px",
                        DEFAULT: "8px",
                        md: "12px",
                        lg: "16px",
                        xl: "20px",
                        '2xl': "24px",
                        '3xl': "32px",
                        full: "9999px",
                        button: "8px",
                    }
                }
            }
        }
    </script>
    <style>
        * {
            box-sizing: border-box;
        }
        #sidebar.collapsed ~ #main-content {
            margin-left: 4rem;
        }
        #sidebar:not(.collapsed) ~ #main-content {
            margin-left: 16rem;
        }
        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(8px);
            transition: all 0.3s ease;
        }
        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .mobile-menu.open {
            max-height: 500px;
        }
        .table-responsive {
            -webkit-overflow-scrolling: touch;
            max-height: 32rem;
            overflow-y: auto;
            width: 100%;
            max-width: 100%;
        }
        .table-responsive table {
            width: 100%;
            max-width: 100%;
        }
        .table-responsive tr {
            display: block;
            border: 1px solid rgba(51, 65, 85, 0.5);
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.5), rgba(4, 120, 87, 0.1));
            margin-bottom: 1rem;
            padding: 1rem;
            visibility: visible;
            opacity: 1;
        }
        .table-responsive td {
            display: block;
            width: 100%;
            max-width: 100%;
            padding: 0.5rem 0;
            text-align: left;
            position: relative;
            visibility: visible;
            opacity: 1;
        }
        .table-responsive td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #94a3b8;
            display: block;
            margin-bottom: 0.25rem;
        }
        .table-responsive thead {
            display: none;
        }
        .table-responsive .order-user, .table-responsive .order-book {
            max-width: 100%;
        }
        .table-responsive .action-btn {
            background: rgba(51, 65, 85, 0.5);
            transition: all 0.3s ease;
        }
        .table-responsive .action-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }
        .table-responsive tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(16, 185, 129, 0.2);
        }
        /* Responsive Adjustments */
        @media (min-width: 1024px) {
            .table-responsive {
                max-height: 40rem;
            }
            .table-responsive td {
                font-size: 1rem;
                padding: 0.75rem 0;
            }
            .table-responsive .action-btn {
                width: 40px;
                height: 40px;
            }
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
</head>
<body class="bg-slate-950 text-white font-['Inter']">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include "../Components/sider.php"; ?>
        <!-- Main Content -->
        <main id="main-content" class="min-h-screen w-full transition-all duration-300 mt-12">
            <!-- Page Content -->
            <div class="pt-16 p-4 sm:p-6">
                <!-- Orders Page -->
                <div id="orders-page" class="page active">
                    <div class="flex flex-col sm:flex-row items-center justify-between mb-6 mt-3 gap-4">
                        <h1 class="text-3xl sm:text-4xl font-black text-white">Orders Management</h1>
                        <a href="neworder.php" class="group relative overflow-hidden bg-emerald-500 hover:bg-emerald-600 text-white px-4 sm:px-6 py-2 sm:py-3 text-base sm:text-lg font-semibold rounded-xl transition-all duration-300 hover:scale-105 inline-flex items-center">
                            <span class="relative z-10 flex items-center">
                                <i class="ri-add-line mr-2 text-lg sm:text-xl"></i>
                                Add New Order
                            </span>
                        </a>
                    </div>
                    <!-- Orders Table -->
                    <div class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 group rounded-2xl overflow-hidden">
                        <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
                        <div class="overflow-x-hidden relative">
                            <table class="w-full table-responsive">
                                <thead>
                                    <tr class="text-left text-sm text-slate-400 border-b border-slate-700/50">
                                        <th class="px-4 py-3 font-semibold">Order ID</th>
                                        <th class="px-4 py-3 font-semibold order-user">User</th>
                                        <th class="px-4 py-3 font-semibold order-book">Book</th>
                                        <th class="px-4 py-3 font-semibold hidden sm:table-cell">Format</th>
                                        <th class="px-4 py-3 font-semibold">Quantity</th>
                                        <th class="px-4 py-3 font-semibold hidden sm:table-cell">Shipping Charge</th>
                                        <th class="px-4 py-3 font-semibold">Total Amount</th>
                                        <th class="px-4 py-3 font-semibold">Order Date</th>
                                        <th class="px-4 py-3 font-semibold">Payment Status</th>
                                        <th class="px-4 py-3 font-semibold">Confirmation</th>
                                        <th class="px-4 py-3 font-semibold hidden sm:table-cell">Delivery Address</th>
                                        <th class="px-4 py-3 font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "
                                        SELECT 
                                            o.OrderID,
                                            u.Username AS UserName,
                                            b.Title AS BookTitle,
                                            o.Format,
                                            o.Confirmation,
                                            o.Quantity,
                                            o.ShippingCharge,
                                            o.TotalAmount,
                                            o.OrderDate,
                                            o.PaymentStatus,
                                            o.DeliveryAddress
                                        FROM orders o
                                        JOIN users u ON o.UserID = u.UserID
                                        JOIN books b ON o.BookID = b.BookID
                                    ";
                                    $result = $conn->query($sql);
                                    if (!$result) {
                                        error_log("Orders query failed: " . $conn->error);
                                        echo '<tr><td colspan="12" class="text-center px-4 py-6 text-slate-400">Error fetching data: ' . htmlspecialchars($conn->error) . '</td></tr>';
                                    } elseif ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr class="border-b border-slate-700/50 group/item">
                                        <td class="px-4 py-4 text-sm text-slate-300" data-label="Order ID"><?php echo htmlspecialchars($row['OrderID']); ?></td>
                                        <td class="px-4 py-4 text-sm text-slate-300 group-hover/item:text-emerald-400 transition-colors duration-300 order-user" data-label="User"><?php echo htmlspecialchars($row['UserName']); ?></td>
                                        <td class="px-4 py-4 text-sm text-slate-300 group-hover/item:text-emerald-400 transition-colors duration-300 order-book" data-label="Book"><?php echo htmlspecialchars($row['BookTitle']); ?></td>
                                        <td class="px-4 py-4 text-sm text-slate-300" data-label="Format"><?php echo htmlspecialchars($row['Format']); ?></td>
                                        <td class="px-4 py-4 text-sm text-slate-300" data-label="Quantity"><?php echo htmlspecialchars($row['Quantity']); ?></td>
                                        <td class="px-4 py-4 text-sm text-slate-300" data-label="Shipping Charge"><?php echo htmlspecialchars($row['ShippingCharge']); ?></td>
                                        <td class="px-4 py-4 text-sm text-slate-300" data-label="Total Amount"><?php echo htmlspecialchars($row['TotalAmount']); ?></td>
                                        <td class="px-4 py-4 text-sm text-slate-300" data-label="Order Date"><?php echo htmlspecialchars($row['OrderDate']); ?></td>
                                        <td class="px-4 py-4 text-sm text-slate-300" data-label="Payment Status"><?php echo htmlspecialchars($row['PaymentStatus']); ?></td>
                                        <td class="px-4 py-4 text-sm text-slate-300" data-label="Confirmation"><?php echo $row['Confirmation'] ? 'Yes' : 'No'; ?></td>
                                        <td class="px-4 py-4 text-sm text-slate-300" data-label="Delivery Address"><?php echo htmlspecialchars($row['DeliveryAddress']); ?></td>
                                        <td class="px-4 py-4" data-label="Actions">
                                            <div class="flex items-center gap-2">
                                                <a href="orderupdate.php?id=<?php echo htmlspecialchars($row['OrderID']); ?>" class="action-btn w-8 h-8 flex items-center justify-center text-slate-400 hover:bg-slate-700/50 hover:text-emerald-400 rounded-full transition-all duration-300">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                <form method="POST" action="deleteorder.php" onsubmit="return confirm('Are you sure you want to delete this order?');" class="inline m-0 p-0">
                                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['OrderID']); ?>">
                                                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                                    <button type="submit" class="action-btn w-8 h-8 flex items-center justify-center text-slate-400 hover:bg-slate-700/50 hover:text-red-400 rounded-full transition-all duration-300">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="12" class="text-center px-4 py-6 text-slate-400">No orders found.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Debug table and sidebar
            const tableContainer = document.querySelector('#orders-page .table-responsive');
            console.log('Table width:', tableContainer.offsetWidth, 'px');
            console.log('Viewport width:', window.innerWidth, 'px');
            console.log('Table scrollable:', tableContainer.scrollWidth > tableContainer.clientWidth ? 'Yes (X-axis)' : 'No');
            console.log('Table height:', tableContainer.offsetHeight, 'px');
            console.log('Table scrollable:', tableContainer.scrollHeight > tableContainer.clientHeight ? 'Yes (Y-axis)' : 'No');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            console.log('Sidebar state:', sidebar.classList.contains('collapsed') ? 'Collapsed' : 'Expanded');
            console.log('Main content margin-left:', mainContent.style.marginLeft || getComputedStyle(mainContent).marginLeft);
        });
    </script>
</body>
</html>