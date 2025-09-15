<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Kasir App')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Tambahkan ini di head atau sebelum penutup body -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom CSS -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <!-- Boxicons CSS -->
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
    {{-- STYLE KASIR --}}
    <style>
        :root {
            --primary: #7a4b47;
            --secondary: #ffbe5e;
            --light: #f8f9fa;
            --dark: #343a40;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            overflow-x: hidden;
        }

        .pos-container {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            height: 100vh;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        /* Product Section */
        .product-section {
            padding: 20px;
            background-color: white;
            overflow-y: auto;
            position: relative;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            animation: fadeInDown 0.5s ease;
        }

        .header h2 {
            color: var(--primary);
            font-weight: 600;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header h2 i {
            color: var(--secondary);
        }

        .search-box {
            position: relative;
            width: 300px;
            animation: fadeInRight 0.5s ease;
        }

        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 30px;
            outline: none;
            font-size: 14px;
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .search-box input:focus {
            border-color: var(--primary);
            box-shadow: 0 2px 10px rgba(122, 75, 71, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 12px;
            color: #999;
        }

        .category-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            overflow-x: auto;
            padding-bottom: 10px;
            animation: fadeIn 0.6s ease;
        }

        .category-tabs::-webkit-scrollbar {
            height: 5px;
        }

        .category-tabs::-webkit-scrollbar-thumb {
            background-color: rgba(122, 75, 71, 0.3);
            border-radius: 10px;
        }

        .category-tab {
            padding: 8px 20px;
            background-color: #f0f0f0;
            border-radius: 30px;
            font-size: 14px;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.3s ease;
            font-weight: 500;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.05);
        }

        .category-tab:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .category-tab.active {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 8px rgba(122, 75, 71, 0.2);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 20px;
            animation: fadeInUp 0.6s ease;
        }

        .product-card {
            border: 1px solid #eee;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
            background: white;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            height: 130px;
            background-color: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0));
            z-index: 1;
        }

        .product-info {
            padding: 12px;
            position: relative;
            z-index: 2;
        }

        .product-name {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 14px;
            color: var(--dark);
        }

        .product-price {
            color: var(--primary);
            font-weight: bold;
            font-size: 15px;
        }

        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--secondary);
            color: white;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            z-index: 3;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Cart Section */
        .cart-section {
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
            border-left: 1px solid #ddd;
            position: relative;
        }

        .cart-header {
            padding: 20px;
            background-color: var(--primary);
            color: white;
            position: relative;
            animation: fadeInDown 0.5s ease;
        }

        .cart-header h2 {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cart-header h2 i {
            color: var(--secondary);
        }

        .cart-body {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            animation: fadeIn 0.6s ease;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
            animation: fadeInRight 0.4s ease;
        }

        .cart-item:hover {
            background-color: rgba(255, 255, 255, 0.7);
            transform: translateX(5px);
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 500;
            color: var(--dark);
        }

        .cart-item-price {
            color: var(--primary);
            font-size: 14px;
            font-weight: 600;
        }

        .cart-item-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quantity-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: #eee;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--dark);
            font-weight: bold;
        }

        .quantity-btn:hover {
            background-color: var(--primary);
            color: white;
            transform: scale(1.1);
        }

        .quantity-btn.minus:hover {
            background-color: var(--danger);
        }

        .quantity-btn.plus:hover {
            background-color: var(--success);
        }

        .quantity-input {
            width: 40px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .quantity-input:focus {
            border-color: var(--primary);
            outline: none;
        }

        .remove-btn {
            color: var(--danger);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .remove-btn:hover {
            background-color: rgba(220, 53, 69, 0.1);
            transform: rotate(90deg);
        }

        .cart-summary {
            padding: 20px;
            background-color: white;
            border-top: 1px solid #ddd;
            animation: fadeInUp 0.5s ease;
            /* Tambahan untuk memastikan tombol tetap di bawah */
            margin-top: auto;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 15px;
        }

        .total-row {
            font-weight: bold;
            font-size: 18px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            margin-top: 20px;
        }

        .btn {
            padding: 12px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: #6a403c;
            box-shadow: 0 5px 15px rgba(122, 75, 71, 0.2);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-success {
            background-color: var(--success);
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .empty-cart {
            text-align: center;
            color: #999;
            padding: 50px 0;
            animation: fadeIn 0.6s ease;
        }

        .empty-cart i {
            font-size: 50px;
            margin-bottom: 15px;
            color: #ddd;
            animation: bounce 2s infinite;
        }

        .empty-cart p {
            font-size: 16px;
            margin-top: 10px;
        }

        .customer-info {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            animation: fadeInDown 0.5s ease;
        }

        .customer-info h4 {
            margin-bottom: 10px;
            font-size: 16px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .customer-info h4 i {
            color: var(--secondary);
        }

        .customer-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .customer-input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        /* Style untuk fitur tambahan */
        .discount-section {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            animation: fadeInUp 0.5s ease;
        }

        .discount-section h4 {
            margin-bottom: 10px;
            font-size: 16px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .discount-section h4 i {
            color: var(--secondary);
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .payment-method {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .payment-method:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        .payment-method.active {
            border-color: var(--primary);
            background-color: rgba(122, 75, 71, 0.1);
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(122, 75, 71, 0.1);
        }

        .payment-method i {
            font-size: 24px;
            margin-bottom: 5px;
            color: var(--primary);
        }

        .payment-method div {
            font-size: 12px;
            font-weight: 500;
        }

        .discount-input {
            display: flex;
            gap: 10px;
        }

        .discount-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .discount-input input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .discount-input button {
            padding: 10px 15px;
            background-color: var(--secondary);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            color: white;
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .discount-input button:hover {
            background-color: #ffb144;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(255, 190, 94, 0.2);
        }

        .notes-section {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            animation: fadeInUp 0.5s ease;
        }

        .notes-section h4 {
            margin-bottom: 10px;
            font-size: 16px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .notes-section h4 i {
            color: var(--secondary);
        }

        .notes-section textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            resize: vertical;
            min-height: 80px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .notes-section textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .change-section {
            display: none;
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            margin-top: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            animation: fadeIn 0.5s ease;
        }

        .change-section h5 {
            margin-bottom: 10px;
            font-size: 15px;
            color: var(--primary);
        }

        .cash-input {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .cash-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .cash-input input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .cash-input button {
            padding: 10px 15px;
            background-color: var(--primary);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            color: white;
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cash-input button:hover {
            background-color: #6a403c;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(122, 75, 71, 0.2);
        }

        /* Perubahan utama untuk tombol Bayar */
        .cart-section {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .cart-body {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 20px;
        }

        .cart-summary {
            position: sticky;
            bottom: 0;
            background-color: white;
            z-index: 10;
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1);
        }

        .action-buttons {
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        }

        .btn-warning {
            background-color: var(--secondary);
            color: white;
        }

        .btn-warning:hover {
            background-color: #ffb144;
            box-shadow: 0 5px 15px rgba(255, 190, 94, 0.2);
        }

        .receipt-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        .receipt-content {
            background-color: white;
            width: 90%;
            max-width: 400px;
            border-radius: 15px;
            padding: 25px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            animation: slideUp 0.4s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header img {
            max-width: 180px;
            margin-bottom: 15px;
            filter: drop-shadow(0 2px 5px rgba(0, 0, 0, 0.1));
        }

        .receipt-header h3 {
            color: var(--primary);
            margin-bottom: 5px;
            font-size: 1.5rem;
        }

        .receipt-header p {
            color: #666;
            font-size: 13px;
            margin-bottom: 3px;
        }

        .receipt-details {
            margin-bottom: 20px;
            font-size: 13px;
        }

        .receipt-details div {
            margin-bottom: 5px;
        }

        .receipt-items {
            margin: 20px 0;
        }

        .receipt-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .receipt-total {
            border-top: 1px dashed #333;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 14px;
        }

        .receipt-total div {
            margin-bottom: 8px;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 25px;
            font-size: 12px;
            color: #666;
            line-height: 1.5;
        }

        .close-receipt {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 24px;
            color: var(--danger);
            cursor: pointer;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .close-receipt:hover {
            background-color: rgba(220, 53, 69, 0.1);
            transform: rotate(90deg);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-15px);
            }

            60% {
                transform: translateY(-7px);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Floating animation for product cards */
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        /* Floating effect on hover */
        .product-card:hover {
            animation: float 2s ease-in-out infinite;
        }

        /* Notification badge */
        .notification-badge {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--primary);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(122, 75, 71, 0.3);
            z-index: 100;
            transition: all 0.3s;
            animation: pulse 2s infinite;
        }

        .notification-badge:hover {
            transform: scale(1.1);
            animation: none;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .pos-container {
                grid-template-columns: 1fr;
                height: auto;
                min-height: 100vh;
            }

            .cart-section {
                border-left: none;
                border-top: 1px solid #ddd;
                min-height: auto;
            }

            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .search-box {
                width: 100%;
            }

            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .payment-methods {
                grid-template-columns: repeat(2, 1fr);
            }

            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }

            .btn {
                padding: 10px;
                font-size: 13px;
            }
        }
    </style>

    {{-- STYLE LAINNYA --}}
    <style>
        .content-area-desktop-only {
            margin-left: 0;
        }

        @media (min-width: 992px) {
            .content-area-desktop-only {
                margin-left: 270px;
            }
        }

        :root {
            --primary: #7a4b47;
            --secondary: #ffbe5e;
            --light: #f8f9fa;
            --dark: #343a40;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .sidebar-header {
            padding: 20px;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-header h3 {
            font-weight: 600;
        }

        .sidebar-header i {
            color: var(--secondary);
        }

        .sidebar-menu {
            padding: 15px 0;
            overflow-y: auto;
            max-height: calc(100vh - 70px);
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            cursor: pointer;
        }

        .menu-item:hover {
            background-color: rgba(122, 75, 71, 0.1);
            color: var(--primary);
            border-left-color: var(--primary);
        }

        .menu-item.active {
            background-color: rgba(122, 75, 71, 0.1);
            color: var(--primary);
            border-left-color: var(--primary);
            font-weight: 500;
        }

        .menu-item i {
            width: 20px;
            text-align: center;
        }

        .submenu {
            padding-left: 40px;
            display: none;
        }

        .submenu.show {
            display: block;
        }

        .submenu .menu-item {
            padding: 10px 15px;
            font-size: 14px;
        }

        .menu-item.has-submenu::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 12px;
            transition: all 0.3s;
        }

        .menu-item.has-submenu.active::after {
            transform: rotate(180deg);
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Main Content */
        .main-content {
            margin-right: 0;
            transition: all 0.3s;
        }

        .main-content.sidebar-open {
            margin-right: 250px;
        }

        /* Top Navigation */
        .top-nav {
            background-color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .toggle-sidebar {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--primary);
            cursor: pointer;
            display: block;
            position: absolute;
            left: 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            order: 2;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .user-name {
            font-weight: 500;
        }

        .user-dropdown {
            position: relative;
            cursor: pointer;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 50px;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 10px 0;
            display: none;
            z-index: 100;
        }

        .dropdown-menu.show {
            display: block;
            animation: fadeInDown 0.3s;
        }

        .dropdown-item {
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s;
        }

        .dropdown-item:hover {
            background-color: rgba(122, 75, 71, 0.1);
            color: var(--primary);
        }

        /* Content Area */
        .content-area {
            padding: 20px;
            margin-left: 0;
            transition: all 0.3s;
        }

        @media (min-width: 992px) {
            .content-area {
                margin-left: 250px;
                /* Sesuaikan dengan lebar sidebar */
            }
        }


        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .page-title {
            color: var(--primary);
            font-weight: 600;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-title i {
            color: var(--secondary);
        }

        .btn {
            padding: 10px 15px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: #6a403c;
            box-shadow: 0 5px 15px rgba(122, 75, 71, 0.2);
        }

        .btn-success {
            background-color: var(--success);
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: var(--secondary);
            color: white;
        }

        .btn-warning:hover {
            background-color: #ffb144;
            box-shadow: 0 5px 15px rgba(255, 190, 94, 0.2);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            width: 100%;
            /* Pastikan mengambil lebar penuh */
            padding: 0;
            /* Hilangkan padding default jika ada */
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .card-icon.primary {
            background-color: var(--primary);
        }

        .card-icon.success {
            background-color: var(--success);
        }

        .card-icon.warning {
            background-color: var(--warning);
        }

        .card-icon.danger {
            background-color: var(--danger);
        }

        .card-title {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        .card-value {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .card-footer {
            font-size: 12px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .card-footer.positive {
            color: var(--success);
        }

        .card-footer.negative {
            color: var(--danger);
        }

        /* Tables */
        .table-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* thead {
            background-color: var(--primary);
            color: rgb(255, 255, 255);
        } */

        th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 500;
        }

        tbody tr {
            border-bottom: 1px solid #eee;
            transition: all 0.3s;
        }

        tbody tr:hover {
            background-color: rgba(122, 75, 71, 0.05);
        }

        td {
            padding: 12px 15px;
            color: #555;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .status-badge.warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .status-badge.danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 5px;
        }

        /* Forms */
        .form-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            background-color: white;
        }

        .form-select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .form-textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            resize: vertical;
            min-height: 100px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        /* Charts */
        .chart-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .chart-title {
            font-weight: 500;
            color: var(--dark);
        }

        .chart-actions {
            display: flex;
            gap: 10px;
        }

        .chart-wrapper {
            position: relative;
            height: 300px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background-color: white;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.3s;
        }

        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-weight: 600;
            color: var(--primary);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive */
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0);
            }

            .main-content {
                margin-right: 0;
                width: calc(100% - 250px);
                /* Sesuaikan dengan lebar sidebar */
            }

            .toggle-sidebar {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .dashboard-cards {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 576px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .chart-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .chart-actions {
                width: 100%;
            }

            .form-select {
                width: 100%;
            }
        }

        /* Pastikan konten utama tidak terhalang sidebar */
        .page-header,
        .card,
        .form-container,
        .table-container,
        .chart-container {
            width: 100%;
            margin-right: 0;
        }
    </style>

    {{-- STYLE Pengguna --}}
    <style>
        :root {
            --primary: #7a4b47;
            --secondary: #ffbe5e;
            --light: #f8f9fa;
            --dark: #343a40;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 100;
        }

        .sidebar-header {
            padding: 20px;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-header h3 {
            font-weight: 600;
        }

        .sidebar-header i {
            color: var(--secondary);
        }

        .sidebar-menu {
            padding: 15px 0;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background-color: rgba(122, 75, 71, 0.1);
            color: var(--primary);
            border-left-color: var(--primary);
        }

        .menu-item.active {
            background-color: rgba(122, 75, 71, 0.1);
            color: var(--primary);
            border-left-color: var(--primary);
            font-weight: 500;
        }

        .menu-item i {
            width: 20px;
            text-align: center;
        }

        .submenu {
            padding-left: 40px;
            display: none;
        }

        .submenu .menu-item {
            padding: 10px 15px;
            font-size: 14px;
        }

        .menu-item.has-submenu::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 12px;
            transition: all 0.3s;
        }

        .menu-item.has-submenu.active::after {
            transform: rotate(180deg);
        }

        /* Main Content */
        .main-content {
            width: 100%;
            transition: all 0.3s;
            margin-right: 0;
        }

        /* Top Navigation X Sidebar */
        .top-nav {
            background-color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .toggle-sidebar {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--primary);
            cursor: pointer;
            display: none;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .user-name {
            font-weight: 500;
        }

        .user-dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 50px;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 10px 0;
            display: none;
            z-index: 100;
        }

        .dropdown-menu.show {
            display: block;
            animation: fadeInDown 0.3s;
        }

        .dropdown-item {
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s;
        }

        .dropdown-item:hover {
            background-color: rgba(122, 75, 71, 0.1);
            color: var(--primary);
        }

        /* Content Area */
        .content-area {
            padding: 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .page-title {
            color: var(--primary);
            font-weight: 600;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-title i {
            color: var(--secondary);
        }

        .btn {
            padding: 10px 15px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: #6a403c;
            box-shadow: 0 5px 15px rgba(122, 75, 71, 0.2);
        }

        .btn-success {
            background-color: var(--success);
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: var(--secondary);
            color: white;
        }

        .btn-warning:hover {
            background-color: #ffb144;
            box-shadow: 0 5px 15px rgba(255, 190, 94, 0.2);
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .card-icon.primary {
            background-color: var(--primary);
        }

        .card-icon.success {
            background-color: var(--success);
        }

        .card-icon.warning {
            background-color: var(--warning);
        }

        .card-icon.danger {
            background-color: var(--danger);
        }

        .card-title {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        .card-value {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .card-footer {
            font-size: 12px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .card-footer.positive {
            color: var(--success);
        }

        .card-footer.negative {
            color: var(--danger);
        }

        /* Tables */
        .table-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: var(--primary);
            color: white;
        }

        th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 500;
        }

        tbody tr {
            border-bottom: 1px solid #eee;
            transition: all 0.3s;
        }

        tbody tr:hover {
            background-color: rgba(122, 75, 71, 0.05);
        }

        td {
            padding: 12px 15px;
            color: #555;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .status-badge.warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .status-badge.danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 5px;
        }

        /* Forms */
        .form-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            background-color: white;
        }

        .form-select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .form-textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            resize: vertical;
            min-height: 100px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
            animation: fadeIn 0.3s;
        }

        .modal-dialog {
            background-color: white;
            border-radius: 10px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .modal-header {
            padding: 15px 20px;
            background-color: var(--primary);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 15px 20px;
            background-color: #f9f9f9;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .toggle-sidebar {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .dashboard-cards {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 576px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>

    {{-- STYLE Pengaturan --}}
    <style>
        :root {
            --primary: #7a4b47;
            --secondary: #ffbe5e;
            --light: #f8f9fa;
            --dark: #343a40;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 100;
        }

        .sidebar-header {
            padding: 20px;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-header h3 {
            font-weight: 600;
        }

        .sidebar-header i {
            color: var(--secondary);
        }

        .sidebar-menu {
            padding: 15px 0;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background-color: rgba(122, 75, 71, 0.1);
            color: var(--primary);
            border-left-color: var(--primary);
        }

        .menu-item.active {
            background-color: rgba(122, 75, 71, 0.1);
            color: var(--primary);
            border-left-color: var(--primary);
            font-weight: 500;
        }

        .menu-item i {
            width: 20px;
            text-align: center;
        }

        .submenu {
            padding-left: 40px;
            display: none;
        }

        .submenu .menu-item {
            padding: 10px 15px;
            font-size: 14px;
        }

        .menu-item.has-submenu::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 12px;
            transition: all 0.3s;
        }

        .menu-item.has-submenu.active::after {
            transform: rotate(180deg);
        }

        /* Main Content */
        .main-content {
            width: 100%;
            transition: all 0.3s;
            margin-right: 0;
        }

        /* Top Navigation */
        .top-nav {
            background-color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .toggle-sidebar {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--primary);
            cursor: pointer;
            display: none;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .user-name {
            font-weight: 500;
        }

        .user-dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 50px;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 10px 0;
            display: none;
            z-index: 100;
        }

        .dropdown-menu.show {
            display: block;
            animation: fadeInDown 0.3s;
        }

        .dropdown-item {
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s;
        }

        .dropdown-item:hover {
            background-color: rgba(122, 75, 71, 0.1);
            color: var(--primary);
        }

        /* Content Area */
        .content-area {
            padding: 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .page-title {
            color: var(--primary);
            font-weight: 600;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-title i {
            color: var(--secondary);
        }

        .btn {
            padding: 10px 15px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: #6a403c;
            box-shadow: 0 5px 15px rgba(122, 75, 71, 0.2);
        }

        .btn-success {
            background-color: var(--success);
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: var(--secondary);
            color: white;
        }

        .btn-warning:hover {
            background-color: #ffb144;
            box-shadow: 0 5px 15px rgba(255, 190, 94, 0.2);
        }

        /* Settings Tabs */
        .settings-tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .tab-btn {
            padding: 10px 20px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            color: #666;
            cursor: pointer;
            transition: all 0.3s;
        }

        .tab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        .tab-btn:hover:not(.active) {
            color: var(--dark);
            border-bottom-color: #ddd;
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s;
        }

        /* Settings Card */
        .settings-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .settings-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .settings-card-title {
            font-weight: 600;
            color: var(--dark);
        }

        /* Forms */
        .form-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            background-color: white;
        }

        .form-select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .form-textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            resize: vertical;
            min-height: 100px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .toggle-sidebar {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .settings-tabs {
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 5px;
            }

            .tab-btn {
                padding: 10px 15px;
            }
        }

        @media (max-width: 576px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>


    {{-- STYLE Tambahan Payment --}}
    <style>
        .payment-details {
            animation: fadeIn 0.5s ease;
        }

        .payment-details h5 {
            margin-bottom: 10px;
            font-size: 15px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .payment-details h5 i {
            color: var(--secondary);
        }

        /* Masking untuk input kartu */
        input[data-mask] {
            letter-spacing: 1px;
        }
    </style>

    {{-- STYLE Tambahan Kasir --}}
    <style>
        .pos-container {
            display: flex;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        .product-section {
            flex: 3;
            padding: 20px;
            background: #f8f9fa;
            overflow-y: auto;
        }

        .cart-section {
            flex: 1;
            background: white;
            border-left: 1px solid #eee;
            display: flex;
            flex-direction: column;
        }

        .category-tabs {
            display: flex;
            margin: 15px 0;
            gap: 10px;
            overflow-x: auto;
        }

        .category-tab {
            padding: 8px 15px;
            background: #e9ecef;
            border-radius: 20px;
            cursor: pointer;
            white-space: nowrap;
            font-size: 14px;
        }

        .category-tab.active {
            background: #7a4b47;
            color: white;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }

        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        /* ... [CSS lainnya tetap sama] ... */
    </style>


    @livewireStyles
</head>

<body>

    <div>
        {{ $slot }}
    </div>

    @livewireScripts
    {{-- @livewireAlertScripts --}}

    <!-- Custom Script -->
    <script src="{{ asset('asset_offline/script.js') }}"></script>
</body>

</html>